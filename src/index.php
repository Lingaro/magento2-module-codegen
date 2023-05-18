<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

$composerAutoload = __DIR__ . '/../../../../vendor/autoload.php';

if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
} else {
    require_once __DIR__ . '/../vendor/autoload.php';
}

define('BP', dirname(__DIR__));

use Orba\Magento2Codegen\Application;
use Orba\Magento2Codegen\Kernel;

$kernel = new Kernel('dev', true);
$kernel->boot();

$container = $kernel->getContainer();
/** @var Application $application */
$application = $container->get(Application::class);
$application->run();
