<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\Magento\ConfigMergerFactory;
use Throwable;

class XmlMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @var ConfigMergerFactory
     */
    private $mergerFactory;

    public function __construct(ConfigMergerFactory $mergerFactory)
    {
        $this->mergerFactory = $mergerFactory;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        $merger = $this->mergerFactory->create(
            $oldContent,
            isset($this->params['idAttributes']) ? $this->params['idAttributes'] : [],
            isset($this->params['typeAttributeName']) ? $this->params['typeAttributeName'] : null
        );
        try {
            $merger->merge($newContent);
        } catch (Throwable $t) {
            throw new InvalidArgumentException('Root nodes cannot be different.');
        }
        $outXML = $merger->getDom()->saveXML();

        return $this->prettyPrint($outXML);
    }

    /**
     * @param string $xml
     * @return string
     */
    private function prettyPrint(string $xml)
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        return str_replace('  ', "\t", $dom->saveXML());
    }
}