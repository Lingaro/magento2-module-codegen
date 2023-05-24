<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit;

use function define;
use function defined;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        if (!defined('BP')) {
            define('BP', __DIR__ . '/_files');
        }
    }
}
