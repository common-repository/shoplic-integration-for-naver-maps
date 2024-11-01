<?php

namespace Shoplic\NaverMap\Supports;

use Shoplic\NaverMap\ArrayWraps\MapAttsWrap;
use Shoplic\NaverMap\Modules\CustomFieldGroups;
use Shoplic\NaverMap\Modules\ScriptLoader;
use WP_Query;

use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\isDevelopment;
use function Shoplic\NaverMap\render;

class MapSupport implements Support
{
    /** @var CustomFieldGroups */
    private $cfg;

    /** @var ScriptLoader */
    private $scriptLoader;

    /** @var array */
    private $enqueued;

    public function __construct(CustomFieldGroups $cfg, ScriptLoader $sl)
    {
        $this->cfg          = $cfg;
        $this->scriptLoader = $sl;
        $this->enqueued     = [];
    }

    /**
     * @param MapAttsWrap|object|array|string $attsWrap
     *
     * @return string
     */
    public function getGroupedLocationsMap($attsWrap): string
    {
        $attsWrap = self::validateAttsWrap($attsWrap);

        if ('yes' === $attsWrap->getDisabled()) {
            return '';
        }

        if ( ! isset($this->enqueued[$attsWrap->getPostId()])) {
            $this->enqueued[$attsWrap->getPostId()] = true;

            $mapData = $this->cfg->getMapData($attsWrap->getPostId());
            $data    = [];

            $query = new WP_Query(
                [
                    'post__in'    => $mapData['locations'],
                    'post_status' => 'publish',
                    'meta_query'  => [
                        [
                            'key'     => 'nm_location_coord',
                            'compare' => 'EXISTS',
                        ],
                    ],
                    'nopaging'    => true,
                    'orderby'     => 'title',
                    'order'       => 'ASC',
                    'post_type'   => getLocationPostType(),
                ]
            );

            foreach ($query->posts as $post) {
                $data[] = array_merge(
                    $this->cfg->getLocationData($post->ID),
                    [
                        'id'     => $post->ID,
                        'status' => $post->post_status,
                        'title'  => get_the_title($post),
                        'type'   => $post->post_type,
                    ]
                );
            }

            $this->scriptLoader
                ->enqueueScript('grouped-locations-map.tsx', ['nm-naver-map'], isDevelopment())
                ->localizeScript(
                    'grouped-locations-map.tsx',
                    "groupedLocationsMap_{$attsWrap->getPostId()}",
                    [
                        'atts'     => $attsWrap->getAtts(),
                        'data'     => $data,
                        'mapIcons' => [
                            'normal'   => plugins_url('assets/images/marker-blue.png', NM_MAIN),
                            'selected' => plugins_url('assets/images/marker-red.png', NM_MAIN),
                        ],
                    ]
                )
            ;
        }

        return render(
            'component-root',
            [
                'root_id'     => "grouped_locations_map-{$attsWrap->getPostId()}",
                'extra_attrs' => [
                    'data-map_type'            => 'grouped-locations-map',
                    'data-object_name_postfix' => $attsWrap->getPostId(),
                ],
            ],
            true
        );
    }

    /**
     * @param MapAttsWrap|array $attsWrap
     *
     * @return string
     */
    public function getSingleLocationMap($attsWrap): string
    {
        $attsWrap = self::validateAttsWrap($attsWrap);

        if ('yes' === $attsWrap->getDisabled()) {
            return '';
        }

        $status     = get_post_status($attsWrap->getPostId());
        $postAuthor = get_post_field('post_author', $attsWrap->getPostId());

        if ('publish' !== $status || ('private' === $status && $postAuthor != get_current_user_id())) {
            return '';
        }

        if ( ! isset($this->enqueued[$attsWrap->getPostId()])) {
            $this->enqueued[$attsWrap->getPostId()] = true;

            $this->scriptLoader
                ->enqueueScript('single-location-map.tsx', ['nm-naver-map'], isDevelopment())
                ->localizeScript(
                    'single-location-map.tsx',
                    "singleLocationMap_{$attsWrap->getPostId()}",
                    [
                        'atts'    => $attsWrap->getAtts(),
                        'data'    => nm()->customFieldGroups->getLocationData($attsWrap->getPostId(), 'display'),
                        'mapIcon' => plugins_url('assets/images/marker-blue.png', NM_MAIN),
                    ]
                )
            ;
        }

        return render(
            'component-root',
            [
                'root_id'     => "single_location_map-{$attsWrap->getPostId()}",
                'extra_attrs' => [
                    'data-map_type'            => 'single-location-map',
                    'data-object_name_postfix' => $attsWrap->getPostId(),
                ],
            ],
            true
        );
    }

    private static function validateAttsWrap($attsWrap): MapAttsWrap
    {
        if ($attsWrap instanceof MapAttsWrap) {
            return $attsWrap;
        }

        return MapAttsWrap::create($attsWrap);
    }
}
