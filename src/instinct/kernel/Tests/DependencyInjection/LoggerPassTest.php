<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instinct\Component\Kernel\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Instinct\Component\Kernel\DependencyInjection\LoggerPass;
use Instinct\Component\Kernel\Log\Logger;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class LoggerPassTest extends TestCase
{
    public function testAlwaysSetAutowiringAlias()
    {
        $container = new ContainerBuilder();
        $container->register('logger', 'Foo');

        (new LoggerPass())->process($container);

        $this->assertFalse($container->getAlias(LoggerInterface::class)->isPublic());
    }

    public function testDoNotOverrideExistingLogger()
    {
        $container = new ContainerBuilder();
        $container->register('logger', 'Foo');

        (new LoggerPass())->process($container);

        $this->assertSame('Foo', $container->getDefinition('logger')->getClass());
    }

    public function testRegisterLogger()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);

        (new LoggerPass())->process($container);

        $definition = $container->getDefinition('logger');
        $this->assertSame(Logger::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
    }
}
