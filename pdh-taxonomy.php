<?php
// Custom Taxonomies: https://developer.wordpress.org/plugins/taxonomies/
// Taxonomies can be hierarchical (parent/child) or flat
// WP stores taxonomies in term_taxonomy
// WP stores the terms associated with a taxonomy in terms
// Built-in Taxonomies: Categories, Tags

function pdh_register_taxonomy_course()
{
    $labels = [
        'name'          => _x('Courses', 'taxonomy general name'),
        'singular_name' => _x('Course', 'taxonomy singular name'),
        'search_items'  => __('Search Courses'),
        'all_items'     => __('All Courses'),
        'parent_item'   => __('Parent Course'),
        'parent_item_colon' => __('Parent Course:'),
        'edit_item'     => __('Edit Course'),
        'update_item'   => __('Update Course'),
        'new_item_name' => __('New Course Name'),
        'menu_name'     => __('Course'),
    ];
    $args = [
        'hierarchical'  => true,
        'labels'        => $labels,
        'show_ui'       => true,
        'show_admin_column' => true,
        'query_var'     => true,
        'rewrite'       => ['slug' => 'course'], // permalink will be somedomain.com/course/%%term-slug%%
    ];
    // https://developer.wordpress.org/reference/functions/register_taxonomy/
    register_taxonomy('course', ['post']. $args);
}
add_action('init', 'pdh_register_taxonomy_course');

// May want to look at the_terms, wp_tag_cloud, and is_taxonomy

// If working with plugins created before 4.2, be aware that they may have issues due to 4.2's change in how terms work: https://developer.wordpress.org/plugins/taxonomies/split-terms-wp-4-2/
