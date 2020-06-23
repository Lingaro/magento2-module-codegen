<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\FileMerger;

use Orba\Magento2Codegen\Service\FileMerger\JsonMerger;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class JsonMergerTest extends TestCase
{
    /**
     * @var JsonMerger
     */
    private $jsonMerger;

    public function setUp(): void
    {
        $this->jsonMerger = new JsonMerger();
    }

    public function testMergeThrowExceptionIfOneStringInvalidJson(): void
    {
        $oldContent = 'invalid_json';
        $newContent = json_encode([
            'valid' => 'json'
        ]);
        $this->expectException(\Exception::class);
        $this->jsonMerger->merge($oldContent, $newContent);
    }

    public function testMergeTwoValidJsonWithDataFromBothResult(): void
    {
        $oldArray = [
            'a0' => ['aa0'],
            'a1' => 'aa0',
            'a2' => ['aa0', 'aa1'],
            'a3' => 'aa0',
            'a4' => 'aa0',
            'a5' => 'aa0',
            'a6' => ['k1' => ['aa0', 'aa1'], 'k2' => ['aa0', 'aa1']]
        ];
        $newArray = [
            'b0' => 'bb0',
            'b1' => 'bb1',
            'a2' => ['aa0', 'bb0', 'bb1'],
            'a3' => 'aa0',
            'a4' => 'aa1',
            'a5' => ['aa1'],
            'a6' => ['k3' => ['aa0', 'aa1'], 'k1' => ['ax0'], 'k4' => 'bx0']
        ];
        $resultJson = $this->jsonMerger->merge(json_encode($oldArray), json_encode($newArray));
        $resultArray = json_decode($resultJson, true);
        // assert general array integrity
        $this->assertTrue(json_last_error() === JSON_ERROR_NONE);
        $this->assertIsArray($resultArray);
        $entryKeys = array_keys(array_merge($oldArray, $newArray));
        $resultKeys = array_keys($resultArray);
        $this->assertEquals(
            $entryKeys,
            $resultKeys
        );
        // assert arrays merged into one
        $this->assertArrayHasKey('a2', $resultArray);
        $this->assertCount(4, $resultArray['a2']);
        $this->assertEquals(['aa0', 'aa1', 'bb0', 'bb1'], $resultArray['a2']);
        // assert identical not merged
        $this->assertArrayHasKey('a3', $resultArray);
        $this->assertIsNotArray($resultArray['a3']);
        $this->assertEquals('aa0', $resultArray['a3']);
        // assert value replaced
        $this->assertArrayHasKey('a4', $resultArray);
        $this->assertIsNotArray($resultArray['a4']);
        $this->assertEquals('aa1', $resultArray['a4']);
        // assert string moved to array
        $this->assertArrayHasKey('a5', $resultArray);
        $this->assertIsArray($resultArray['a5']);
        $this->assertEquals(['aa0', 'aa1'], $resultArray['a5']);
        // assert custom key array of arrays merge
        $this->assertArrayHasKey('a6', $resultArray);
        $this->assertIsArray($resultArray['a6']);
        $this->assertEquals(['k1', 'k2', 'k3', 'k4'], array_keys($resultArray['a6']));
        $this->assertCount(3, $resultArray['a6']['k1']);
        $this->assertTrue(in_array('ax0', $resultArray['a6']['k1']), true);
        $this->assertIsNotArray($resultArray['a6']['k4']);
        $this->assertEquals('bx0', $resultArray['a6']['k4']);
    }
}