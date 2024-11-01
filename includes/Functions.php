<?php

namespace {

    use Shoplic\NaverMap\Container;

    if ( ! function_exists('nm')) {
        function nm(): Container
        {
            return Container::getInstance();
        }
    }
}

namespace Shoplic\NaverMap {

    use Shoplic\NaverMap\Modules\CustomPosts;
    use WP_Post;
    use WP_Post_Type;
    use WP_Query;

    /**
     * Render template file.
     *
     * @param string $__tmplName__
     * @param array  $__context__
     * @param bool   $__return__
     *
     * @return string
     */
    function render(string $__tmplName__, array $__context__ = [], bool $__return__ = false): string
    {
        $__output__   = '';
        $__tmplName__ = trim($__tmplName__, '\\/');
        $__path__     = dirname(NM_MAIN) . "/includes/templates/$__tmplName__.tmpl.php";

        if (file_exists($__path__) && is_file($__path__) && is_readable($__path__)) {
            if ($__return__) {
                ob_start();
            }

            (function ($__path__, $__context__) {
                if ($__context__) {
                    extract($__context__, EXTR_SKIP);
                }
                unset($__context__);
                include $__path__;
            })($__path__, $__context__);

            if ($__return__) {
                $__output__ = ob_get_clean();
            }
        }

        return $__output__;
    }

    function getLocationPostType(): string
    {
        return CustomPosts::PT_LOCATION;
    }

    function getMapPostType(): string
    {
        return CustomPosts::PT_MAP;
    }

    function getDefaultCoord(): array
    {
        return [
            'lat' => 37.50112047582572,
            'lng' => 127.02594679999856,
        ];
    }

    function isWpDevelopmentEnvironment(): bool
    {
        return 'production' !== wp_get_environment_type();
    }

    function isDevelopment(): bool
    {
        return isWpDevelopmentEnvironment() && ! empty(nm()->settings->wrap->isDevelopment());
    }

    /**
     * @param string $output 'names', or 'objects'.
     *
     * @return WP_Post_Type[]|string[]
     */
    function getAllowedPostTypes(string $output = 'names'): array
    {
        $postTypes = get_post_types(['_builtin' => false], $output);

        if ('objects' === $output) {
            foreach ($postTypes as $i => $postType) {
                if (in_array($postType->name, [getLocationPostType(), getMapPostType()], true)) {
                    unset($postTypes[$i]);
                }
            }

            return array_values(array_merge([get_post_type_object('post'), get_post_type_object('page')], $postTypes));
        }

        // names.
        foreach ($postTypes as $i => $postType) {
            if (in_array($postType, [getLocationPostType(), getMapPostType()], true)) {
                unset($postTypes[$i]);
            }
        }

        return array_values(array_merge(['post', 'page'], $postTypes));
    }

    /**
     * @return array{
     *   array{
     *     id: int,
     *     title: string,
     *     type: string,
     *     status: string,
     *     address: string,
     *     alt_title: string,
     *     coord: array{
     *       lat: float,
     *       lng: float
     *     },
     *   }
     * }
     */
    function getAllLocations(): array
    {
        $cf          = nm()->customFields;
        $metaAddress = $cf->locationAddress;
        $metaCoord   = $cf->locationCoord;

        $query = new WP_Query(
            [
                'post_type'   => getLocationPostType(),
                'post_status' => 'publish',
                'orderby'     => 'title',
                'order'       => 'asc',
                'nopaging'    => true,
                'meta_query'  => [
                    'relation' => 'AND',
                    [
                        'key'     => $metaAddress->getKey(),
                        'compare' => 'EXISTS',
                    ],
                    [
                        'key'     => $metaCoord->getKey(),
                        'compare' => 'EXISTS',
                    ],
                ],
            ]
        );

        return array_map(
            function (WP_Post $post) use (&$metaAddress, &$metaCoord) {
                return array_merge(
                    [
                        'id'     => $post->ID,
                        'title'  => $post->post_title,
                        'type'   => $post->post_type,
                        'status' => $post->post_status,
                    ],
                    nm()->customFieldGroups->getLocationData($post->ID)
                );
            },
            $query->posts
        );
    }
}
