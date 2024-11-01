<?php

use Shoplic\NaverMap\Container;

if ( ! defined('ABSPATH')) {
    exit;
}

return array(
    // Key: Module name.
    // Value: Closure or array.
    // Closure: fn(Container $containr, string $moduleName) => [ ... ]
    'ContentFilter' => function (Container $container) {
        $settings = $container->settings->wrap;

        return [
            $settings->getDisplayMethod(),   // method.
            $settings->getDisplayPosition(), // position.
            $settings->getDisplayPriority(), // priority.
        ];
    }
);
