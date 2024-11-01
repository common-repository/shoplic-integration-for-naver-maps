<?php

if ( ! defined('ABSPATH')) {
    exit;
}

return [
    // Hook.
    'plugins_loaded' => [
        // Priority.
        5 => [
            'L10n',
            'Settings',
        ],
    ],
    // Hook.
    'init'           => [
        // Priority.
        5 => [
            // 모듈만 나열 가능. 이러면 모듈의 첫글자를 소문자로 해서 이름지은 것과 동일함.
            'CustomPosts',
            'CustomTaxonomies',
        ],
        // Priority.
        6 => [
            'ContentFilter',
            'CustomFields',
            'CustomFieldGroups',
            'ScriptLoader',
            'Shortcodes',
        ],
    ],
    // Hook.
    'admin_init'     => [
        // Priority.
        5 => [
            'AdminListTableFields',
            'Edit',
        ],
    ],
    // Hook.
    'admin_menu'     => [
        // Priority.
        5 => [
            [
                'module' => 'AdminSettings',
                'name'   => '', // 비워두면 알아서 첫글자 소문자로 바꿈.
            ],
        ],
    ],
];
