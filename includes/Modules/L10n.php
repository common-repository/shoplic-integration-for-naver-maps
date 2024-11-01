<?php

namespace Shoplic\NaverMap\Modules;

class L10n implements Module
{
    public function __construct()
    {
        load_plugin_textdomain(
            'shoplic-integration-for-naver-map',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
        );
    }
}
