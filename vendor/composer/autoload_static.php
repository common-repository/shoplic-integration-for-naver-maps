<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0c7711aa2bc7670d863721a51187c65d
{
    public static $files = array (
        'd303627d9b41aafd1c4c9e2ab008a778' => __DIR__ . '/../..' . '/includes/Functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Shoplic\\NaverMap\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Shoplic\\NaverMap\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Shoplic\\NaverMap\\ArrayWraps\\ArrayWrap' => __DIR__ . '/../..' . '/includes/ArrayWraps/ArrayWrap.php',
        'Shoplic\\NaverMap\\ArrayWraps\\MapAttsWrap' => __DIR__ . '/../..' . '/includes/ArrayWraps/MapAttsWrap.php',
        'Shoplic\\NaverMap\\ArrayWraps\\SettingsWrap' => __DIR__ . '/../..' . '/includes/ArrayWraps/SettingsWrap.php',
        'Shoplic\\NaverMap\\Container' => __DIR__ . '/../..' . '/includes/Container.php',
        'Shoplic\\NaverMap\\Fields\\FieldRenderer' => __DIR__ . '/../..' . '/includes/Fields/FieldRenderer.php',
        'Shoplic\\NaverMap\\Fields\\Meta' => __DIR__ . '/../..' . '/includes/Fields/Meta.php',
        'Shoplic\\NaverMap\\Fields\\MetaFactory' => __DIR__ . '/../..' . '/includes/Fields/MetaFactory.php',
        'Shoplic\\NaverMap\\Fields\\Option' => __DIR__ . '/../..' . '/includes/Fields/Option.php',
        'Shoplic\\NaverMap\\Fields\\OptionFactory' => __DIR__ . '/../..' . '/includes/Fields/OptionFactory.php',
        'Shoplic\\NaverMap\\Modules\\AdminListTableFields' => __DIR__ . '/../..' . '/includes/Modules/AdminListTableFields.php',
        'Shoplic\\NaverMap\\Modules\\AdminSettings' => __DIR__ . '/../..' . '/includes/Modules/AdminSettings.php',
        'Shoplic\\NaverMap\\Modules\\ContentFilter' => __DIR__ . '/../..' . '/includes/Modules/ContentFilter.php',
        'Shoplic\\NaverMap\\Modules\\CustomFieldGroups' => __DIR__ . '/../..' . '/includes/Modules/CustomFieldGroups.php',
        'Shoplic\\NaverMap\\Modules\\CustomFields' => __DIR__ . '/../..' . '/includes/Modules/CustomFields.php',
        'Shoplic\\NaverMap\\Modules\\CustomPosts' => __DIR__ . '/../..' . '/includes/Modules/CustomPosts.php',
        'Shoplic\\NaverMap\\Modules\\CustomTaxonomies' => __DIR__ . '/../..' . '/includes/Modules/CustomTaxonomies.php',
        'Shoplic\\NaverMap\\Modules\\Edit' => __DIR__ . '/../..' . '/includes/Modules/Edit.php',
        'Shoplic\\NaverMap\\Modules\\L10n' => __DIR__ . '/../..' . '/includes/Modules/L10n.php',
        'Shoplic\\NaverMap\\Modules\\Module' => __DIR__ . '/../..' . '/includes/Modules/Module.php',
        'Shoplic\\NaverMap\\Modules\\ModuleException' => __DIR__ . '/../..' . '/includes/Modules/ModuleException.php',
        'Shoplic\\NaverMap\\Modules\\ScriptLoader' => __DIR__ . '/../..' . '/includes/Modules/ScriptLoader.php',
        'Shoplic\\NaverMap\\Modules\\Settings' => __DIR__ . '/../..' . '/includes/Modules/Settings.php',
        'Shoplic\\NaverMap\\Modules\\Shortcodes' => __DIR__ . '/../..' . '/includes/Modules/Shortcodes.php',
        'Shoplic\\NaverMap\\Supports\\LocalizeDataSupport' => __DIR__ . '/../..' . '/includes/Supports/LocalizeDataSupport.php',
        'Shoplic\\NaverMap\\Supports\\MapSupport' => __DIR__ . '/../..' . '/includes/Supports/MapSupport.php',
        'Shoplic\\NaverMap\\Supports\\Support' => __DIR__ . '/../..' . '/includes/Supports/Support.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0c7711aa2bc7670d863721a51187c65d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0c7711aa2bc7670d863721a51187c65d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0c7711aa2bc7670d863721a51187c65d::$classMap;

        }, null, ClassLoader::class);
    }
}
