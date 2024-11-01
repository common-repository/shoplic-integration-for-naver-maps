<?php

namespace Shoplic\NaverMap\Modules;

use Shoplic\NaverMap\ArrayWraps\SettingsWrap;

use function Shoplic\NaverMap\getAllowedPostTypes;

/**
 * @property-read SettingsWrap $wrap
 */
final class Settings implements Module
{
    private $option_group = 'shoplic-integration-for-naver-map';

    /** @var SettingsWrap|null */
    private $sw = null;

    public function __construct()
    {
        $this->registerSettings();
    }

    /**
     * @throws ModuleException
     */
    public function __get(string $name)
    {
        if ('wrap' === $name) {
            return $this->getSettingsWrap();
        }

        throw new ModuleException("'$name' not found in settings module.");
    }

    public function getOptionGroup(): string
    {
        return $this->option_group;
    }

    private function registerSettings()
    {
        register_setting(
            $this->option_group,
            'nm_settings',
            [
                'type'              => 'object',
                'description'       => _x('Naver Map settings', 'Settings description', 'shoplic-integration-for-naver-map'),
                'sanitize_callback' => [self::class, 'sanitizeSettings'],
                'show_in_rest'      => false,
                'default'           => self::getDefaultSettings(),
            ]
        );
    }

    private function getSettingsWrap(): SettingsWrap
    {
        if (is_null($this->sw)) {
            $this->sw = new SettingsWrap('nm_settings', get_option('nm_settings'), self::getDefaultSettings());
        }

        return $this->sw;
    }

    public static function sanitizeSettings($value): array
    {
        $default   = self::getDefaultSettings();
        $sanitized = self::getDefaultSettings();

        // Sanitize 'client_id'.
        $sanitized['client_id'] = sanitize_text_field($value['client_id'] ?? $default['client_id']);

        // Sanitize 'develop'.
        $sanitized['development'] = filter_var(
            $value['development'] ?? $default['development'],
            FILTER_VALIDATE_BOOLEAN
        );

        // Sanitize 'display_method'.
        $displayMethod  = sanitize_key($value['display_method'] ?? $default['display_method']);
        $allowedMethods = ['manual', 'automatic'];
        if ( ! in_array($displayMethod, $allowedMethods, true)) {
            $displayMethod = 'manual';
        }
        $sanitized['display_method'] = $displayMethod;

        // Sanitize 'display_position'.
        $displayPosition  = sanitize_key($value['display_position'] ?? $default['display_position']);
        $allowedPositions = ['bottom', 'top'];
        if ( ! in_array($displayPosition, $allowedPositions, true)) {
            $displayPosition = 'bottom';
        }
        $sanitized['display_position'] = $displayPosition;

        // Sanitize 'display_priority'.
        $sanitized['display_priority'] = intval($value['display_priority'] ?? $default['display_priority']);

        // Sanitize 'linked_post_types'.
        if (isset($value['linked_post_types'])) {
            $value['linked_post_types'] = (array)$value['linked_post_types'];
            if ( ! wp_is_numeric_array($value['linked_post_types'])) {
                // Might be passed from POST request.
                $value['linked_post_types'] = array_keys(
                    array_filter(
                        $value['linked_post_types'],
                        function ($v) { return 'yes' === $v; }
                    )
                );
            }

            $allowedTypes                   = getAllowedPostTypes();
            $sanitized['linked_post_types'] = [];
            foreach ($value['linked_post_types'] as $postType) {
                if (in_array($postType, $allowedTypes, true)) {
                    $sanitized['linked_post_types'][] = $postType;
                }
            }

            if (empty($sanitized['linked_post_types'])) {
                $sanitized['linked_post_types'] = $default['linked_post_types'];
            }
        } else {
            $sanitized['linked_post_types'] = $default['linked_post_types'];
        }

        return $sanitized;
    }

    public static function getDefaultSettings(): array
    {
        return [
            'client_id'         => '',
            'development'       => false,
            'display_method'    => 'manual',
            'display_position'  => 'bottom',
            'display_priority'  => 10,
            'linked_post_types' => [],
        ];
    }
}
