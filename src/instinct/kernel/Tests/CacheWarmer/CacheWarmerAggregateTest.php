<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instinct\Component\Kernel\Tests\CacheWarmer;

use PHPUnit\Framework\TestCase;
use Instinct\Component\Kernel\CacheWarmer\CacheWarmerAggregate;

class CacheWarmerAggregateTest extends TestCase
{
    protected static $cacheDir;

    public static function setUpBeforeClass()
    {
        self::$cacheDir = tempnam(sys_get_temp_dir(), 'sf_cache_warmer_dir');
    }

    public static function tearDownAfterClass()
    {
        @unlink(self::$cacheDir);
    }

    public function testInjectWarmersUsingConstructor()
    {
        $warmer = $this->getCacheWarmerMock();
        $warmer
            ->expects($this->once())
            ->method('warmUp');
        $aggregate = new CacheWarmerAggregate([$warmer]);
        $aggregate->warmUp(self::$cacheDir);
    }

    public function testWarmupDoesCallWarmupOnOptionalWarmersWhenEnableOptionalWarmersIsEnabled()
    {
        $warmer = $this->getCacheWarmerMock();
        $warmer
            ->expects($this->never())
            ->method('isOptional');
        $warmer
            ->expects($this->once())
            ->method('warmUp');

        $aggregate = new CacheWarmerAggregate([$warmer]);
        $aggregate->enableOptionalWarmers();
        $aggregate->warmUp(self::$cacheDir);
    }

    public function testWarmupDoesNotCallWarmupOnOptionalWarmersWhenEnableOptionalWarmersIsNotEnabled()
    {
        $warmer = $this->getCacheWarmerMock();
        $warmer
            ->expects($this->once())
            ->method('isOptional')
            ->will($this->returnValue(true));
        $warmer
            ->expects($this->never())
            ->method('warmUp');

        $aggregate = new CacheWarmerAggregate([$warmer]);
        $aggregate->warmUp(self::$cacheDir);
    }

    protected function getCacheWarmerMock()
    {
        $warmer = $this->getMockBuilder('Instinct\Component\Kernel\CacheWarmer\CacheWarmerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        return $warmer;
    }
}
