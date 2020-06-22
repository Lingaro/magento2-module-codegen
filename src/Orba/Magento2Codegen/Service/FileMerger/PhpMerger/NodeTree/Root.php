<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use Exception;
use PhpParser\Node;
use ReflectionException;

/**
 * Class Root
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree
 */
class Root
{
    const ROOT = 'root';
    const MARKER_GROUP = "{GROUP}";
    const SET_ABLE_PARAMS = 'set_able';
    const ADD_ABLE_NODES = 'add_able';
    const KEEP_ABLE_NODES = 'keep_able';

    const RESOLVE_KEYS = [
        self::SET_ABLE_PARAMS,
        self::ADD_ABLE_NODES,
        self::KEEP_ABLE_NODES
    ];

    /** @var string */
    private $_name = self::ROOT;

    /** @var RootFactory */
    private $_rootFactory;

    /** @var RelationFactory */
    private $_relationFactory;

    /** @var Order */
    private $_order;

    /** @var NodeCleaner */
    private $_nodeCleaner;

    /** @var array */
    private $_relations = [], $_nodes = [], $_objectHashes = [];

    /** @var Node */
    private $_lastNode;

    /** @var self */
    private $_up;

    /** @var self */
    private $_down;

    /**
     * Root constructor.
     * @param RootFactory $rootFactory
     * @param RelationFactory $relationFactory
     * @param Order $order
     * @param NodeCleaner $nodeCleaner
     * @param string|null $rootName
     */
    public function __construct(
        RootFactory $rootFactory,
        RelationFactory $relationFactory,
        Order $order,
        NodeCleaner $nodeCleaner,
        string $rootName = null
    ) {
        $this->_rootFactory = $rootFactory;
        $this->_relationFactory = $relationFactory;
        $this->_order = $order;
        $this->_nodeCleaner = $nodeCleaner;
        if ($rootName) {
            $this->_name = $rootName;
        }
    }

    /**
     * @return $this|array
     * @throws Exception
     */
    public function resolve()
    {
        if (empty($this->_up) && empty($this->_down)) {
            throw new \Exception('This Root can\'t be resolved as it has empty down nodes');
        }

        if (!empty($this->up) && empty($this->_relations)) {
            throw new \Exception('Parent param relation array is empty');
        }

        if (!empty($this->_down)) {
            $result = [
                self::SET_ABLE_PARAMS => [],
                self::KEEP_ABLE_NODES => [],
                self::ADD_ABLE_NODES => []
            ];
            /** @var Root $down */
            foreach ($this->_down as $down) {
                $result = array_merge_recursive($result, $down->resolve());
            }

            if (empty($this->_up)) {
                return $this;
            }

            if ($this->_isGroupNamed()) {
                if (!empty($result[self::KEEP_ABLE_NODES])) {
                    $alreadyAccepted = [];
                    foreach ($this->_nodes as $key => $node) {
                        if (!in_array($node, $result[self::KEEP_ABLE_NODES]) || in_array($node, $alreadyAccepted)) {
                            unset($this->_nodes[$key]);
                        }
                        $alreadyAccepted[] = $node;
                    }
                }
            } else {
                $result[self::SET_ABLE_PARAMS] = array_merge_recursive(
                    $result[self::SET_ABLE_PARAMS],
                    $result[self::ADD_ABLE_NODES]
                );
                foreach ($result[self::SET_ABLE_PARAMS] as $param => $nodes) {
                    $nodes = $this->_nodeCleaner->clean($this->_lastNode, $nodes);
                    $this->_lastNode->$param = $this->_order->sort($nodes);
                }
            }
        }

        if ($this->_isGroupNamed()) {
            return $this->_getGroupNodes();
        } else {
            return $this->_getValidNodes();
        }
    }

    /**
     * @param Node $node
     * @param string $parentParamName
     * @return $this
     * @throws ReflectionException
     */
    public function handleNode(Node $node, string $parentParamName): self
    {
        $rootName = $this->_getProperName($node);
        if (!isset($this->_down[$rootName])) {
            $this->_down[$rootName] = $this->_rootFactory->create($rootName)
                ->setUpRoot($this);
        }
        /** @var self $root */
        $root = $this->_down[$rootName];
        $root->addNode($node, $parentParamName);
        return $root;
    }

    /**
     * @param Node $node
     * @param string $param
     * @return $this
     */
    public function addNode(Node $node, string $param): self
    {
        $this->_nodes[] = $node;
        $this->_lastNode = $node;
        if ($this->_up) {
            $relation = $this->_getParentParamsRelation($node);
            $relation->setParent($this->_up->getLastNode())
                ->setParam($param);
        }
        return $this;
    }

    /**
     * @param Root $up
     * @return $this
     */
    public function setUpRoot(self $up): self
    {
        $this->_up = $up;
        return $this;
    }

    /**
     * @return Node
     */
    public function getLastNode()
    {
        return $this->_lastNode;
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        $name = '';
        if (empty($this->_up)) {
            return $this->_name;
        } else {
            $name .= $this->_up->getFullPath() . "_" . $this->_name;
        }
        return $name;
    }

    /**
     * @param Node $node
     * @return Relation
     */
    private function &_getParentParamsRelation(Node $node): Relation
    {
        $hash = $this->_getObjectHash($node);
        if (!isset($this->_relations[$hash])) {
            $this->_relations[$hash] = $this->_relationFactory->create();
        }

        return $this->_relations[$hash];
    }

    /**
     * @param Object $object
     * @return string
     */
    private function _getObjectHash(Object $object): string
    {
        $hash = spl_object_hash($object);
        if (!isset($this->_objectHashes[$hash])) {
            $this->_objectHashes[$hash] = "h" . count($this->_objectHashes);
        }
        return $this->_objectHashes[$hash];
    }

    /**
     * @return bool
     */
    private function _isGroupNamed(): bool
    {
        return strpos($this->_name, self::MARKER_GROUP) !== false;
    }

    /**
     * @param Node $node
     * @return string
     * @throws ReflectionException
     */
    private function _getProperName(Node $node): string
    {
        $name = [];
        $class = get_class($node);
        $reflection = new \ReflectionClass($class);
        if ($reflection->hasMethod('__toString') === true) {
            $name[] = (string)$node;
        }
        if ($reflection->hasProperty('name')) {
            $name[] = (string)$node->name;
        }
        if ($reflection->hasProperty('var')) {
            $name[] = (string)$node->var->name;
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

    /**
     * @return array
     * @throws Exception
     */
    private function _getGroupNodes(): array
    {
        $result = [];
        foreach ($this->_nodes as $node) {
            $relation = $this->_getParentParamsRelation($node);
            $result[self::SET_ABLE_PARAMS][$relation->getParam()][] = $node;
        }
        return $this->_ensureProperResult($result, __FUNCTION__);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function _getValidNodes(): array
    {
        $grouped = $result = [];

        foreach ($this->_nodes as $node) {
            $grouped[get_class($node)][] = $node;
        }

        foreach ($grouped as $nodes) {
            /** @var Node $valid */
            $valid = end($nodes);
            $relation = $this->_getParentParamsRelation($valid);
            $result[self::KEEP_ABLE_NODES][] = $relation->getParent();
            $result[self::ADD_ABLE_NODES][$relation->getParam()][] = $valid;
        }
        return $this->_ensureProperResult($result, __FUNCTION__);
    }

    /** @var array */
    private static $_ensured = [];

    /**
     * @param array $result
     * @param string $source
     * @return array
     * @throws Exception
     */
    private function _ensureProperResult(array $result, string $source): array
    {
        if (isset(self::$_ensured[$source])) {
            return $result;
        }

        $validKeys = $invalidKeys = [];
        foreach (array_keys($result) as $key) {
            if (in_array($key, self::RESOLVE_KEYS)) {
                $validKeys[] = $key;
            } else {
                $invalidKeys[] = $key;
            }
        }

        if (empty($validKeys) || !empty($invalidKeys)) {
            throw new \Exception(
                sprintf("Expected at least one key in result: %s\nFound keys: %s",
                    implode(" ", self::RESOLVE_KEYS),
                    implode(" ", $invalidKeys)
                )
            );
        }

        self::$_ensured[$source] = true;

        return $result;
    }

    /**
     * @param $rootName
     * @return static
     */
    private static function create($rootName): self
    {
        return new self($rootName);
    }

}