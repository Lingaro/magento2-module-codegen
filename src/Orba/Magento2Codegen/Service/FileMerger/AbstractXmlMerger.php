<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use Orba\Magento2Codegen\Service\Magento\ConfigMergerFactory;

abstract class AbstractXmlMerger implements MergerInterface
{
    /**
     * @var ConfigMergerFactory
     */
    protected $mergerFactory;

    /**
     * @var array
     */
    protected $idAttributes = [];

    /**
     * @var string|null
     */
    protected $typeAttributeName;

    public function __construct(ConfigMergerFactory $mergerFactory)
    {
        $this->mergerFactory = $mergerFactory;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        $merger = $this->mergerFactory->create($oldContent, $this->idAttributes, $this->typeAttributeName);
        $merger->merge($newContent);
        return $merger->getDom()->saveXML();
    }
}