<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use Exception;
use PhpParser\Node;
use ReflectionClass;

use function array_keys;
use function array_merge_recursive;
use function count;
use function end;
use function get_class;
use function implode;
use function in_array;
use function is_null;
use function spl_object_hash;
use function sprintf;
use function strpos;

class Root
{
    private const ROOT = 'root';
    private const MARKER_GROUP = "{GROUP}";
    private const SET_ABLE_PARAMS = 'set_able';
    private const ADD_ABLE_NODES = 'add_able';
    private const KEEP_ABLE_NODES = 'keep_able';
    private const RESOLVE_KEYS = [self::SET_ABLE_PARAMS, self::ADD_ABLE_NODES, self::KEEP_ABLE_NODES];

    private string $name = self::ROOT;
    private RootFactory $rootFactory;
    private RelationFactory $relationFactory;
    private Order $order;
    private NodeCleaner $nodeCleaner;
    private array $objectHashes = [];
    private array $nodes = [];
    private array $relations = [];
    private ?Node $lastNode = null;
    private ?Root $up = null;
    private static array $ensured = [];

    /**
     * @var Root[]
     */
    private array $down = [];

    public function __construct(
        RootFactory $rootFactory,
        RelationFactory $relationFactory,
        Order $order,
        NodeCleaner $nodeCleaner,
        ?string $rootName = null
    ) {
        $this->rootFactory = $rootFactory;
        $this->relationFactory = $relationFactory;
        $this->order = $order;
        $this->nodeCleaner = $nodeCleaner;
        if ($rootName) {
            $this->name = $rootName;
        }
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function resolve(): array
    {
        if (empty($this->up) && empty($this->down)) {
            throw new Exception('This Root can\'t be resolved as it has empty down nodes');
        }

        if (!empty($this->up) && empty($this->relations)) {
            throw new Exception('Parent param relation array is empty');
        }

        if (!empty($this->down)) {
            $result = [
                self::SET_ABLE_PARAMS => [],
                self::KEEP_ABLE_NODES => [],
                self::ADD_ABLE_NODES => []
            ];
            foreach ($this->down as $down) {
                $result = array_merge_recursive($result, $down->resolve());
            }

            if (empty($this->up)) {
                return [];
            }

            if ($this->isGroupNamed()) {
                if (!empty($result[self::KEEP_ABLE_NODES])) {
                    $alreadyAccepted = [];
                    foreach ($this->nodes as $key => $node) {
                        if (!in_array($node, $result[self::KEEP_ABLE_NODES]) || in_array($node, $alreadyAccepted)) {
                            unset($this->nodes[$key]);
                        }
                        $alreadyAccepted[] = $node;
                    }
                } elseif (!empty($result[self::SET_ABLE_PARAMS])) {
                    $this->nodes = $this->nodeCleaner->clean($this->lastNode, $this->nodes);
                }
                return $this->getGroupNodes();
            }
            $result[self::SET_ABLE_PARAMS] = array_merge_recursive(
                $result[self::SET_ABLE_PARAMS],
                $result[self::ADD_ABLE_NODES]
            );
            foreach ($result[self::SET_ABLE_PARAMS] as $param => $nodes) {
                $nodes = $this->nodeCleaner->clean($this->lastNode, $nodes);
                $this->lastNode->$param = $this->order->sort($nodes);
            }
        }

        if ($this->isGroupNamed()) {
            return $this->getGroupNodes();
        }
        return $this->getValidNodes();
    }

    public function handleNode(Node $node, string $parentParamName): self
    {
        $rootName = $this->getProperName($node);
        if (!isset($this->down[$rootName])) {
            $this->down[$rootName] = $this->rootFactory->create($rootName)
                ->setUpRoot($this);
        }
        $root = $this->down[$rootName];
        $root->addNode($node, $parentParamName);
        return $root;
    }

    public function addNode(Node $node, string $param): self
    {
        $this->nodes[] = $node;
        $this->lastNode = $node;
        if ($this->up) {
            $relation = $this->getParentParamsRelation($node);
            $relation->setParent($this->up->getLastNode())
                ->setParam($param);
        }
        return $this;
    }

    public function setUpRoot(Root $up): self
    {
        $this->up = $up;
        return $this;
    }

    public function getLastNode(): ?Node
    {
        return $this->lastNode;
    }

    public function getFullPath(): string
    {
        if (empty($this->up)) {
            return $this->name;
        }
        return $this->up->getFullPath() . "_" . $this->name;
    }

    private function &getParentParamsRelation(Node $node): Relation
    {
        $hash = $this->getObjectHash($node);
        if (!isset($this->relations[$hash])) {
            $this->relations[$hash] = $this->relationFactory->create();
        }
        return $this->relations[$hash];
    }

    private function getObjectHash(object $object): string
    {
        $hash = spl_object_hash($object);
        if (!isset($this->objectHashes[$hash])) {
            $this->objectHashes[$hash] = "h" . count($this->objectHashes);
        }
        return $this->objectHashes[$hash];
    }

    private function isGroupNamed(): bool
    {
        return strpos($this->name, self::MARKER_GROUP) !== false;
    }

    private function getProperName(Node $node): string
    {
        $name = [];
        $class = get_class($node);
        $reflection = new ReflectionClass($class);
        if ($reflection->hasMethod('__toString') === true) {
            $name[] = (string) $node;
        }
        if ($reflection->hasProperty('name')) {
            $name[] = (string) $node->name;
        }
        if ($reflection->hasProperty('var')) {
            $name[] = (string) $node->var->name;
        }

        if ($reflection->hasProperty('extends')) {
            $extends = $node->extends;
            if (!is_null($extends)) {
                $name[] = (string)$extends;
            }
        }

        if (empty($name)) {
            $name[] = self::MARKER_GROUP . $class;
        }

        return implode("|", $name);
    }

    private function getGroupNodes(): array
    {
        $result = [];
        foreach ($this->nodes as $node) {
            $relation = $this->getParentParamsRelation($node);
            $result[self::SET_ABLE_PARAMS][$relation->getParam()][] = $node;
        }
        return $this->ensureProperResult($result, __FUNCTION__);
    }

    private function getValidNodes(): array
    {
        $grouped = $result = [];

        foreach ($this->nodes as $node) {
            $grouped[get_class($node)][] = $node;
        }

        foreach ($grouped as $nodes) {
            /** @var Node $valid */
            $valid = end($nodes);
            $relation = $this->getParentParamsRelation($valid);
            $result[self::KEEP_ABLE_NODES][] = $relation->getParent();
            $result[self::ADD_ABLE_NODES][$relation->getParam()][] = $valid;
        }
        return $this->ensureProperResult($result, __FUNCTION__);
    }

    private function ensureProperResult(array $result, string $source): array
    {
        if (isset(self::$ensured[$source])) {
            return $result;
        }

        $validKeys = $invalidKeys = [];
        foreach (array_keys($result) as $key) {
            if (in_array($key, self::RESOLVE_KEYS)) {
                $validKeys[] = $key;
                continue;
            }
            $invalidKeys[] = $key;
        }

        if (empty($validKeys) || !empty($invalidKeys)) {
            throw new Exception(
                sprintf(
                    "Expected at least one key in result: %s\nFound keys: %s",
                    implode(" ", self::RESOLVE_KEYS),
                    implode(" ", $invalidKeys)
                )
            );
        }

        self::$ensured[$source] = true;

        return $result;
    }
}
