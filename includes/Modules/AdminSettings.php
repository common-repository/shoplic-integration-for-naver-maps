<?php
/** @noinspection HtmlUnknownTarget */

namespace Shoplic\NaverMap\Modules;

use Shoplic\NaverMap\Fields\FieldRenderer;

use function Shoplic\NaverMap\getAllowedPostTypes;
use function Shoplic\NaverMap\isWpDevelopmentEnvironment;
use function Shoplic\NaverMap\render;

/**
 * 관리자 설정 관련 모듈
 */
final class AdminSettings implements Module
{
    public function __construct()
    {
        add_submenu_page(
            'edit.php?post_type=' . CustomPosts::PT_MAP,
            _x('Naver Map Settings', 'Options page title', 'shoplic-integration-for-naver-map'),
            _x('Settings', 'Options menu title', 'shoplic-integration-for-naver-map'),
            'manage_options',
            self::getPageSlug(),
            [$this, 'outputSettings']
        );

        add_action('admin_enqueue_scripts', [$this, 'actionEnqueueScripts']);
    }

    public function actionEnqueueScripts($hook)
    {
        if (get_plugin_page_hook(self::getPageSlug(), '') === $hook) {
            wp_enqueue_style(
                'nm-settings',
                plugins_url('assets/styles/settings.css', NM_MAIN),
                [],
                NM_VERSION
            );
        }
    }

    public function outputSettings()
    {
        $this->prepareSettings();

        render(
            'admin-settings',
            [
                'banner_horizontal' => plugins_url('assets/images/banner-001-h.png', NM_MAIN),
                'banner_url'        => 'https://shoplic.kr',
                'banner_vertical'   => plugins_url('assets/images/banner-001-v.png', NM_MAIN),
                'option_group'      => nm()->settings->getOptionGroup(),
                'page'              => self::getPageSlug(),
            ]
        );
    }

    private function prepareSettings(): void
    {
        $settings   = nm()->settings->wrap;
        $theSection = 'nm-general';
        $theName    = $settings->getOptionName();

        add_settings_section(
            $theSection,
            __('General', 'shoplic-integration-for-naver-map'),
            '__return_empty_string',
            self::getPageSlug()
        );


        add_settings_field(
            "$theSection-client_id",
            __('Client ID', 'shoplic-integration-for-naver-map'),
            [FieldRenderer::class, 'input'],
            self::getPageSlug(),
            $theSection,
            [
                'attrs'       => [
                    'autocomplete' => 'off',
                    'id'           => "$theName-client_id",
                    'class'        => 'text large-text',
                    'name'         => "{$theName}[client_id]",
                    'value'        => $settings->getClientId(),
                ],
                'description' => sprintf(
                // Translators: 링크 주소.
                    __(
                        'You can get it from <a href="%1$s" target="_blank" rel="noreferrer">Naver Cloud console</a>. <a href="%2$s" target="_blank" rel="noreferrer">Instructions</a>, <a href="%3$s" target="_blank" rel="noreferrer">Video</a>',
                        'shoplic-integration-for-naver-map'
                    ),
                    'https://www.ncloud.com',
                    'https://docs.google.com/presentation/d/1IA5tmDzu1QiQ3lBqjhBsq1jPnYgJYRhglmiFNMrA9So/edit?usp=drive_link',
                    'https://drive.google.com/file/d/1TRUcScN4jY-Sh6oNktOLEsjKeWjl0WSQ/view?usp=drive_link'
                ),
                'label_for'   => "$theName-client_id",
            ]
        );

        if (isWpDevelopmentEnvironment()) {
            add_settings_field(
                "$theSection-development",
                __('Development Mode', 'shoplic-integration-for-naver-map'),
                [FieldRenderer::class, 'checkbox'],
                self::getPageSlug(),
                $theSection,
                [
                    'attrs'       => [
                        'id'    => "$theName-development",
                        'name'  => "{$theName}[development]",
                        'type'  => 'checkbox',
                        'value' => $settings->isDevelopment(),
                    ],
                    'descripton'  => __('', 'shoplic-integration-for-naver-map'),
                    'instruction' => __('Running in development mode!', 'shoplic-integration-for-naver-map'),
                    'label_for'   => "$theName-development",
                ]
            );
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /// 섹션: 지도 표시
        // Will be available in next release..
        /*
        $theSection = 'nm-map-display';

        add_settings_section(
            $theSection,
            __('지도 표시', 'shoplic-integration-for-naver-map'),
            '__return_empty_string',
            self::getPageSlug()
        );

        // Get available post type options.
        $options   = [];
        $postTypes = getAllowedPostTypes('objects');
        foreach ($postTypes as $postType) {
            $options[$postType->name] = $postType->label;
        }

        add_settings_field(
            "$theSection-linked_post_types",
            __('연결된 포스트 타입', 'shoplic-integration-for-naver-map'),
            [FieldRenderer::class, 'choose'],
            self::getPageSlug(),
            $theSection,
            [
                'attrs'       => [
                    'id'    => "$theName-linked_post_types",
                    'name'  => "{$theName}[linked_post_types]",
                    'value' => $settings->getLinkedPostTypes(),
                ],
                'options'     => $options,
                'type'        => 'checkbox',
                'description' => __('위치 정보를 연결시킬 포스트 타입을 지정해 주세요.', 'shoplic-integration-for-naver-map'),
            ]
        );

        add_settings_field(
            "$theSection-display_method",
            __('표시 방법', 'shoplic-integration-for-naver-map'),
            [FieldRenderer::class, 'select'],
            self::getPageSlug(),
            $theSection,
            [
                'attrs'       => [
                    'id'    => "$theName-display_method",
                    'name'  => "{$theName}[display_method]",
                    'value' => $settings->getDisplayMethod(),
                ],
                'description' => __('지도를 표시할 방법을 설정합니다.', 'shoplic-integration-for-naver-map'),
                'options'     => [
                    'manual'    => __('수동: 항상 숏코드로 직접 삽입해야 합니다.', 'shoplic-integration-for-naver-map'),
                    'automatic' => __('자동: 참조된 위치/지도가 있으면 자동으로 표시합니다.', 'shoplic-integration-for-naver-map'),
                ],
                'label_for'   => "$theName-display",
            ]
        );

        add_settings_field(
            "$theSection-display_position",
            __('표시 위치', 'shoplic-integration-for-naver-map'),
            [FieldRenderer::class, 'choose'],
            self::getPageSlug(),
            $theSection,
            [
                'attrs'       => [
                    'id'    => "$theName-display_position",
                    'name'  => "{$theName}[display_position]",
                    'value' => $settings->getDisplayPosition(),
                ],
                'description' => __('지도가 표시될 본문 위치를 지정합니다.', 'shoplic-integration-for-naver-map'),
                'options'     => [
                    'top'    => '본문 상단',
                    'bottom' => '본문 하단',
                ],
                'type'        => 'radio',
            ]
        );

        add_settings_field(
            "$theSection-display_priority",
            __('표시 우선순위', 'shoplic-integration-for-naver-map'),
            [FieldRenderer::class, 'input'],
            self::getPageSlug(),
            $theSection,
            [
                'attrs'       => [
                    'id'    => "$theName-display_priority",
                    'name'  => "{$theName}[display_priority]",
                    'value' => $settings->getDisplayPriority(),
                    'type'  => 'number',
                ],
                'description' => __('지도의 표시 순서를 보다 세밀하게 조정합니다.', 'shoplic-integration-for-naver-map'),
                'label_for'   => "$theName-display_position",
            ]
        );
        */
    }

    public static function getPageSlug(): string
    {
        return 'nm-settings';
    }
}
