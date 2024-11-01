<?php

namespace Shoplic\NaverMap\Modules;

use Shoplic\NaverMap\ArrayWraps\MapAttsWrap;
use Shoplic\NaverMap\Supports\MapSupport;

use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\getMapPostType;

final class ContentFilter implements Module
{
    /** @var string */
    private $position;

    /**
     * @param string $method   'manual', or 'automatic'.
     * @param string $position 'top', or 'bottom'.
     * @param int    $priority
     */
    public function __construct(string $method, string $position, int $priority)
    {
        if ( ! is_admin() && 'automatic' === $method) {
            add_filter('the_content', [$this, 'filterTheContent'], $priority);
        }
        $this->position = $position;
    }

    public function filterTheContent($content): string
    {
        $theId = get_the_ID();
        $refId = nm()->customFields->ref->get($theId);
        $type  = get_post_type($refId);

        if ($refId && current_user_can('read_post', $refId)) {
            if (getLocationPostType() === $type) {
                /** @uses MapSupport::getSingleLocationMap() */
                $root = nm()->get(MapSupport::class)
                            ->getSingleLocationMap(MapAttsWrap::create("post_id=$theId"))
                ;
            } elseif (getMapPostType() === $type) {
                /** @uses MapSupport::getGroupedLocationsMap() */
                $root = nm()->get(MapSupport::class)
                            ->getGroupedLocationsMap(MapAttsWrap::create("post_id=$theId"))
                ;
            } else {
                $root = '';
            }

            switch ($this->position) {
                case 'top':
                    $content = $root . $content;
                    break;

                case 'bottom':
                    $content = $content . $root;
                    break;
            }
        }

        return $content;
    }
}
