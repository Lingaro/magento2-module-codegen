<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

/**
 * \Exception that should be thrown by DOM model when incoming xml is not valid.
 */
namespace Lingaro\Magento2Codegen\Util\Magento\Config\Dom;

use InvalidArgumentException;

/**
 * @api
 * @since 100.0.2
 */
class ValidationException extends InvalidArgumentException
{
}
