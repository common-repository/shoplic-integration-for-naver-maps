<?php

namespace Shoplic\NaverMap\Modules;

use Shoplic\NaverMap\Fields\Meta;
use Shoplic\NaverMap\Fields\MetaFactory;
use WP_Query;

use function Shoplic\NaverMap\getDefaultCoord;
use function Shoplic\NaverMap\getLocationPostType;

/**
 * 커스텀 필드를 정의하는 모듈
 *
 * @property-read Meta $locationAddress
 * @property-read Meta $locationAltTitle
 * @property-read Meta $locationCoord
 * @property-read Meta $locationTelephone
 * @property-read Meta $locationUrl
 * @property-read Meta $mapLocations
 * @property-read Meta $ref
 */
final class CustomFields implements Module
{
    public function __construct()
    {
        $this->registerLocationFields();
        $this->registerMapFields();
        $this->registerRefFields();
    }

    /**
     * @throws ModuleException
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'locationAddress':
                return MetaFactory::get('nm_location_address');

            case 'locationAltTitle':
                return MetaFactory::get('nm_location_alt_title');

            case 'locationCoord':
                return MetaFactory::get('nm_location_coord');

            case 'locationTelephone':
                return MetaFactory::get('nm_location_telephone');

            case 'locationUrl':
                return MetaFactory::get('nm_location_url');

            case 'mapLocations':
                return MetaFactory::get('nm_map_locations');

            case 'ref':
                return MetaFactory::get('nm_ref');
        }

        throw new ModuleException("'$name' not found in custom fields module.");
    }

    private function registerLocationFields(): void
    {
        /**
         * 위치 주소
         */
        register_meta(
            'post',
            'nm_location_address',
            [
                'object_subtype'    => '',
                'type'              => 'string',
                'description'       => _x('Location address field.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => '',
                'single'            => true,
                'sanitize_callback' => function ($v) { return sanitize_text_field($v); },
                'show_in_rest'      => false,
            ]
        );

        /**
         * 대체 제목 (지도에 표시할 제목)
         */
        register_meta(
            'post',
            'nm_location_alt_title',
            [
                'object_subtype'    => '',
                'type'              => 'string',
                'description'       => _x('Location alt title.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => '',
                'single'            => true,
                'sanitize_callback' => function ($v) { return sanitize_text_field($v); },
                'show_in_rest'      => false,
            ]
        );

        /**
         * 위/경도
         *
         * Lat: latitude  위도
         * Lng: longitude 경도
         */
        register_meta(
            'post',
            'nm_location_coord',
            [
                'object_subtype'    => '',
                'type'              => 'object',
                'description'       => _x('Location coordinate field.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => getDefaultCoord(),
                'single'            => true,
                'sanitize_callback' => [self::class, 'sanitizeCoord'],
                'show_in_rest'      => false,
            ]
        );

        /**
         * 위치 전화번호
         */
        register_meta(
            'post',
            'nm_location_telephone',
            [
                'object_subtype'    => '',
                'type'              => 'string',
                'description'       => _x('Location telephone field.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => '',
                'single'            => true,
                'sanitize_callback' => [self::class, 'sanitizeTelephone'],
                'show_in_rest'      => false,
            ]
        );

        /**
         * 위치 URL
         */
        register_meta(
            'post',
            'nm_location_url',
            [
                'object_subtype'    => '',
                'type'              => 'string',
                'description'       => _x('Location URL field.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => '',
                'single'            => true,
                'sanitize_callback' => function ($v) { return sanitize_url($v); },
                'show_in_rest'      => false,
            ]
        );
    }

    private function registerMapFields(): void
    {
        /**
         * 지도에 등재된 위치 ID 목록
         */
        register_meta(
            'post',
            'nm_map_locations',
            [
                'object_subtype'    => CustomPosts::PT_MAP,
                'type'              => 'array',
                'description'       => _x('Location list of post IDs.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => [],
                'single'            => true,
                'sanitize_callback' => [self::class, 'sanitizeMapLocations'],
                'show_in_rest'      => false,
            ]
        );
    }

    private function registerRefFields(): void
    {
        /**
         * 지도, 혹은 위치의 참조.
         */
        register_meta(
            'post',
            'nm_ref',
            [
                'object_subtype'    => '',
                'type'              => 'integer',
                'description'       => _x('Reference of map or coordination.', 'Description for meta field', 'shoplic-integration-for-naver-map'),
                'default'           => 0,
                'single'            => true,
                'sanitize_callback' => function ($v) { return absint($v); },
                'show_in_rest'      => false,
            ]
        );
    }

    public static function sanitizeCoord($value): array
    {
        $output = getDefaultCoord();

        // String to array.
        if (is_string($value)) {
            $decode = json_decode($value);
            if (false === $decode) {
                $value = explode(',', $value);
            } else {
                $value = $decode;
            }
        }

        if (is_array($value)) {
            if (wp_is_numeric_array($value)) {
                $lat = $value[0] ?? null;
                $lng = $value[1] ?? null;
            } else {
                $lat = $value['lat'] ?? null;
                $lng = $value['lng'] ?? null;
            }

            if (is_string($lat) && is_string($lng)) {
                $lat = filter_var($lat, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $lng = filter_var($lng, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }

            if ( ! is_null($lat) && ! is_null($lng)) {
                $output['lat'] = (float)$lat;
                $output['lng'] = (float)$lng;
            }
        }

        return $output;
    }

    public static function sanitizeTelephone($value): string
    {
        $output = '';

        if (is_string($value)) {
            $value  = preg_replace('/[^0-9]/', '', $value);
            $length = strlen($value);

            switch ($length) {
                case 12:
                    // ####-####-#### (12)
                    $output = implode('-', [substr($value, 0, 4), substr($value, 4, 4), substr($value, 8, 4)]);
                    break;
                case 11:
                    // ###-####-#### (11)
                    $output = implode('-', [substr($value, 0, 3), substr($value, 3, 4), substr($value, 7, 4)]);
                    break;
                case 10:
                    // ###-###-#### (10)
                    $output = implode('-', [substr($value, 0, 3), substr($value, 3, 3), substr($value, 6, 4)]);
                    break;
                case 9:
                    // ##-###-#### (9)
                    $output = implode('-', [substr($value, 0, 2), substr($value, 2, 3), substr($value, 5, 4)]);
                    break;
                case 8:
                    // #-###-#### (8)
                    $output = implode('-', [substr($value, 0, 1), substr($value, 1, 3), substr($value, 4, 4)]);
                    break;
                case 7:
                    // ###-#### (7)
                    $output = implode('-', [substr($value, 0, 3), substr($value, 3, 4)]);
                    break;
                case 6:
                    // ##-#### (6)
                    $output = implode('-', [substr($value, 0, 2), substr($value, 2, 4)]);
                    break;
                case 5:
                    // ##-### (5)
                    $output = implode('-', [substr($value, 0, 2), substr($value, 2, 3)]);
                    break;
                default:
                    $output = $value;
            }
        }

        return $output;
    }

    public static function sanitizeMapLocations($value): array
    {
        if (is_string($value)) {
            $output = array_filter(array_map('absint', explode(',', $value)));
        } elseif (is_array($value)) {
            $output = array_filter(array_map('absint', $value));
        } else {
            return [];
        }

        if ($output) {
            // Filter out non-location post types.
            // Anytime users may change their location post types.
            $query = new WP_Query(
                [
                    'post_type' => getLocationPostType(),
                    'post__in'  => $output,
                    'fields'    => 'ids',
                ]
            );

            $output = array_values(array_intersect($output, $query->posts));
        }

        return $output;
    }
}
