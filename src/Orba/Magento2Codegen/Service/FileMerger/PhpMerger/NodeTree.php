<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use GuzzleHttp\RequestOptions;
use \Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Order;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Group;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Root;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Stmt\Property;

class NodeTree
{
    const COMPARE_NODES = [
        NodeWrapper::class,
        Namespace_::class,
        Class_::class,
    ];

    const ROOT_NODES = [
        NodeWrapper::class,
        Namespace_::class,
        Class_::class,
//        ClassMethod::class
    ];

    /** @var array */
    private $_groups = [];

    /** @var NodeHandler */
    private $_handler;

    /** @var Order */
    private $_order;

    /** @var Root */
    private $_root;

    public $iteration = 0;

    /**
     * NodeTree constructor.
     * @param NodeHandler $nodeHandler
     * @param Order $order
     * @param Root $root
     */
    public function __construct(
        NodeHandler $nodeHandler,
        Order $order,
        Root $root
    ) {
        $this->_handler = $nodeHandler;
        $this->_order = $order;
        $this->_root = $root;
    }

    public function handle(NodeWrapper $wrapper)
    {
        $this->down($wrapper, 'stmts')
            ->build($wrapper, 'stmts')
            ->up()
            ->iterate();

        $this->getGroups();
        $stmts = $this->exclude($wrapper->stmts);
        ksort($stmts);
        $wrapper->stmts = [];

        foreach ($stmts as $s) {
            $wrapper->stmts[] = $s;
        }
    }

    public function down(Node $node, $param): self
    {
        $name = $this->getName($node);
        $this->_root->setName($name)->setNode($node);

        if (!$this->_root->isValid()) {
            $firstValidBranch = $this->_root->getFirstValidBranch();
            $this->group($firstValidBranch->getRootName())
                ->addNode($firstValidBranch->getLastNode());
        } else {
            $this->group($this->_root->getRootName())->addNode($node);
        }

        $this->_root = $this->_root->down();

        return $this;
    }

    private $names = [];

    private function name($name)
    {
        if (!in_array($name, $this->names)) {
            $this->names[] = $name;
        }
        return array_search($name, $this->names);
    }

    public function getName(Node $node)
    {
        if (isset($node->name)) {
            return (string)$node->name;
        }

        if ($node instanceof Use_) {
            $rootCode = $this->name(spl_object_hash($node));
            $name = "{use_}";
            return $name;
        }

        if ($node instanceof Property) {
            $rootCode = $this->name(spl_object_hash($node));
            $name = "{property_}";
            return $name;
        }

        return 'XXX';
    }

    public function up(): self
    {
        $this->_root = $this->_root->up();

        return $this;
    }

    public function build(Node $parent, string $name): self
    {
        if (is_array($parent->$name)) {
            /** @var Node $node */
            foreach ($parent->$name as $node) {
                if (!$node instanceof Node) {
                    continue;
                }
                foreach ($node->getSubNodeNames() as $name) {
                    $this->down($node, $name)
                        ->build($node, $name)
                        ->up();
                }
            }
        }

        return $this;
    }

    public function getGroups()
    {
        $groups = [];

        /** @var Group $group */
        foreach (array_reverse($this->_groups) as $group) {
            $this->candidate($group);
        }

        return $groups;
    }


    public function candidate(Group $group)
    {
        if ($group->isMergeAble()) {
            $processableNodes = $group->getNode();
            $target = end($processableNodes);
            $nodeData = [];
            foreach ($processableNodes as $single) {
                $nodeData[] = $this->getNodeData($single);
            }
            $this->mergeDataToTarget($target, ...$nodeData);
        }
    }

    private function getNodeData(Node $node)
    {
        $subNodeNames = $node->getSubNodeNames();

        $processable = [];

        foreach ($subNodeNames as $name) {
            if (is_array($node->$name) || $node->$name instanceof Node) {
                $processable[$name] = $node->$name;
            }
        }

        return $processable;
    }

    private $_merge = [];

    private function mergeDataToTarget(Node $target, ...$data)
    {
        $tmp = [];

        foreach ($data as $appendData) {
            foreach ($appendData as $name => $append) {
                if (is_array($append)) {
                    if (!isset($tmp[$name])) {
                        $tmp[$name] = [];
                    }
                    $tmp[$name] = array_merge($tmp[$name], $append);
                } else {
                    $tmp[$name] = $append;
                }
            }
        }
        foreach ($tmp as $name => $data) {
            $target->$name = $data;
            $this->_merge[] = [
                $target,
                $name,
                $data
            ];
        }
        return $this;
    }

    private $_excluded = [];

    private function _makeExcluded(Node $node)
    {
        $this->_excluded[] = $node;
    }

    private function _isExcluded(Node $node)
    {
        return in_array($node, $this->_excluded);
    }

    private function exclude(array $nodes)
    {
        foreach ($nodes as $key => $element) {
            if ($this->_isExcluded($element)) {
                unset($nodes[$key]);
            }
        }
        return $nodes;
    }

    private function &group($name): Group
    {
        if (!isset($this->_groups[$name])) {
            $this->_groups[$name] = Group::create();
        }

        return $this->_groups[$name];
    }

    private function iterate(): self
    {
        $this->iteration++;
        return $this;
    }
}