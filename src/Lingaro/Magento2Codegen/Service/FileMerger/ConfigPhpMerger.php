<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\FileMerger\Formatter\ConfigPhpFormatter;
use Orba\Magento2Codegen\Service\ArrayMerger;
use PhpParser\Node\Stmt;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;

use function is_null;

class ConfigPhpMerger extends AbstractMerger implements MergerInterface
{
    private ConfigPhpFormatter $phpFormatter;
    private ArrayMerger $arrayMergeService;
    private Parser $phpParser;

    /**
     * Index used for keys in array in case if array item
     */
    private int $noKeyIndex = 0;

    public function __construct(ConfigPhpFormatter $phpFormatter, ArrayMerger $arrayMergeService)
    {
        $this->phpFormatter = $phpFormatter;
        $this->arrayMergeService = $arrayMergeService;
        $this->phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    /**
     * Merge app/etc/config.php file content with new configurations
     */
    public function merge(string $oldContent, string $newContent): string
    {
        $parsedOldContent = current($this->phpParser->parse($oldContent)) ?: null;
        $parsedOldContentArray = $this->convertExpressionToArray($this->getConfigContentExpression($parsedOldContent));

        $parsedNewContent = current($this->phpParser->parse($newContent)) ?: null;
        $parsedNewContentArray = $this->convertExpressionToArray($this->getConfigContentExpression($parsedNewContent));

        $content = $this->arrayMergeService
            ->arrayMergeRecursiveDistinct($parsedOldContentArray, $parsedNewContentArray);
        return $this->phpFormatter->format($content);
    }

    /**
     * Get base array node from parsed config.php object
     */
    private function getConfigContentExpression(?Stmt $content): Array_
    {
        if (is_null($content) || !($content instanceof Return_) || !($content->expr instanceof Array_)) {
            throw new InvalidArgumentException('Merged string is not proper config.php file content');
        }
        return $content->expr;
    }

    /**
     * Convert object returned by php parser to php array recursively
     */
    private function convertExpressionToArray(Array_ $arrayExpr): array
    {
        $result = [];

        foreach ($arrayExpr->items as $item) {
            if ($item instanceof ArrayItem) {
                $key = $item->key ? $item->key->value : $this->noKeyIndex++;
                if ($item->value instanceof Array_) {
                    $result[$key] = $this->convertExpressionToArray($item->value);
                    continue;
                }
                $result[$key] = $item->value->value ?? null;
            }
        }

        return $result;
    }
}
