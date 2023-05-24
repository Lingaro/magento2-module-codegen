<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\FileMerger;

use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Service\FileMerger\ConfigPhpMerger;
use Orba\Magento2Codegen\Service\FileMerger\Formatter\ConfigPhpFormatter;
use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\ArrayMerger;
use InvalidArgumentException;

class ConfigPhpMergerTest extends TestCase
{
    private FilepathUtil $filepathUtil;
    private ConfigPhpMerger $merger;

    public function setUp(): void
    {
        $this->filepathUtil = new FilepathUtil();
        $this->merger = new ConfigPhpMerger(new ConfigPhpFormatter(), new ArrayMerger());
    }

    /**
     * @dataProvider invalidContent
     */
    public function testMergeExceptionMergedContentIsNotValid(string $oldContent, string $newContent): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->merger->merge($oldContent, $newContent);
    }

    /**
     * @dataProvider emptyContent
     */
    public function testMergeExceptionMergedContentIsEmpty(string $oldContent, string $newContent): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->merger->merge($oldContent, $newContent);
    }

    public function testMergeSuccessDistinctValueAfterMerge(): void
    {
        $oldContent = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessDistinctValueAfterMerge/oldContent.php'
        );
        $newContent = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessDistinctValueAfterMerge/newContent.php'
        );
        $expectedResult = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessDistinctValueAfterMerge/expectedResult.php'
        );

        $actualResult = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $actualResult);
    }

    public function testMergeSuccessWithBracketsInConfigValues(): void
    {
        $oldContent = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessWithBracketsInConfigValues/oldContent.php'
        );
        $newContent = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessWithBracketsInConfigValues/newContent.php'
        );
        $expectedResult = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessWithBracketsInConfigValues/expectedResult.php'
        );

        $actualResult = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $actualResult);
    }

    public function testMergeSuccessMergedArrayWithoutKeys(): void
    {
        $oldContent = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessMergedArrayWithoutKeys/oldContent.php'
        );
        $newContent = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessMergedArrayWithoutKeys/newContent.php'
        );
        $expectedResult = $this->filepathUtil->getContent(
            BP . '/configPhp/testMergeSuccessMergedArrayWithoutKeys/expectedResult.php'
        );

        $actualResult = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $actualResult);
    }

    public function invalidContent(): array
    {
        return [
            ['simple text', '<?php return [] ?>'],
            ['<?php return [] ?>', 'simple text'],
            ['<?php return [] ?>', '<?php return 1 ?>'],
            ['<?php return 1 ?>', '<?php return [] ?>'],
            ['<?php $a = 1 ?>', '<?php return [] ?>'],
            ['<?php return [] ?>', '<?php $a = 1 ?>'],
        ];
    }

    public function emptyContent(): array
    {
        return [
            ['', '<?php return [] ?>'],
            ['<?php return [] ?>', ''],
        ];
    }
}
