<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use DOMDocument;
use InvalidArgumentException;
use Orba\Magento2Codegen\Service\Magento\ConfigMergerFactory;
use Throwable;

use function str_replace;

class XmlMerger extends AbstractMerger implements MergerInterface
{
    private ConfigMergerFactory $mergerFactory;

    public function __construct(ConfigMergerFactory $mergerFactory)
    {
        $this->mergerFactory = $mergerFactory;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        $merger = $this->mergerFactory->create(
            $oldContent,
            $this->params['idAttributes'] ?? [],
            $this->params['typeAttributeName'] ?? null
        );
        try {
            $merger->merge($newContent);
        } catch (Throwable $t) {
            throw new InvalidArgumentException('Root nodes cannot be different.');
        }
        $outXML = $merger->getDom()->saveXML();

        return $this->prettyPrint($outXML);
    }

    private function prettyPrint(string $xml): string
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        return str_replace('  ', "\t", $dom->saveXML());
    }
}
