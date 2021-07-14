<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit;

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
