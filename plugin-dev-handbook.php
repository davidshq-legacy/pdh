<?php
/**
 * Plugin Name: WP Plugin Dev Handbook Unofficial Reference Plugin
 * Plugin URI: https://github.com/davidshq/
 * Description: Takes the best practices and code from the handbook and provides them in a plugin along with useful
 * notes and links.
 * Version: 0.0.3
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Dave Mackey
 * Author URI: https://davemackey.net/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pdh
 * Domain Path: // Where translations can be found
 * Network: // Whether plugin can be activated network-wide
 */

/**
 * Setup a Custom Post Type
 */
 function pdh_setup_book_post_type() {
     // register_post_type( $cpt_name, [ $args ] );
     register_post_type( 'book', ['public' => 'true'] );
 }
 add_action( 'init', 'pdh_setup_post_type');

/**
 * Activate Plugin
 */
 function pdh_activate() {
     pdh_setup_book_post_type();
     // clear permalinks
     flush_rewrite_rules();
 }

/**
 * Register Activation Hook
 *
 * Reference: https://developer.wordpress.org/reference/functions/register_activation_hook/
 */
 register_activation_hook( __FILE__, 'pdh_activate' ); //

/**
 * Deactivate Plugin
 */
 function pdh_deactivate() {
     // unregister post type
     unregister_post_type( 'book' );
     // clear permalinks
     flush_rewrite_rules();
 }

/**
 * Register Deactivation Hook
 *
 * Reference: https://developer.wordpress.org/reference/functions/register_deactivation_hook/
 */
 register_deactivation_hook( __FILE__, 'pdh_deactivate' );

/**
 * Uninstall Plugin
 *
 * When a plugin is to be entirely removed (deleted) from a WP instance we need to ensure that it appropriately
 * removes itself from WP and doesn't leave junk behind (files, database entries, etc.)
 *
 * This can be done using the register_uninstall_hook or by creating an uninstall.php file in the base of one's plugin.
 * For the latter please see the uninstall.php file.
 */
 function pdh_uninstall() {
     // Do something, like remove options and tables
 }

/**
 * Register Uninstall Hook
 *
 * Reference: https://developer.wordpress.org/reference/functions/register_uninstall_hook/
 */
 register_uninstall_hook( __FILE__, 'pdh_uninstall' );


/**
 * Creating a Class
 *
 * By default all variables, functions, and classes are defined in the global namespace (which includes WordPress as
 * well as all themes and plugins). It does not affect variables inside functions or classes.
 *
 * One can prefix a unique identifier to every variable, function, and class OR one can use PHP's built-in
 * functions (#001) to detect if a conflict exists BUT it is best to create a class in which your code is separated in
 * scope from the global namespace.
 */
if (!class_exists( 'pdh_Plugin' )) {
    class pdh_Plugin
    {
	    /**
	     * Register a Setting
	     */
        public static function init() {
            register_setting( 'pdh_settings', 'pdh_option_foo' );
        }

	    /**
         * Return a Setting
         *
	     * @return mixed|void
	     */
        public static function get_foo() {
            return get_option( 'pdh_option_foo' );
        }
    }

    pdh_Plugin::init();
    pdh_Plugin::get_foo();
}

/**

 */

/**
 * Get the URL to a File(#004)
 *
 * Returns a URL like: somedomain.com/wp-content/plugins/pdh/pdh.js
 *
 * There are similar functions you'll want to be aware of as well
 * - For Plugins: plugin_dir_url(), plugin_dir_path(), plugin_basename()
 * - For Themes: get_template_directory_uri(), get_stylesheet_directory_uri(), get_stylesheet_uri(),
 * get_theme_root_uri(), get_theme_root(), get_theme_roots(), get_stylesheet_directory(), get_template_directory
 * - For Site Home: home_url(), get_home_path()
 * - For WP: admin_url(), get_admin_url(), get_home_url(), network_admin_url(), network_site_url(), network_home_url()
 *
 * There are WP global constants for paths but these should not be used directly.(#004)
 */
plugins_url( 'pdhscript.js', __FILE__ );

/**
 * To Enqueue (Load) CSS Files
 *
 * TODO: Add working example.
 */
wp_enqueue_style();

// Revisit privacy section: https://developer.wordpress.org/plugins/privacy/
// Also: https://developer.wordpress.org/plugins/privacy/suggesting-text-for-the-site-privacy-policy/
// And: https://developer.wordpress.org/plugins/privacy/adding-the-personal-data-exporter-to-your-plugin/
// And: https://developer.wordpress.org/plugins/privacy/adding-the-personal-data-eraser-to-your-plugin/
// And: https://developer.wordpress.org/plugins/privacy/privacy-related-options-hooks-and-capabilities/

// Metadata: https://developer.wordpress.org/plugins/metadata/
// Metadata is information about information. In WP it is associated with posts, users, comments, and terms.
}