<?php
/**
 * @copyright Copyright © 2020 Orba. All rights reserved.
 * @author    info@orba.co
 */

use \Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::THEME,
    'frontend/{{ vendorName|pascal }}/{{ themeName|snake }}',
    __DIR__
);
