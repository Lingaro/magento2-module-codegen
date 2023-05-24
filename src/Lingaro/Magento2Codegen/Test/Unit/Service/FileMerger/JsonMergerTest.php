<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\FileMerger;

use Exception;
use Orba\Magento2Codegen\Service\FileMerger\JsonMerger;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Service\ArrayMerger;
use UnexpectedValueException;

use function array_keys;
use function array_merge;
use function in_array;
use function json_encode;
use function json_last_error;

class JsonMergerTest extends TestCase
{
    private JsonMerger $jsonMerger;

    public function setUp(): void
    {
        $this->jsonMerger = new JsonMerger(new ArrayMerger());
    }

    public function testMergeThrowExceptionIfOneStringInvalidJson(): void
    {
        $oldContent = 'invalid_json';
        $newContent = json_encode([
            'valid' => 'json'
        ]);
        $this->expectException(Exception::class);
        $this->jsonMerger->merge($oldContent, $newContent);
    }

    public function testMergeTwoValidJsonWithDataFromBothResult(): void
    {
        $oldArray = [
            'a0' => ['aa0'],
            'a1' => 'aa0',
            'a2' => ['aa0', 'aa1'],
            'a3' => 'aa0',
        ];
        $newArray = [
            'b0' => 'bb0',
            'b1' => 'bb1',
            'a2' => ['aa0', 'bb0', 'bb1'],
            'a3' => 'aa0',
        ];
        $this->getMergeResultAndAssertIntegrity($oldArray, $newArray);
    }

    public function testMergeTwoArraysIntoOne(): void
    {
        $oldArray = [
            'a0' => ['aa0', 'aa1'],
        ];
        $newArray = [
            'a0' => ['aa0', 'bb0', 'bb1']
        ];
        $resultArray = $this->getMergeResultAndAssertIntegrity($oldArray, $newArray);
        $this->assertArrayHasKey('a0', $resultArray);
        $this->assertCount(4, $resultArray['a0']);
        $this->assertEquals(['aa0', 'aa1', 'bb0', 'bb1'], $resultArray['a0']);
    }

    public function testMergeTwoIdenticalElementsWithNoChanges(): void
    {
        $oldArray = [
            'a0' => 'aa0'
        ];
        $newArray = [
            'a0' => 'aa0'
        ];
        $resultArray = $this->getMergeResultAndAssertIntegrity($oldArray, $newArray);
        $this->assertArrayHasKey('a0', $resultArray);
        $this->assertIsNotArray($resultArray['a0']);
        $this->assertEquals('aa0', $resultArray['a0']);
    }

    public function testMergeTwoElementsWithValueReplaced(): void
    {
        $oldArray = [
            'a0' => 'aa0',
        ];
        $newArray = [
            'a0' => 'aa1',
        ];
        $resultArray = $this->getMergeResultAndAssertIntegrity($oldArray, $newArray);
        $this->assertArrayHasKey('a0', $resultArray);
        $this->assertIsNotArray($resultArray['a0']);
        $this->assertEquals('aa1', $resultArray['a0']);
    }

    public function testMergeStringMovedToArrayThrowException(): void
    {
        $oldArray = [
            'a0' => 'aa0',
        ];
        $newArray = [
            'a0' => ['aa1'],
        ];
        $this->expectException(UnexpectedValueException::class);
        $this->jsonMerger->merge(json_encode($oldArray), json_encode($newArray));
    }

    public function testMergeTwoArraysOfCustomKeysAndArrays(): void
    {
        $oldArray = [
            'a0' => ['k1' => ['aa0', 'aa1'], 'k2' => ['aa0', 'aa1']]
        ];
        $newArray = [
            'a0' => ['k3' => ['aa0', 'aa1'], 'k1' => ['ax0'], 'k4' => 'bx0']
        ];
        $resultArray = $this->getMergeResultAndAssertIntegrity($oldArray, $newArray);
        $this->assertArrayHasKey('a0', $resultArray);
        $this->assertIsArray($resultArray['a0']);
        $this->assertEquals(['k1', 'k2', 'k3', 'k4'], array_keys($resultArray['a0']));
        $this->assertCount(3, $resultArray['a0']['k1']);
        $this->assertTrue(in_array('ax0', $resultArray['a0']['k1']));
        $this->assertIsNotArray($resultArray['a0']['k4']);
        $this->assertEquals('bx0', $resultArray['a0']['k4']);
    }

    private function getMergeResultAndAssertIntegrity(array $oldArray, array $newArray): array
    {
        $resultJson = $this->jsonMerger->merge(json_encode($oldArray), json_encode($newArray));
        $resultArray = json_decode($resultJson, true);
        $this->assertTrue(json_last_error() === JSON_ERROR_NONE);
        $this->assertIsArray($resultArray);
        $entryKeys = array_keys(array_merge($oldArray, $newArray));
        $resultKeys = array_keys($resultArray);
        $this->assertEquals($entryKeys, $resultKeys);
        return $resultArray;
    }
}
