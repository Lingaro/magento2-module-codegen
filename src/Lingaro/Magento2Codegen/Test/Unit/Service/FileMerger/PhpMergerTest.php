<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\FileMerger;

use Exception;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTreeFactory;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver\NodeChainNameResolver;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\ClassMethodNodeCleaner;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\DeclareCleaner;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Order;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RelationFactory;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;
use Lingaro\Magento2Codegen\Service\FilepathUtil;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

use function trim;

class PhpMergerTest extends TestCase
{
    private FilepathUtil $filepathUtil;
    private PhpMerger $phpMerger;

    public function setUp(): void
    {
        $this->filepathUtil = new FilepathUtil();
        $classMethodCleaner = new ClassMethodNodeCleaner(new NodeChainNameResolver());
        $declareCleaner = new DeclareCleaner(new NodeChainNameResolver());
        $nodeCleaner = new NodeCleaner($classMethodCleaner, $declareCleaner);
        $rootFactory = new RootFactory(new RelationFactory(), new Order(), $nodeCleaner);
        $nodeTreeFactory = new NodeTreeFactory($rootFactory);
        $this->phpMerger = new PhpMerger($nodeTreeFactory);
    }

    public function testMergeThrowExceptionIfAnyLacksNamespace(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeThrowExceptionIfAnyLackNamespace/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeThrowExceptionIfAnyLackNamespace/newContent.php');
        $this->expectException(Exception::class);
        $this->phpMerger->merge($oldContent, $newContent);
    }

    public function testMergeThrowExceptionForTwoDifferentNamespace(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeThrowExceptionForDifferentNamespace/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeThrowExceptionForDifferentNamespace/newContent.php');
        $this->expectException(Exception::class);
        $this->phpMerger->merge($oldContent, $newContent);
    }

    public function testMergeThrowExceptionForNotSimilarClasses(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeThrowExceptionIfClassNotSimilar/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeThrowExceptionIfClassNotSimilar/newContent.php');
        $this->expectException(Exception::class);
        $this->phpMerger->merge($oldContent, $newContent);
    }

    public function testMergeOnePerLineUseImport(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeOnePerLineUseImport/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeOnePerLineUseImport/newContent.php');
        $expected = $this->filepathUtil
            ->getContent(BP . '/php/mergeOnePerLineUseImport/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame(trim($expected), trim($result));
    }

    public function testMergeNewContentAddMethodAndToConstructorOnce(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/newContent.php');
        $expected = $this->filepathUtil
            ->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame(trim($expected), trim($result));
    }

    public function testMergeNewContentAddMethodAndToConstructorMultiple(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/result.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/newContent.php');
        $expected = $this->filepathUtil
            ->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame(trim($expected), trim($result));
    }

    public function testMergeClassImplementsAndExtends(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeClassImplementsAndExtends/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeClassImplementsAndExtends/newContent.php');
        $expected = $this->filepathUtil
            ->getContent(BP . '/php/mergeClassImplementsAndExtends/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame(trim($expected), trim($result));
    }

    public function testMergeMethodWithSimilarObjectFunctionCalls(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeMethodWithSimilarObjectFunctionCalls/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeMethodWithSimilarObjectFunctionCalls/newContent.php');
        $expected = $this->filepathUtil
            ->getContent(BP . '/php/mergeMethodWithSimilarObjectFunctionCalls/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame(trim($expected), trim($result));
    }

    public function testMergeComplexCase(): void
    {
        $oldContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeComplexCase/oldContent.php');
        $newContent = $this->filepathUtil
            ->getContent(BP . '/php/mergeComplexCase/newContent.php');
        $expected = $this->filepathUtil
            ->getContent(BP . '/php/mergeComplexCase/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame(trim($expected), trim($result));
    }
}
