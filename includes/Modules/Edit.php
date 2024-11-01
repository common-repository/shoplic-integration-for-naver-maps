<?php

namespace Shoplic\NaverMap\Modules;

use Shoplic\NaverMap\Supports\LocalizeDataSupport;
use WP_Post;

use function Shoplic\NaverMap\getAllLocations;
use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\getMapPostType;
use function Shoplic\NaverMap\isDevelopment;
use function Shoplic\NaverMap\render;

final class Edit implements Module
{
    public function __construct()
    {
        add_action('edit_form_after_editor', [$this, 'actionShowEditMap']);

        $location_post_type = getLocationPostType();
        add_action("save_post_$location_post_type", [$this, 'actionSaveLocation'], 10, 3);

        $map_post_type = getMapPostType();
        add_action("save_post_$map_post_type", [$this, 'actionSaveMap'], 10, 3);

        foreach (nm()->settings->wrap->getLinkedPostTypes() as $post_type) {
            add_action("add_meta_boxes_$post_type", [$this, 'actionAddMapRefMetaBox']);
            add_action("save_post_$post_type", [$this, 'actionSaveRef'], 10, 3);
        }
    }

    /**
     * 위치/지도 참조 메타 박스.
     */
    public function actionAddMapRefMetaBox()
    {
        add_meta_box('nm-map-ref', __('Naver map, location reference', 'shoplic-integration-for-naver-map'), [$this, 'outputRefMetaBox']);
    }

    /**
     * 기본 위치 포스트 타입이 아닌 다른 포스트 타입에 위치 정보를 기록할 때는 메타박스를 사용한다.
     *
     * @return void
     */
    public function outputRefMetaBox(WP_Post $post)
    {
        $this->prepareRefComponent($post->ID);
    }

    /**
     * 글 편집 에디터는 보이지 않게 한 대신, 지도 전용 편집 인터페이스를 보여준다.
     *
     * @param WP_Post $post
     *
     * @return void
     */
    public function actionShowEditMap(WP_Post $post): void
    {
        if (getMapPostType() === $post->post_type) {
            $this->prepareEditMapComponent($post->ID);
            return;
        }

        if (getLocationPostType() === $post->post_type) {
            $this->prepareEditLocationComponent($post->ID);
        }
    }

    /**
     * 위치 정보 저장
     */
    public function actionSaveLocation(int $postId, WP_Post $_, bool $update): void
    {
        if ( ! self::checkSavePoint('nm_location_nonce', 'nm-edit-location', $update)) {
            return;
        }

        $address   = sanitize_text_field(wp_unslash($_POST['nm_location_address'] ?? ''));
        $altTitle  = sanitize_text_field(wp_unslash($_POST['nm_location_alt_title'] ?? ''));
        $lat       = sanitize_text_field(wp_unslash($_POST['nm_location_coord']['lat'] ?? []));
        $lng       = sanitize_text_field(wp_unslash($_POST['nm_location_coord']['lng'] ?? []));
        $telephone = sanitize_text_field(wp_unslash($_POST['nm_location_telephone'] ?? ''));
        $url       = sanitize_text_field(wp_unslash($_POST['nm_location_url'] ?? ''));

        nm()->customFieldGroups->updateLocationData(
            $postId,
            $address,
            $altTitle,
            $lat,
            $lng,
            $telephone,
            $url
        );
    }

    /**
     * 지도 정보 저장
     */
    public function actionSaveMap(int $postId, WP_Post $_, bool $update): void
    {
        if ( ! self::checkSavePoint('nm_map_nonce', 'nm-edit-map', $update)) {
            return;
        }

        $value = CustomFields::sanitizeMapLocations(wp_unslash($_REQUEST['nm_map_locations'] ?? []));

        nm()->customFieldGroups->updateMapData($postId, $value);
    }

    /**
     * 위치/지도 참조 정보 저장
     */
    public function actionSaveRef(int $postId, WP_Post $_, bool $update): void
    {
        if ( ! self::checkSavePoint('nm_ref_nonce', 'nm-ref-nonce', $update)) {
            return;
        }

        $value = absint(wp_unslash($_REQUEST['nm_ref'] ?? []));

        nm()->customFields->ref->update($postId, $value);
    }

    /**
     * 지도 편집 콤포넌트를 준비.
     *
     * @param int $postId
     *
     * @return void
     */
    private function prepareEditMapComponent(int $postId): void
    {
        render('component-root', ['root_id' => 'nm-edit-map']);

        nm()
            ->scriptLoader
            ->enqueueScript('edit-map.tsx', ['nm-naver-map'], isDevelopment())
            ->localizeScript(
                'edit-map.tsx',
                'nmEditMap',
                [
                    'data'    => array_merge(
                        [
                            'allLocations' => getAllLocations(),
                        ],
                        nm()->customFieldGroups->getMapData($postId)
                    ),
                    'nonce'   => wp_create_nonce('nm-edit-map'),
                    'post_id' => $postId,
                ]
            )
        ;
    }

    /**
     * 위치 편집 콤포넌트를 준비.
     *
     * @param int $postId
     *
     * @return void
     */
    private function prepareEditLocationComponent(int $postId): void
    {
        render('component-root', ['root_id' => 'nm-edit-location']);

        nm()
            ->scriptLoader
            ->enqueueScript('edit-location.tsx', ['nm-naver-map-geocoder'], isDevelopment())
            ->localizeScript(
                'edit-location.tsx',
                'nmEditLocation',
                [
                    'data'    => nm()->customFieldGroups->getLocationData($postId),
                    'nonce'   => wp_create_nonce('nm-edit-location'),
                    'post_id' => $postId,
                ]
            )
        ;
    }

    /**
     * 위치/지도 참조 콤포넌트를 준비.
     */
    private function prepareRefComponent(int $postId): void
    {
        render('component-root', ['root_id' => 'nm-edit-map-ref']);

        nm()
            ->scriptLoader
            ->enqueueScript('edit-map-ref.tsx', [], isDevelopment())
            ->localizeScript(
                'edit-map-ref.tsx',
                'nmEditMapRef',
                /** @uses LocalizeDataSupport::getEditMapRefData() */
                [
                    'data'    => array_merge(
                        nm()->get(LocalizeDataSupport::class)->getEditMapRefData($postId),
                        ['selected' => nm()->customFields->ref->get($postId)]
                    ),
                    'nonce'   => wp_create_nonce('nm-ref-nonce'),
                    'post_id' => $postId,
                ]
            )
        ;
    }

    private static function checkSavePoint(string $field, string $action, bool $update): bool
    {
        $nonce = sanitize_text_field(wp_unslash($_REQUEST[$field] ?? ''));

        return wp_verify_nonce($nonce, $action) && $update && ! (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE);
    }
}
