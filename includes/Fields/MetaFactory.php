<?php

namespace Shoplic\NaverMap\Fields;

class MetaFactory
{
    /** @var array<string, Meta> */
    protected static $store = [];

    public static function get(string $metaKey, string $objectType = 'post', bool $single = true): Meta
    {
        $key = "$objectType:$metaKey";

        if ( ! isset(static::$store[$key])) {
            static::$store[$key] = new Meta($metaKey, $objectType, $single);
        }

        return static::$store[$key];
    }
}
