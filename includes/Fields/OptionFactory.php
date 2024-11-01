<?php

namespace Shoplic\NaverMap\Fields;

class OptionFactory
{
    /** @var array<string, Option> */
    protected static $store = [];

    public static function get(string $optionName, bool $autoload): Option
    {
        if ( ! isset(static::$store[$optionName])) {
            static::$store[$optionName] = new Option($optionName, $autoload);
        }

        return static::$store[$optionName];
    }
}
