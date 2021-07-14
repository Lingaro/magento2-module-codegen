<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

/**
 * \Exception that should be thrown by DOM model when incoming xml is not valid.
 */
namespace Orba\Magento2Codegen\Util\Magento\Config\Dom;

use InvalidArgumentException;

/**
 * @api
 * @since 100.0.2
 */
class ValidationException extends InvalidArgumentException
{
}
