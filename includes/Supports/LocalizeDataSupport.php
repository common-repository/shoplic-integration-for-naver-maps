<?php

namespace Shoplic\NaverMap\Supports;

use Shoplic\NaverMap\Modules\CustomFields;
use WP_Query;

use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\getMapPostType;

class LocalizeDataSupport implements Support
{
    /** @var CustomFields */
    private $cf;

    public function __construct(CustomFields $cf)
    {
        $this->cf = $cf;
    }

    /**
     *
     * @return array{
     *     locations: array{
     *         address: string,
     *         post_id: int,
     *         title: string,
     *     },
     *     maps: array{
     *         locations: int[],
     *         post_id: int,
     *         title: string,
     *     },
     * }
     */
    public function getEditMapRefData(): array
    {
        $locations = [];
        $maps      = [];

        $locQuery = new WP_Query(
            [
                'meta_query'  => [
                    [
                        'key'     => $this->cf->locationCoord->getKey(),
                        'compare' => 'EXISTS',
                    ],
                ],
                'nopaging'    => true,
                'post_status' => ['publish', 'private'],
                'post_type'   => getLocationPostType(),
            ]
        );

        $addressField  = $this->cf->locationAddress;
        $altTitleField = $this->cf->locationAltTitle;

        foreach ($locQuery->posts as $p) {
            $locations[$p->ID] = [
                'address'     => $addressField->get($p->ID),
                'post_id'     => $p->ID,
                'post_status' => get_post_status($p),
                'title'       => $altTitleField->get($p->ID) ?: get_the_title($p),
            ];
        }

        $mapQuery = new WP_Query(
            [
                'meta_query'  => [
                    [
                        'key'     => $this->cf->mapLocations->getKey(),
                        'compare' => 'EXISTS',
                    ],
                ],
                'nopaging'    => true,
                'post_status' => ['publish', 'private'],
                'post_type'   => getMapPostType(),
            ]
        );

        $locField = $this->cf->mapLocations;

        foreach ($mapQuery->posts as $p) {
            $maps[] = [
                'locations'   => $locField->get($p->ID),
                'post_id'     => $p->ID,
                'post_status' => get_post_status($p),
                'title'       => get_the_title($p),
            ];
        }

        return [
            'locations' => $locations,
            'maps'      => $maps,
        ];
    }
}
