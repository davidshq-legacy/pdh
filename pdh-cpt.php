<?php
    // https://developer.wordpress.org/plugins/post-types/

    // Registering Custom Post Type: https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/
    // WP comes with five post types built-in: post, page, attachment, revision, menu
    // Once registered a CPT gets a top-level admin screen that can be used to manage and create posts of that type
    // https://developer.wordpress.org/reference/functions/register_post_type/
    // CPT Identifier may not be greater than 20 characters, post_type column in db is VARCHAR with length of 20.
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
                // URLs
                // CPT gets its own permalinks, e.g. http://somewhere.com/pdh_product/%product_name%
                // pdh_product = cpt slug
                // %product_name% = name of particular post
                // If you want a custom CPT slug:
                'rewrite'       => array( 'slug' => 'products' ),
                // Note: using generic slugs like "products" can easily result in conflicts with other plugins/themes.
            )
        );
    }
    // The call for registration must occur before admin_init and after after_setup_theme action hooks, init is usually good.
    add_action('init', 'pdh_custom_post_type');

    // Using Custom Post Types Once Created: https://developer.wordpress.org/plugins/post-types/working-with-custom-post-types/
    // Single Post Template: single-{post_type}.php
    // Archive Post Template: archive-{post_type}.php
    // Alternatively use is_post_type_archive() in any template file to check if query is for archive of a given post type, use post_type_archive_title() to display the post type title.
    

    // Querying by Post Type
    // Display 10 latest product posts
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

    // Altering the Main Query
    // CPT's aren't added to the main query automatically, if CPTs should show up in standard archives or otherwise combined with other post types, use the pre_get_posts action hook.
    function pdh_add_custom_post_types($query)
    {
        if (is_home() && $query->is_main_query()) {
            $query->set('post_type', ['post', 'page', 'movie']);
        }
        return $query;
    }
    add_action('pre_get_posts', 'pdh_add_custom_post_types');