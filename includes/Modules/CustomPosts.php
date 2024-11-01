<?php

namespace Shoplic\NaverMap\Modules;

use function Shoplic\NaverMap\getLocationPostType;
use function Shoplic\NaverMap\getMapPostType;

final class CustomPosts implements Module
{
    public const PT_LOCATION = 'nm_location';
    public const PT_MAP      = 'nm_map';

    public function __construct()
    {
        register_post_type(
            self::PT_LOCATION,
            [
                'labels'              => [
                    'name'                     => _x('Locations', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – General name for the post type, usually plural. The same and overridden by $post_type_object->label. Default is ‘Posts’ / ‘Pages’.
                    'singular_name'            => _x('Location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Name for one object of this post type. Default is ‘Post’ / ‘Page’.
                    'add_new'                  => _x('Add New', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Default is ‘Add New’ for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x( 'Add New', 'product', 'textdomain' );.
                    'add_new_item'             => _x('Add New Location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for adding a new singular item. Default is ‘Add New Post’ / ‘Add New Page’.
                    'edit_item'                => _x('Edit Location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for editing a singular item. Default is ‘Edit Post’ / ‘Edit Page’.
                    'new_item'                 => _x('New Location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the new item page title. Default is ‘New Post’ / ‘New Page’.
                    'view_item'                => _x('View Location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for viewing a singular item. Default is ‘View Post’ / ‘View Page’.
                    'view_items'               => _x('View Locations', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for viewing post type archives. Default is ‘View Posts’ / ‘View Pages’.
                    'search_items'             => _x('Search Locations', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for searching plural items. Default is ‘Search Posts’ / ‘Search Pages’.
                    'not_found'                => _x('No locations found', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when no items are found. Default is ‘No posts found’ / ‘No pages found’.
                    'not_found_in_trash'       => _x('o locations found in Trash', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when no items are in the Trash. Default is ‘No posts found in Trash’ / ‘No pages found in Trash’.
                    'parent_item_colon'        => _x('Parent Location:', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. Default is ‘Parent Page:’.
                    'all_items'                => _x('All Locations', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label to signify all items in a submenu link. Default is ‘All Posts’ / ‘All Pages’.
                    'archives'                 => _x('Location Archives', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for archives in nav menus. Default is ‘Post Archives’ / ‘Page Archives’.
                    'attributes'               => _x('Location Attributes', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the attributes meta box. Default is ‘Post Attributes’ / ‘Page Attributes’.
                    'insert_into_item'         => _x('Insert into location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the media frame button. Default is ‘Insert into post’ / ‘Insert into page’.
                    'uploaded_to_this_item'    => _x('Uploaded to this location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the media frame filter. Default is ‘Uploaded to this post’ / ‘Uploaded to this page’.
                    'featured_image'           => _x('Featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the featured image meta box title. Default is ‘Featured image’.
                    'set_featured_image'       => _x('Set featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for setting the featured image. Default is ‘Set featured image’.
                    'remove_featured_image'    => _x('Remove featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for removing the featured image. Default is ‘Remove featured image’.
                    'use_featured_image'       => _x('Use as featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label in the media frame for using a featured image. Default is ‘Use as featured image’.
                    'menu_name'                => _x('Locations', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the menu name. Default is the same as name.
                    'filter_items_list'        => _x('Filter pages list’', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the table views hidden heading. Default is ‘Filter posts list’ / ‘Filter pages list’.
                    'filter_by_date'           => _x('Filter by date', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the date filter in list tables. Default is ‘Filter by date’.
                    'items_list_navigation'    => _x('Locations list navigation', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the table pagination hidden heading. Default is ‘Posts list navigation’ / ‘Pages list navigation’.
                    'items_list'               => _x('Locations list', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the table hidden heading. Default is ‘Posts list’ / ‘Pages list’.
                    'item_published'           => _x('Location published.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is published. Default is ‘Post published.’ / ‘Page published.’
                    'item_published_privately' => _x('Location published privately.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is published with private visibility. Default is ‘Post published privately.’ / ‘Page published privately.’
                    'item_reverted_to_draft'   => _x('Location reverted to draft.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’ / ‘Page reverted to draft.’
                    'item_trashed'             => _x('Location trashed.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is moved to Trash. Default is ‘Post trashed.’ / ‘Page trashed.’
                    'item_scheduled'           => _x('Location scheduled.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’ / ‘Page scheduled.’
                    'item_updated'             => _x('Location scheduled.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is updated. Default is ‘Post updated.’ / ‘Page updated.’
                    'item_link'                => _x('Location Link', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Title for a navigation link block variation. Default is ‘Post Link’ / ‘Page Link’.
                    'item_link_description'    => _x('A link to a location', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Description for a navigation link block variation. Default is ‘A link to a post.’ / ‘A link to a page.’
                ],
                'description'         => __('Location custom post type', 'shoplic-integration-for-naver-map'),
                'public'              => false,
                'hierarchical'        => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=nm_map',
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => true,
                'show_in_rest'        => false,
                'menu_icon'           => 'dashicons-building',
                'capability_type'     => 'post',
                'supports'            => ['title'],
                'has_archive'         => false,
                'query_var'           => false,
                'can_export'          => false,
                'delete_with_user'    => false,
            ]
        );

        register_post_type(
            self::PT_MAP,
            [
                'labels'              => [
                    'name'                     => _x('Maps', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – General name for the post type, usually plural. The same and overridden by $post_type_object->label. Default is ‘Posts’ / ‘Pages’.
                    'singular_name'            => _x('Map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Name for one object of this post type. Default is ‘Post’ / ‘Page’.
                    'add_new'                  => _x('Add New', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Default is ‘Add New’ for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x( 'Add New', 'product', 'textdomain' );.
                    'add_new_item'             => _x('Add New Map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for adding a new singular item. Default is ‘Add New Post’ / ‘Add New Page’.
                    'edit_item'                => _x('Edit Map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for editing a singular item. Default is ‘Edit Post’ / ‘Edit Page’.
                    'new_item'                 => _x('New Map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the new item page title. Default is ‘New Post’ / ‘New Page’.
                    'view_item'                => _x('View Map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for viewing a singular item. Default is ‘View Post’ / ‘View Page’.
                    'view_items'               => _x('View Maps', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for viewing post type archives. Default is ‘View Posts’ / ‘View Pages’.
                    'search_items'             => _x('Search Maps', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for searching plural items. Default is ‘Search Posts’ / ‘Search Pages’.
                    'not_found'                => _x('No maps found', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when no items are found. Default is ‘No posts found’ / ‘No pages found’.
                    'not_found_in_trash'       => _x('o maps found in Trash', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when no items are in the Trash. Default is ‘No posts found in Trash’ / ‘No pages found in Trash’.
                    'parent_item_colon'        => _x('Parent Map:', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. Default is ‘Parent Page:’.
                    'all_items'                => _x('All Maps', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label to signify all items in a submenu link. Default is ‘All Posts’ / ‘All Pages’.
                    'archives'                 => _x('Map Archives', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for archives in nav menus. Default is ‘Post Archives’ / ‘Page Archives’.
                    'attributes'               => _x('Map Attributes', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the attributes meta box. Default is ‘Post Attributes’ / ‘Page Attributes’.
                    'insert_into_item'         => _x('Insert into map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the media frame button. Default is ‘Insert into post’ / ‘Insert into page’.
                    'uploaded_to_this_item'    => _x('Uploaded to this map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the media frame filter. Default is ‘Uploaded to this post’ / ‘Uploaded to this page’.
                    'featured_image'           => _x('Featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the featured image meta box title. Default is ‘Featured image’.
                    'set_featured_image'       => _x('Set featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for setting the featured image. Default is ‘Set featured image’.
                    'remove_featured_image'    => _x('Remove featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for removing the featured image. Default is ‘Remove featured image’.
                    'use_featured_image'       => _x('Use as featured image', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label in the media frame for using a featured image. Default is ‘Use as featured image’.
                    'menu_name'                => _x('Maps', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the menu name. Default is the same as name.
                    'filter_items_list'        => _x('Filter pages list’', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the table views hidden heading. Default is ‘Filter posts list’ / ‘Filter pages list’.
                    'filter_by_date'           => _x('Filter by date', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the date filter in list tables. Default is ‘Filter by date’.
                    'items_list_navigation'    => _x('Maps list navigation', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the table pagination hidden heading. Default is ‘Posts list navigation’ / ‘Pages list navigation’.
                    'items_list'               => _x('Maps list', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label for the table hidden heading. Default is ‘Posts list’ / ‘Pages list’.
                    'item_published'           => _x('Map published.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is published. Default is ‘Post published.’ / ‘Page published.’
                    'item_published_privately' => _x('Map published privately.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is published with private visibility. Default is ‘Post published privately.’ / ‘Page published privately.’
                    'item_reverted_to_draft'   => _x('Map reverted to draft.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’ / ‘Page reverted to draft.’
                    'item_trashed'             => _x('Map trashed.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is moved to Trash. Default is ‘Post trashed.’ / ‘Page trashed.’
                    'item_scheduled'           => _x('Map scheduled.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’ / ‘Page scheduled.’
                    'item_updated'             => _x('Map scheduled.', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Label used when an item is updated. Default is ‘Post updated.’ / ‘Page updated.’
                    'item_link'                => _x('Map Link', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Title for a navigation link block variation. Default is ‘Post Link’ / ‘Page Link’.
                    'item_link_description'    => _x('A link to a map', 'CPT Label', 'shoplic-integration-for-naver-map'),
                    //  – Description for a navigation link block variation. Default is ‘A link to a post.’ / ‘A link to a page.’
                ],
                'description'         => __('Map custom post type', 'shoplic-integration-for-naver-map'),
                'public'              => false,
                'hierarchical'        => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => true,
                'show_in_rest'        => false,
                'menu_icon'           => 'dashicons-location-alt',
                'capability_type'     => 'post',
                'supports'            => ['title'],
                'has_archive'         => false,
                'query_var'           => false,
                'can_export'          => false,
                'delete_with_user'    => false,
            ]
        );

        // 메뉴 조정.
        add_action('admin_menu', [$this, 'actionRearrangeMenuOrder'], 999);
        add_filter('submenu_file', [$this, 'filterSubmenufile'], 10, 2);

        // 메시지 조정.
        add_filter('post_updated_messages', [$this, 'filterUpdatedMessages']);
    }

    /**
     * 메뉴 순서 새롭게 조정.
     */
    public function actionRearrangeMenuOrder(): void
    {
        global $submenu;

        // '새로 추가' 메뉴를 숨김.
        remove_submenu_page('edit.php?post_type=nm_map', 'post-new.php?post_type=nm_map');

        $desired_orders = [
            self::getMenuSlugMap()                => 5,  // 모든 네이버 지도.
            self::getMenuSlugLoc()                => 10, // 모든 위치.
            CustomTaxonomies::getMenuSlugMapCat() => 20, // 지도 분류.
            AdminSettings::getPageSlug()          => 30, // 네이버 지도 설정.
        ];

        $key       = self::getMenuSlugMap();
        $reordered = [];

        foreach ($submenu[$key] as $item) {
            $order = $desired_orders[$item[2]] ?? null;
            if ($order) {
                $reordered[$order] = $item;
            }
        }

        ksort($reordered);

        $submenu[$key] = $reordered;
    }

    /**
     * 네이버 지도 메뉴 아래 일부 서브메뉴 항목들의 선택 상태를 조정.
     */
    public function filterSubmenufile(?string $submenu_file, string $parent_file): ?string
    {
        if (self::getMenuSlugMap() === $submenu_file) {
            // 새 지도 추가.
            $submenu_file = $parent_file;
        }

        return $submenu_file;
    }

    /**
     * 지도 편집 후 관리자 메시지의 수정.
     *
     * @param array $messages
     *
     * @return array
     */
    public function filterUpdatedMessages(array $messages): array
    {
        $postType = get_post_type();

        if (in_array($postType, [getLocationPostType(), getMapPostType()], true)) {
            $obj  = get_post_type_object($postType);
            $name = $obj->labels->singular_name;

            $messages['post'][1] = sprintf(_x('%s updated.', 'Administration update message',  'shoplic-integration-for-naver-map'), $name);
            $messages['post'][4] = sprintf(_x('%s updated.', 'Administration update message',  'shoplic-integration-for-naver-map'), $name);
            $messages['post'][6] = sprintf(_x('%s published.', 'Administration update message',  'shoplic-integration-for-naver-map'), $name);
            $messages['post'][7] = sprintf(_x('%s saved.', 'Administration update message',  'shoplic-integration-for-naver-map'), $name);
            $messages['post'][8] = sprintf(_x('%s submitted.', 'Administration update message',  'shoplic-integration-for-naver-map'), $name);
        }

        return $messages;
    }

    public static function getMenuSlugLoc(): string
    {
        return 'edit.php?post_type=' . CustomPosts::PT_LOCATION;
    }

    public static function getMenuSlugMap(): string
    {
        return 'edit.php?post_type=' . CustomPosts::PT_MAP;
    }
}
