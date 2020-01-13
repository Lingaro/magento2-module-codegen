<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use Exception;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Root;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use ReflectionException;

/**
 * Class NodeTree
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger
 */
class NodeTree
{
    /** @var Root */
    private $_root;

    /** @var array */
    private $_roots = [];

    const EXPECTED_BRANCHES = [
        Namespace_::class,
        Class_::class
    ];

    /**
     * NodeTree constructor.
     * @param RootFactory $rootFactory
     */
    public function __construct(
        RootFactory $rootFactory
    ) {
        $this->_root = $rootFactory->create('root');
    }

    /**
     * @param NodeWrapper $wrapper
     * @return $this
     * @throws ReflectionException
     */
    public function grow(NodeWrapper $wrapper): self
    {
        if (!$wrapper->isWrapped()) {
            throw new Exception("Wrapper has no wrapped nodes");
        }

        foreach ($wrapper->getSubNodeNames() as $propertyName) {
            $this->_grow($this->_root, $wrapper, $propertyName);
        }

        return $this;
    }

    public function resolve(): ?self
    {
        if ($this->_canResolve()) {
            $this->_root->resolve();
            return $this;
        }
        return null;
    }

    /**
     * @param Root $root
     * @param Node $node
     * @param $parentParamName
     * @return $this
     * @throws ReflectionException
     */
    private function _grow(Root $root, Node $node, $parentParamName): self
    {
        $root = $root->handleNode($node, $parentParamName);

        foreach ($node->getSubNodeNames() as $propertyName) {
            if (is_array($node->$propertyName)) {
                foreach ($node->$propertyName as $subNode) {
                    if ($subNode instanceof Node) {
                        $this->_grow($root, $subNode, $propertyName);
                    }
                }
            }
        };

        $this->_collectRoot($root);
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function _canResolve(): bool
    {
        $gathered = $this->_getRoots();
        if (empty($gathered)) {
            throw new Exception("No tree gathered roots");
        }

        foreach (self::EXPECTED_BRANCHES as $class) {
            $classes = $this->_getRoots($class);
            if (count($classes) === 1) {
                throw new Exception("To few expected Nodes");
            }
            $prev = null;
            foreach ($classes as $one) {
                if (!$prev) {
                    $prev = $one;
                    continue;
                }
                if ($prev !== $one) {
                    throw new Exception("Mismatched Node names");
                }
            }
        }

        return true;
    }

    /**
     * @param Root $root
     * @return $this
     */
    private function _collectRoot(Root $root)
    {
        $hash = $this->_rootHash();
        $class = get_class($root->getLastNode());
        $this->_roots[$hash][$class][] = $root->getFullPath();
        return $this;
    }

    /**
     * @param string|null $class
     * @return array|mixed
     */
    private function _getRoots(string $class = null)
    {
        $hash = $this->_rootHash();
        if ($class) {
            return $this->_roots[$hash][$class];
        }
        return $this->_roots[$hash];
    }

    /**
     * @return string
     */
    private function _rootHash(): string
    {
        return spl_object_hash($this->_root);
    }
}