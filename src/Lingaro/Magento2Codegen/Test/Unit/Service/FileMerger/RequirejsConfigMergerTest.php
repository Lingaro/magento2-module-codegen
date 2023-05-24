<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\FileMerger;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\FileMerger\JsonMerger;
use Orba\Magento2Codegen\Service\FileMerger\RequirejsConfigMerger;
use Orba\Magento2Codegen\Service\JsConverter;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Service\ArrayMerger;

class RequirejsConfigMergerTest extends TestCase
{
    private RequirejsConfigMerger $merger;

    public function setUp(): void
    {
        $this->merger = new RequirejsConfigMerger(new JsonMerger(new ArrayMerger()), new JsConverter());
    }

    /**
     * @dataProvider invalidContent
     */
    public function testMergeWillThrowExceptionIfMergedContentIsNotValid(string $oldContent, string $newContent): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->merger->merge($oldContent, $newContent);
    }

    public function testMergeWillReturnEmptyRequirejsConfigIfBothContentsAreEmpty(): void
    {
        $emptyContent = 'var config = {}';
        $result = $this->merger->merge($emptyContent, $emptyContent);
        $this->assertSame($emptyContent, $result);
    }

    public function testMergeWillReturnMergedRequirejsConfigIfBothContentsUsesDoubleQuotes(): void
    {
        $oldContent = <<<JS
var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module"
        }
    }
}
JS;
        $newContent = <<<JS
var config = {
    "map": {
        "*": {
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
}
JS;
        $expectedResult = <<<JS
var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module",
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
}
JS;
        $result = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $result);
    }

    public function testMergeWillReturnMergedRequirejsConfigIfBothContentsUsesSingleOrNoQuotes(): void
    {
        $oldContent = <<<JS
var config = {
    map: {
        '*': {
            someModule: 'Lingaro_Codegen/js/some_module'
        }
    }
}
JS;
        $newContent = <<<JS
var config = {
    map: {
        '*': {
            otherModule: 'Lingaro_Codegen/js/other_module'
        }
    }
}
JS;
        $expectedResult = <<<JS
var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module",
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
}
JS;
        $result = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $result);
    }

    public function testMergeWillReturnMergedRequirejsConfigIfBothContentsHaveSemicolonAtTheEnd(): void
    {
        $oldContent = <<<JS
var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module"
        }
    }
};
JS;
        $newContent = <<<JS
var config = {
    "map": {
        "*": {
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
};
JS;
        $expectedResult = <<<JS
var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module",
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
}
JS;
        $result = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $result);
    }

    public function testMergeWillReturnMergedRequirejsConfigIfBothContentsHaveCommentsAtTheBeginning(): void
    {
        $oldContent = <<<JS
/**
* Some comment
*/

var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module"
        }
    }
};
JS;
        $newContent = <<<JS
/**
* Other comment
*/

var config = {
    "map": {
        "*": {
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
};
JS;
        $expectedResult = <<<JS
var config = {
    "map": {
        "*": {
            "someModule": "Lingaro_Codegen/js/some_module",
            "otherModule": "Lingaro_Codegen/js/other_module"
        }
    }
}
JS;
        $result = $this->merger->merge($oldContent, $newContent);
        $this->assertSame($expectedResult, $result);
    }

    public function invalidContent(): array
    {
        return [
            ['invalid content', 'var config = {}'],
            ['var config = {}', 'invalid content']
        ];
    }
}
