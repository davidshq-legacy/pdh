<?php
/**
 * WP Plugin Developer Handbook Custom Post Types
 *
 * WP comes with five post types built-in: post, page, attachment, revision, menu.
 *
 * Once registered a CPT gets a top-level admin screen that can be used to manage and create posts of that type
 *
 * CPT Identifier may not be greater than 20 characters, post_type column in db is VARCHAR with length of 20.
 *
 * Handbook: https://developer.wordpress.org/plugins/post-types/
 */

/**
 * Registering Custom Post Type
 *
 * Handbook: https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/
 * Developer Reference: https://developer.wordpress.org/reference/functions/register_post_type/
 */
 function pdh_custom_post_type()
 {

     register_post_type('pdh_product',
         array(
                 'labels'        => array(
                    'name'          => __('Products'),
                    'singular_name' => __('Product'),
                     ),
                 'public'        => true,
                 'has_archive'   => true,
	         /**
	          * Adding Permalinks
              *
              * CPT get their own permalinks by default: e.g. http://somewhere.com/pdh_product/%product_name%
              *
              * pdh_product = CPT slug
              * %product_name% = name of particular post
              *
              * To create a custom post type slug, use rewrite as below.
              *
              * Warning: Using generic slugs like "products" are likely to result in conflicts with other plugins/themes.
	          */
                'rewrite'       => array( 'slug' => 'products' ),
            )
        );
    }
    // The call for registration must occur before admin_init and after the after_setup_theme action hooks, init is usually good.
    add_action('init', 'pdh_custom_post_type');

/**
 * Using Custom Post Types
 *
 * You can create a Single Post Template for a CPT, name it: single-{post-type}.php
 * For an Archive Post Template: archive-{post_type}.php
 *
 * Alternatively use is_post_type_archive() in any template file to check if query is for archive of a given post type,
 * use post_type_archive_title() to display the post type title.
 *
 * Handbook: https://developer.wordpress.org/plugins/post-types/working-with-custom-post-types/
 */

/**
 * Querying by Post Type
 *
 * Below we'll display the 10 latest product posts.
 */

    $args = [
        'post_type'        => 'product',
        'posts_per_page'   => 10,
    ];
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();
        ?>
        <div class="entry-content">
            <?php the_title(); ?>
            <?php the_content(); ?>
        </div>
        <?php
    }

/**
 * Tying into the Main Query
 *
 * CPT's aren't added to the main query automatically, if CPTs should show up in standard archives or otherwise combined
 * with other post types, use the pre_get_posts action hook.
 *
 * @param $query
 *
 * @return mixed
 */
    function pdh_add_custom_post_types($query)
    {
        if (is_home() && $query->is_main_query()) {
            $query->set('post_type', ['post', 'page', 'movie']);
        }
        return $query;
    }
    add_action('pre_get_posts', 'pdh_add_custom_post_types');