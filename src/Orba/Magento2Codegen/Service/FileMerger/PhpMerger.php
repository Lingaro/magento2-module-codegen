<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use Exception;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTreeFactory;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

/**
 * Class PhpMerger
 * @package Orba\Magento2Codegen\Service\FileMerger
 */
class PhpMerger extends AbstractMerger implements MergerInterface
{
    protected $experimental = true;

    /** @var Parser\Multiple */
    private $_parser;

    /** @var PrettyPrinter\Standard */
    private $_printer;

    /** @var NodeWrapper */
    private $_wrapper;

    /** @var NodeTreeFactory */
    private $_treeFactory;

    /**
     * PhpMerger constructor.
     * @param NodeTreeFactory $nodeTreeFactory
     */
    public function __construct(
        NodeTreeFactory $nodeTreeFactory
    ) {
        $this->_parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->_printer = new PrettyPrinter\Standard();
        $this->_wrapper = new NodeWrapper();
        $this->_treeFactory = $nodeTreeFactory;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        $tree = $this->_treeFactory->create();
        $this->_wrapper->wrap(
            $this->_parser->parse($oldContent),
            $this->_parser->parse($newContent)
        );

        try {
            $tree->grow($this->_wrapper)->resolve();
        } catch (Exception $e) {
            throw new Exception("An error occurred while merging:\n" . $e->getMessage());
        }

        return $this->_printer->prettyPrintFile(
            $this->_wrapper->unwrap()
        );
    }
}