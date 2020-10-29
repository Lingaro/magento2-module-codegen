<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instinct\Component\Kernel\Tests\Bundle;

use PHPUnit\Framework\TestCase;
use Instinct\Component\Kernel\Bundle\Bundle;
use Instinct\Component\Kernel\Tests\Fixtures\ExtensionNotValidBundle\ExtensionNotValidBundle;
use Instinct\Component\Kernel\Tests\Fixtures\ExtensionPresentBundle\ExtensionPresentBundle;

class BundleTest extends TestCase
{
    public function testGetContainerExtension()
    {
        $bundle = new ExtensionPresentBundle();

        $this->assertInstanceOf(
            'Instinct\Component\Kernel\Tests\Fixtures\ExtensionPresentBundle\DependencyInjection\ExtensionPresentExtension',
            $bundle->getContainerExtension()
        );
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage must implement Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function testGetContainerExtensionWithInvalidClass()
    {
        $bundle = new ExtensionNotValidBundle();
        $bundle->getContainerExtension();
    }

    public function testBundleNameIsGuessedFromClass()
    {
        $bundle = new GuessedNameBundle();

        $this->assertSame('Instinct\Component\Kernel\Tests\Bundle', $bundle->getNamespace());
        $this->assertSame('GuessedNameBundle', $bundle->getName());
    }

    public function testBundleNameCanBeExplicitlyProvided()
    {
        $bundle = new NamedBundle();

        $this->assertSame('ExplicitlyNamedBundle', $bundle->getName());
        $this->assertSame('Instinct\Component\Kernel\Tests\Bundle', $bundle->getNamespace());
        $this->assertSame('ExplicitlyNamedBundle', $bundle->getName());
    }
}

class NamedBundle extends Bundle
{
    public function __construct()
    {
        $this->name = 'ExplicitlyNamedBundle';
    }
}

class GuessedNameBundle extends Bundle
{
}
