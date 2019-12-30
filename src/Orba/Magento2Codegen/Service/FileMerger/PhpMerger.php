<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeHandler;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

class PhpMerger extends AbstractMerger implements MergerInterface
{

    /** @var NodeHandler */
    private $_tree;

    public function __construct(
        NodeTree $nodeTree
    ) {
        $this->_tree = $nodeTree;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        $Wrapper = new NodeWrapper();
        $Wrapper->stmts = array_merge($parser->parse($oldContent), $parser->parse($newContent));

        $this->_tree->handle($Wrapper);

        $prettyPrinter = new PrettyPrinter\Standard;
        $source = $prettyPrinter->prettyPrintFile($Wrapper->stmts);

        file_put_contents('class.php', $source);

        return $source;
    }
}