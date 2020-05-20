<?php

namespace Orba\Magento2Codegen;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass($this->createCollectingCompilerPass());
    }

    private function createCollectingCompilerPass(): CompilerPassInterface
    {
        return new class implements CompilerPassInterface
        {
            public function process(ContainerBuilder $containerBuilder)
            {
                $applicationDefinition = $containerBuilder->findDefinition(Application::class);
                foreach ($containerBuilder->getDefinitions() as $definition) {
                    if (! is_a($definition->getClass(), Command::class, true)) {
                        continue;
                    }
                    $applicationDefinition->addMethodCall('add', [new Reference($definition->getClass())]);
                }
            }
        };
    }
}
