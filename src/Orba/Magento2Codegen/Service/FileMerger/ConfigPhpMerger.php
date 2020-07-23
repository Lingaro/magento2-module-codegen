<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use InvalidArgumentException;
use UnexpectedValueException;
use Orba\Magento2Codegen\Service\FileMerger\Formatter\ConfigPhpFormatter;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;

/**
 * Class ConfigPhpMerger
 * @package Orba\Magento2Codegen\Service\FileMerger
 */
class ConfigPhpMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @var ConfigPhpFormatter
     */
    private $phpFormatter;

    /**
     * @var Parser
     */
    private $phpParser;

    /**
     * Index used for keys in array in case if array item
     *
     * @var int
     */
    private $noKeyIndex = 0;

    /**
     * @param ConfigPhpFormatter $phpFormatter
     */
    public function __construct(
        ConfigPhpFormatter $phpFormatter
    ) {
        $this->phpFormatter = $phpFormatter;
        $this->phpParser    = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    /**
     * Merge app/etc/config.php file content with new configurations
     *
     * @param string $oldContent
     * @param string $newContent
     * @return string
     * @throws InvalidArgumentException
     */
    public function merge(string $oldContent, string $newContent): string
    {
        $parsedOldContent = current($this->phpParser->parse($oldContent));
        $parsedOldContentArray = $this->convertExpressionToArray($this->getConfigContentExpression($parsedOldContent));

        $parsedNewContent = current($this->phpParser->parse($newContent));
        $parsedNewContentArray = $this->convertExpressionToArray($this->getConfigContentExpression($parsedNewContent));

        $content = $this->arrayMergeRecursiveDistinct($parsedOldContentArray, $parsedNewContentArray);
        return $this->phpFormatter->format($content);
    }

    /**
     * Get base array node from parsed config.php object
     *
     * @param object $content
     * @return false|string
     */
    private function getConfigContentExpression($content)
    {
        if (!($content instanceof Return_) || !($content->expr instanceof Array_)) {
            throw new InvalidArgumentException('Merged string is not proper config.php file content');
        }

        return $content->expr;
    }

    /**
     * Convert object returned by php parser to php array recursively
     *
     * @param Array_ $arrayExpr
     * @return array
     */
    private function convertExpressionToArray(Array_ $arrayExpr): array
    {
        $result = [];

        foreach ($arrayExpr->items as $item) {
            if ($item instanceof ArrayItem) {
                $key = $item->key ? $item->key->value : $this->noKeyIndex++;

                if ($item->value instanceof Array_) {
                    $result[$key] = $this->convertExpressionToArray($item->value);
                } else {
                    $result[$key] = isset($item->value->value) ? $item->value->value : null;
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function arrayMergeRecursiveDistinct(): array
    {
        $arrays = func_get_args();
        $base = array_shift($arrays);
        $canMergeBase = $canMergeAppend = true;
        if (!is_array($base)) {
            $base = empty($base) ? array() : array($base);
            $canMergeBase = false;
        }
        foreach ($arrays as $append) {
            if (!is_array($append)) {
                $append = array($append);
                $canMergeAppend = false;
            }
            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) && !is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (isset($base[$key]) && (is_array($value) || is_array($base[$key]))) {
                    $base[$key] = $this->arrayMergeRecursiveDistinct($base[$key], $append[$key]);
                } else {
                    if (!$canMergeBase || !$canMergeAppend) {
                        throw new UnexpectedValueException('Can\'t merge non array into array');
                    }
                    elseif (is_numeric($key)) {
                        if (!in_array($value, $base)) {
                            $base[] = $value;
                        }
                    } else {
                        $base[$key] = $value;
                    }
                }
            }
        }
        return $base;
    }
}
