<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function is_a;

final class Kernel extends \Instinct\Component\Kernel\Kernel
{
    public function registerBundles(): array
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(dirname(__DIR__, 3) . '/config/services.yml');
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass($this->createCollectingCompilerPass());
    }

    private function createCollectingCompilerPass(): CompilerPassInterface
    {
        return new class implements CompilerPassInterface
        {
            public function process(ContainerBuilder $container)
            {
                $applicationDefinition = $container->findDefinition(Application::class);
                foreach ($container->getDefinitions() as $definition) {
                    if (! is_a($definition->getClass(), Command::class, true)) {
                        continue;
                    }
                    $applicationDefinition->addMethodCall('add', [new Reference($definition->getClass())]);
                }
            }
        };
    }
}
