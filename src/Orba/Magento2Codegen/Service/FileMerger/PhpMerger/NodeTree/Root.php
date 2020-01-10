<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node;

class Root
{
    const GROUP_MARKER = "{GROUP}";

    /** @var string */
    private $_name = 'root';

    /** @var array */
    private $_relations = [], $_nodes = [], $_objectHashes = [];

    /** @var Node */
    private $_lastNode;

    /** @var self */
    private $_up;

    /** @var self */
    private $_down;

    public function __construct(string $name = null)
    {
        if ($name) {
            $this->_name = $name;
        }
    }

    public function hasGroupMarker(): bool
    {
        return strpos($this->_name, self::GROUP_MARKER) !== false;
    }

    public function addNode(Node $node, string $param): self
    {
        $this->_nodes[] = $node;
        $this->_lastNode = $node;
        if (!empty($this->_up)) {
            $relation = $this->relation($node);
            $relation->setParent($this->_up->getLastNode())
                ->setChild($node)
                ->setParam($param);
        }
        return $this;
    }

    public function getLastNode()
    {
        return $this->_lastNode;
    }

    public function extractGroupNodes()
    {
        $keep = [];
        foreach ($this->_nodes as $node) {
            $relation = $this->relation($node);
            $keep[$relation->getParam()][] = $node;
        }
        return $keep;
    }

    public function extractAcceptedNode()
    {
        $grouped = $result = [];

        foreach ($this->_nodes as $node) {
            $grouped[get_class($node)][] = $node;
        }

        foreach ($grouped as $nodes) {
            /** @var Node $valid */
            $valid = end($nodes);
            $result['accepted'][] = $this->relation($valid)->getParent();
            $result['single'][$this->relation($valid)->getParam()][] = $valid;
        }
        return $result;
    }

    public function resolve()
    {
        if (!empty($this->_down)) {
            $result = ['group' => [], 'accepted' => [], 'single' => []];
            /** @var Root $down */
            foreach ($this->_down as $down) {
                $processed = $down->resolve();
                if ($down->hasGroupMarker()) {
                    $result['group'] = array_merge_recursive($result['group'], $processed);
                } else {
                    $result['accepted'] = array_merge_recursive($result['accepted'], $processed['accepted']);
                    $result['single'] = array_merge_recursive($result['single'], $processed['single']);
                }
            }

            if (empty($this->_up)) {
                return $this;
            }

            if ($this->hasGroupMarker()) {
                if (!empty($result['accepted'])) {
                    $accepted = [];
                    foreach ($this->_nodes as $key => $node) {
                        if (!in_array($node, $result['accepted']) || in_array($node, $accepted)) {
                            unset($this->_nodes[$key]);
                        }
                        $accepted[] = $node;
                    }
                }
            } else {
                $result['group'] = array_merge_recursive($result['group'], $result['single']);
                foreach ($result['group'] as $param => $group) {
                    $this->_lastNode->$param = $group;
                }
            }
        }

        if ($this->hasGroupMarker()) {
            return $this->extractGroupNodes();
        } else {
            return $this->extractAcceptedNode();
        }
    }

    public function up(self $up = null): ?self
    {
        if ($up instanceof self) {
            $this->_up = $up;
            return $this;
        }
        if ($this->_up instanceof self) {
            return $this->_up;
        }
        return null;
    }

    public function down(string $name): self
    {
        if (!isset($this->_down[$name])) {
            $this->_down[$name] = self::create($name)->up($this);
        }
        return $this->_down[$name];
    }

    public static function create($name): self
    {
        return new self($name);
    }

    private function &relation(Node $node): Relation
    {
        $hash = $this->_getObjectHash($node);
        if (!isset($this->_relations[$hash])) {
            $this->_relations[$hash] = Relation::create();
        }

        return $this->_relations[$hash];
    }

    private function _getObjectHash(Object $object): string
    {
        $hash = spl_object_hash($object);
        if (!isset($this->_objectHashes[$hash])) {
            $this->_objectHashes[$hash] = "h" . count($this->_objectHashes);
        }
        return $this->_objectHashes[$hash];
    }
}