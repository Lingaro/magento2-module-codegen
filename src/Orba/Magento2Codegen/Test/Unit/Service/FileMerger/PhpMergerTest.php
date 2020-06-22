<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\FileMerger;

use Exception;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver\NodeChainNameResolver;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\ClassMethodNodeCleaner;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Order;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RelationFactory;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;
use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\FinderFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

/**
 * Class PhpMergerTest
 * @package Orba\Magento2Codegen\Test\Unit\Service\FileMerger
 */
class PhpMergerTest extends TestCase
{
    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    /**
     * @var PhpMerger
     */
    private $phpMerger;

    public function setUp(): void
    {
        $this->filepathUtil = new FilepathUtil(new FinderFactory());
        $classMethodCleaner = new ClassMethodNodeCleaner(new NodeChainNameResolver());
        $nodeCleaner = new NodeCleaner($classMethodCleaner);
        $rootFactory = new RootFactory(new RelationFactory(), new Order(), $nodeCleaner);
        $nodeTree = new NodeTree($rootFactory);
        $this->phpMerger = new PhpMerger($nodeTree);
    }

    public function testMergeThrowExceptionIfAnyLacksNamespace(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeThrowExceptionIfAnyLackNamespace/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeThrowExceptionIfAnyLackNamespace/newContent.php');
        $this->expectException(Exception::class);
        $this->phpMerger->merge($oldContent, $newContent);
    }

    public function testMergeThrowExceptionForTwoDifferentNamespace(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeThrowExceptionForDifferentNamespace/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeThrowExceptionForDifferentNamespace/newContent.php');
        $this->expectException(Exception::class);
        $this->phpMerger->merge($oldContent, $newContent);
    }

    public function testMergeThrowExceptionForNotSimilarClasses(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeThrowExceptionIfClassNotSimilar/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeThrowExceptionIfClassNotSimilar/newContent.php');
        $this->expectException(Exception::class);
        $this->phpMerger->merge($oldContent, $newContent);
    }

    public function testMergeOnePerLineUseImport(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeOnePerLineUseImport/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeOnePerLineUseImport/newContent.php');
        $expected = $this->filepathUtil->getContent(BP . '/php/mergeOnePerLineUseImport/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame($expected, $result);
    }

    public function testMergeNewContentAddMethodAndToConstructorOnce(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/newContent.php');
        $expected = $this->filepathUtil->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame($expected, $result);
    }

    public function testMergeNewContentAddMethodAndToConstructorMultiple(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/result.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/newContent.php');
        $expected = $this->filepathUtil->getContent(BP . '/php/mergeNewContentAddMethodAndConstructor/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame($expected, $result);
    }

    public function testMergeClassImplementsAndExtends(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeClassImplementsAndExtends/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeClassImplementsAndExtends/newContent.php');
        $expected = $this->filepathUtil->getContent(BP . '/php/mergeClassImplementsAndExtends/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame($expected, $result);
    }

    public function testMergeComplexCase(): void
    {
        $oldContent = $this->filepathUtil->getContent(BP . '/php/mergeComplexCase/oldContent.php');
        $newContent = $this->filepathUtil->getContent(BP . '/php/mergeComplexCase/newContent.php');
        $expected = $this->filepathUtil->getContent(BP . '/php/mergeComplexCase/result.php');
        $result = $this->phpMerger->merge($oldContent, $newContent);
        $this->assertSame($expected, $result);
    }
}