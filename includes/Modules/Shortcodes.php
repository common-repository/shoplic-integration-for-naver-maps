<?php

namespace Shoplic\NaverMap\Modules;

use Shoplic\NaverMap\ArrayWraps\MapAttsWrap;
use Shoplic\NaverMap\Supports\MapSupport;

use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\getMapPostType;

final class Shortcodes implements Module
{
    public function __construct()
    {
        add_shortcode('shoplic_naver_map', [$this, 'shortcodeNaverMap']);
    }

    public function shortcodeNaverMap($atts): string
    {
        $attsWrap = MapAttsWrap::create(shortcode_atts(self::getDefaultAtts(), $atts));
        $postType = get_post_type($attsWrap->getPostId());

        if (getMapPostType() === $postType) {
            $output = $this->groupedLocationsMap($attsWrap);
        } elseif (getLocationPostType() === $postType) {
            $output = $this->singleLocationMap($attsWrap);
        } else {
            $output = '<p>' . __('Cannot load map data', 'shoplic-integration-for-naver-map') . '</p>';
        }

        return $output;
    }

    private function groupedLocationsMap(MapAttsWrap $attsWrap): string
    {
        /** @var MapSupport $controller */
        $controller = nm()->get(MapSupport::class);

        return $controller->getGroupedLocationsMap($attsWrap);
    }

    private function singleLocationMap(MapAttsWrap $attsWrap): string
    {
        /** @var MapSupport $controller */
        $controller = nm()->get(MapSupport::class);

        return $controller->getSingleLocationMap($attsWrap);
    }

    private static function getDefaultAtts(): array
    {
        return MapAttsWrap::getDefautlAtts();
    }
}
