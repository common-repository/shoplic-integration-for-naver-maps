<?php

namespace Shoplic\NaverMap\Modules;

use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\getMapPostType;

class AdminListTableFields implements Module
{
    public function __construct()
    {
        $location_post_type = getLocationPostType();
        $map_post_type      = getMapPostType();

        add_filter("manage_{$location_post_type}_posts_columns", [$this, 'filterCustomColumnsLocation']);
        add_action("manage_{$location_post_type}_posts_custom_column", [$this, 'actionCustomColumnLocation'], 10, 2);

        add_filter("manage_{$map_post_type}_posts_columns", [$this, 'filterCustomColumnsMap']);
        add_action("manage_{$map_post_type}_posts_custom_column", [$this, 'actionCustomColumnMap'], 10, 2);
    }

    public function filterCustomColumnsLocation(array $columns): array
    {
        unset($columns['date']);

        $columns['nm_props']     = __('Address/Telephone', 'shoplic-integration-for-naver-map');
        $columns['nm_url']       = __('URL', 'shoplic-integration-for-naver-map');
        $columns['nm_shortcode'] = __('Shortcode', 'shoplic-integration-for-naver-map');

        return $columns;
    }

    public function filterCustomColumnsMap(array $columns): array
    {
        unset($columns['date']);

        $columns['nm_count']     = __('Number of Locations', 'shoplic-integration-for-naver-map');
        $columns['nm_shortcode'] = __('Shortcode', 'shoplic-integration-for-naver-map');

        return $columns;
    }

    public function actionCustomColumnLocation(string $columnName, int $postId): void
    {
        switch ($columnName) {
            case 'nm_props':
                $address   = nm()->customFields->locationAddress->get($postId);
                $telephone = nm()->customFields->locationTelephone->get($postId);
                if ($address) {
                    echo esc_html($address);
                }
                if ($telephone) {
                    echo '<br>' . esc_html($telephone);
                }
                break;

            case 'nm_url':
                $url = nm()->customFields->locationUrl->get($postId);
                if ($url) {
                    echo '<a href="' . esc_url($url) . '" target="_blank" rel="extermal nofollow noreferrer">' .
                        __('Confirm', 'shoplic-integration-for-naver-map') .
                        '</a>';
                }
                break;

            case 'nm_shortcode':
                $this->outputShortcodeInfo($postId);
                break;
        }
    }

    public function actionCustomColumnMap(string $columnName, int $postId): void
    {
        switch ($columnName) {
            case 'nm_count':
                $locations = nm()->customFields->mapLocations->get($postId);
                if ($locations) {
                    printf(
                        // Translators: number of places.
                        _n('%d place', '%d places', count($locations), 'shoplic-integration-for-naver-map'),
                        count($locations)
                    );
                }
                break;

            case 'nm_shortcode':
                $this->outputShortcodeInfo($postId);
                break;
        }
    }

    private function outputShortcodeInfo(int $postId): void
    {
        echo '<code>';
        echo '[shoplic_naver_map post_id="' . absint($postId) . '"]';
        echo '</code>';
    }
}
