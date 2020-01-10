<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Order;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Root;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\Node;

class NodeTree
{
    /** @var Order */
    private $_order;

    /** @var Root */
    private $_root;

    public $iteration = 0;

    /**
     * NodeTree constructor.
     * @param Order $order
     * @param Root $root
     */
    public function __construct(
        Order $order,
        Root $root
    ) {
        $this->_order = $order;
        $this->_root = $root;
    }

    public function handle(NodeWrapper $wrapper)
    {
        $this->grow($this->_root, $wrapper);
        $this->_root->resolve();
    }

    public function getName(Node $node)
    {
        $reflection = new \ReflectionClass(get_class($node));
        if ($reflection->hasMethod('__toString') === true) {
            return (string)$node;
        }
        if ($reflection->hasProperty('name')) {
            return (string)$node->name;
        }
        if ($reflection->hasProperty('var')) {
            return (string)$node->var->name;
        }
        if ($reflection->hasProperty('expr')) {
            return (string)$node->expr->var->name;
        }

        return Root::GROUP_MARKER . get_class($node);
    }

    public function grow(Root $root, Node $node, $param = 'stmts')
    {
        $root = $root->down($this->getName($node));
        $root->addNode($node, $param);

        foreach ($node->getSubNodeNames() as $propertyName) {
            if (is_array($node->$propertyName)) {
                foreach ($node->$propertyName as $single) {
                    if ($single instanceof Node) {
                        $this->grow($root, $single, $propertyName);
                    }
                }
            }
        };
        return $this;
    }
}