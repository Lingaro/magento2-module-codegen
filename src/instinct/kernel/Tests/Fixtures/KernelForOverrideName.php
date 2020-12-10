<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instinct\Component\Kernel\Tests\Fixtures;

use Symfony\Component\Config\Loader\LoaderInterface;
use Instinct\Component\Kernel\Kernel;

class KernelForOverrideName extends Kernel
{
    protected $name = 'overridden';

    public function registerBundles()
    {
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
