<?php

namespace Shoplic\NaverMap\Modules;

final class CustomTaxonomies implements Module
{
    public const TAX_MAP_CAT = 'nm_map_cat';

    public function __construct()
    {
        register_taxonomy(
            self::TAX_MAP_CAT,
            [CustomPosts::PT_LOCATION, CustomPosts::PT_MAP],
            [
                'labels'             => [
                    'name'                       => _x('Categories', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // General name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default 'Tags'/'Categories'.
                    'singular_name'              => _x('Category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Name for one object of this taxonomy. Default 'Tag'/'Category'.
                    'search_items'               => _x('Search Categories', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'Search Tags'/'Search Categories'.
                    'popular_items'              => _x('Popular categories', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // This label is only used for non-hierarchical taxonomies. Default 'Popular Tags'.
                    'all_items'                  => _x('All Categories', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'All Tags'/'All Categories'.
                    'parent_item'                => _x('Parent Category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // This label is only used for hierarchical taxonomies. Default 'Parent Category'.
                    'parent_item_colon'          => _x('Parent Category:', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // The same as parent_item, but with colon : in the end.
                    'edit_item'                  => _x('Edit Category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'Edit Tag'/'Edit Category'.
                    'view_item'                  => _x('View Category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'View Tag'/'View Category'.
                    'update_item'                => _x('Update Category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'Update Tag'/'Update Category'.
                    'add_new_item'               => _x('Add New Category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'Add New Tag'/'Add New Category'.
                    'new_item_name'              => _x('New Category Name', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'New Tag Name'/'New Category Name'.
                    'add_or_remove_items'        => _x('Add or remove tags', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // This label is only used for non-hierarchical taxonomies. Default 'Add or remove tags', used in the meta box when JavaScript is disabled.
                    'choose_from_most_used'      => _x('Choose from the most used tags', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // This label is only used on non-hierarchical taxonomies. Default 'Choose from the most used tags', used in the meta box.
                    'not_found'                  => _x('No categories found', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'No tags found'/'No categories found', used in the meta box and taxonomy list table.
                    'no_terms'                   => _x('No categories', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Default 'No tags'/'No categories', used in the posts and media list tables.
                    'filter_by_item'             => _x('Filter by category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // This label is only used for hierarchical taxonomies. Default 'Filter by category', used in the posts list table.
                    'items_list_navigation'      => _x('Categories List Navigation', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Label for the table pagination hidden heading.
                    'items_list'                 => _x('Categories List', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Label for the table hidden heading.
                    'most_used'                  => _x('Most Used', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Title for the Most Used tab. Default 'Most Used'.
                    'back_to_items'              => _x('Back to categories', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Label displayed after a term has been updated.
                    'item_link'                  => _x('Category Link', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Used in the block editor. Title for a navigation link block variation. Default 'Tag Link'/'Category Link'.
                    'item_link_description'      => _x('A link to a category', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                    // Used in the block editor. Description for a navigation link block variation. Default 'A link to a tag'/'A link to a category'.
                ],
                'description'        => _x('Taxonomy for locations and maps custom post type.', 'Custom taxonomy', 'shoplic-integration-for-naver-map'),
                'public'             => false,
                'publicly_queryable' => false,
                'hierarchical'       => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_in_nav_menus'  => false,
                'show_in_rest'       => false,
                'show_tagcloud'      => false,
                'show_in_quick_edit' => true,
                'show_admin_column'  => true,
                'query_var'          => false,
            ]
        );
    }

    public static function getMenuSlugMapCat(): string
    {
        return 'edit-tags.php?taxonomy=' . self::TAX_MAP_CAT . '&amp;post_type=' . CustomPosts::PT_MAP;
    }
}
