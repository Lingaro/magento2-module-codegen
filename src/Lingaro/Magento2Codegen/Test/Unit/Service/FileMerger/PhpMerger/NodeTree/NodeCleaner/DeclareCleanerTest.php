<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\DeclareCleaner;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver\NodeChainNameResolver;
use PhpParser\Node\Stmt\Declare_;
use PHPUnit\Framework\MockObject\MockObject;

class DeclareCleanerTest extends TestCase
{
    private DeclareCleaner $declareCleaner;
    private Declare_ $declare;

    /**
     * @var MockObject|NodeChainNameResolver
     */
    private $nodeChainNameResolver;

    public function setUp(): void
    {
        $this->nodeChainNameResolver = $this->createPartialMock(
            NodeChainNameResolver::class,
            ['resolve']
        );
        $this->declare = new Declare_([]);
        $this->declareCleaner = new DeclareCleaner($this->nodeChainNameResolver);
    }

    public function testCleanTwoSimilarDeclaresResultInOneItem(): void
    {
        $nodes = [$this->declare, $this->declare];
        $expectedResult = ['foo' => $this->declare];

        $this->nodeChainNameResolver->expects($this->any())
            ->method('resolve')
            ->will($this->onConsecutiveCalls('foo', 'foo'));

        $actualResult = $this->declareCleaner->clean($nodes);
        $this->assertSame($expectedResult, $actualResult);
    }

    public function testCleanTwoDifferentDeclaresResultInTwoItems(): void
    {
        $nodes = [$this->declare, $this->declare];
        $expectedResult = ['foo' => $this->declare, 'bar' => $this->declare];

        $this->nodeChainNameResolver->expects($this->any())
            ->method('resolve')
            ->will($this->onConsecutiveCalls('foo', 'bar'));

        $actualResult = $this->declareCleaner->clean($nodes);
        $this->assertSame($expectedResult, $actualResult);
    }
}
