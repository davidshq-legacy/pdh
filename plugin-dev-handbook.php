<?php
/**
 * Plugin Name: Plugin Dev Handbook
 * Plugin URI: https://somedomain.com/
 * Description: Takes the best practices and code from the handbook and provides them in a plugin along with useful notes and links.
 * Version: 0.0.1
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Dave Mackey
 * Author URI: https://somedomain.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: plugin-dev-handbook
 * Domain Path: Where translations can be found
 * Network: Whether plugin can be activated network-wide
 */

 // We'll need some Hooks
 // Actions allow us to add/change WP functionality
 // Filters allow us to alter content as it is loaded/displayed to the user


 // Setup a Custom Post Type
 function pdh_setup_book_post_type() {
     // register "book" custom post type
     register_post_type( 'book', ['public' => 'true'] );
 }
 add_action( 'init', 'pdh_setup_post_type');

 // Activate plugin
 function pdh_activate() {
     pluginprefix_setup_post_type();
     // clear permalinks
     flush_rewrite_rules();
 }
 register_activation_hook( __FILE__, 'pdh_activate' ); // https://developer.wordpress.org/reference/functions/register_activation_hook/

 // Deactivate plugin
 function pdh_deactivate() {
     // unregister post type
     unregister_post_type( 'book' );
     // clear permalinks
     flush_rewrite_rules();
 }
 register_deactivation_hook( __FILE__, 'pdh_deactivate' ); // https://developer.wordpress.org/reference/functions/register_deactivation_hook/

 // Uninstall plugin
 function pdh_uninstall() {
     // Do something, like remove options and tables
     // Alternatively we could create an uninstall.php file which WP will automatically run when someone deletes the plugin
 }
 register_uninstall_hook( __FILE__, 'pdh_uninstall' ); // https://developer.wordpress.org/reference/functions/register_uninstall_hook/

 // If we want to allow our plugin to be extended, we'll need a do_action(), true for both actions and filters
 do_action()

 // If we want to remove some existing function, true for both actions and filters
 remove_action()

// Store data for the plugin in the database using the Options API
// If working with HTTP, use HTTP API
// And don't forget the Plugin API


// Remember that by default all variables, functions, and classes are defined in the global namespace.
// This does not affect variables inside of functions or classes.
// You can prefix everything with a unique identifier to avoid accidentally overwriting or being overwritten.
// One can also use PHP built-in functions to check for existence: isset(), function_exists, class_exists, defined()
// But it is easier to use a class for code in the plugin.
if (!class_exists( 'pdh_Plugin' )) {
    class pdh_Plugin
    {
        public static function init() {
            register_setting( 'pdh_settings', 'pdh_option_foo' );
        }

        public static function get_foo() {
            return get_option( 'pdh_option_foo' );
        }
    }

    pdh_Plugin::init();
    pdh_Plugin::get_foo();
}

// The sample folder structure included in the dev handbook is:
// plugin-name
//      plugin-name.php
//      uninstall.php
//      /languages
//      /includes
//      /admin
//          /js
//          /css
//          /images
//      /public
//          /js
//          /css
//          /images

// Code can be arranged in three main ways:
//  - single plugin file containing functions: https://github.com/GaryJones/move-floating-social-bar-in-genesis/blob/master/move-floating-social-bar-in-genesis.php
//  - single plugin file containing a class, instantiated object, and optionally functions: https://github.com/norcross/wp-comment-notes/blob/master/wp-comment-notes.php
//  - main plugin file with one or more class files:  https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate
//      - see: https://jjj.blog/2012/12/slash-architecture-my-approach-to-building-wordpress-plugins/
//      - see: https://iandunn.name/content/presentations/wp-oop-mvc/mvc.php#/

// One can start with boilerplate including:
//      - WPPB, probably the most popular: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate (5663 stars)
//      - WordPress Plugin Boilerplate, which hasn't been updated since 2014: https://github.com/claudiosanches/wordpress-plugin-boilerplate
//      - WP Skeleton Plugin, also no updates since 2014: https://github.com/ptahdunbar/wp-skeleton-plugin
//      - WP CLI Scaffold, pretty bare bones though it includes a gruntfile,, .editorconfig, .gitignore, .distignore, phpunit.xml.dist, .travis.yml, .phpcs.xml.dist, etc.: https://developer.wordpress.org/cli/commands/scaffold/plugin/

// To get the URL to some file
// Returns URL like somedomain.com/wp-content/plugins/pdh/phd.js
plugins_url( 'pdhscript.js', __FILE__ );

// To load plugins JS
wp_enqueue_script();

// To load plugins CSS
wp_enqueue_style();

// For finding locations see also
// for plugins:  plugin_dir_url, plugin_dir_path, and plugin_basename
// for themes: get_template_directory_uri(), get_stylesheet_directory_uri(), get_stylesheet_uri(), get_theme_root_uri(), get_theme_root(), get_theme_roots(), get_stylesheet_directory(), get_template_directory()
// for site home: home_url(), get_home_path()
// for wp: admin_url(), site_url(), content_url(), includes_url(), wp_upload_dir()
// for multisite: get_admin_url, get_home_url, get_site_url, network_admin_url, network_site_url, network_home_url
// There are WP constants for path, but these should not be used directly.


// Safety
// Check User Capabilities
//      Roles are groups, groups have capabilities
//      Capabilities are the specific permissions assigned to a user / user role
//      Only run code if user has proper capabilities to run it
//      Roles are a hierarchy, inheriting all lower roles' capabilities.
// Validate
// Sanitize Input
// Sanitize Output
// Create and Validate Nonces

// Checking User Capabilities
/**
 * generate a Delete link based on homepage url
 */
function pdh_generate_delete_link($content)
{
    // run only for single post page
    if (is_single() && in_the_lopp() && is_main_query()) {
        // add query arguments: action, post
        $url = add_query_arg(
            [
                'action' => 'phd_frontend_delete',
                'post'   => get_the_ID();
            ],
            home_url()
        );
        return $content . ' <a href="' . esc_url($url) . '">' . esc_html__('Delete Post', 'pdh' ) . '</a>'; 
    }
    return null;
    }

    /**
     * request handler
     */
    function pdh_delete_post()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'pdh_frontend_delete') {
            // verify we have a post id
            $post_id = (isset($_GET['post'])) ? ($_GET['post']) : (null);

            // verify there is a post with such a number
            $post = get_post((int)$post_id);
            if (empty($post)) {
                return;
            }

            // delete the posty
            wp_trash_post($post_id);

            // redirect to admin page
            $redirect = admin_url('edit.php');
            wp_safe_redirect($redirect);

            // we are done
            die;
        }
    }

    if (current_user_can('edit_others_post')) {
        /**
         * add the delete link to the end of the post content
         */
        add_filter('the_content', 'pdh_generate_delete_link');

        /**
         * register our request handler with the init hook
         */
        add_action('init', 'pdh_delete_post');
    }
}

// Validating Data
// Can use built-in PHP functions: isset(), empty(), mb_strlen(), strlen(), preg_match(), strpos(), count(), in_array()
// WP functions: is_email(), term_exists(), username_exists(), validate_file() - some others are named variants of *_exists(), *_validate(), and is_*(), though not all are for validation.
// Custom Functions: name like a question, e.g., is_phone_number()
function is_us_zip_code($zip_code)
{
    // 1: empty
    if (empty($zip_code)) {
        return false;
    }

    // 2: more than 10 characters
    if (strlen(trim($zip_code)) > 10) {
        return false;
    }

    // 3: incorrect format
    if (!preg_match('/^\d{5}(\-?\d{4})?$/', $zip_code)) {
        return false;
    }

    // passed
    return true;
}

// Call validation
if (isset($_POST['phd_zip_code']) && is_us_zip_code($_POST['phd_zip_code'])) {
    // do something
}

// Here we are checking an incoming sort key
$allowed_keys = ['author', 'post_author', 'date', 'post_date'];

$orderby = sanitize_key($_POST['orderby']);

// Third param says to check not only valid values but types, should be string.
// in_array is built-in php
if (in_array($orderby, $allowed_keys, true)) {
    // modify query to sort by the orderby key
}

// WP provides a number of built-in data sanitization functions.
santize_email()
sanitize_file_name()
sanitize_hex_color()
sanitize_hex_color_no_hash()
sanitize_html_class()
sanitize_key()
sanitize_meta()
sanitize_mime_type()
sanitize_option()
sanitize_sql_orderby()
sanitize_text_field()
sanitize_title()
sanitize_title_for_query()
sanitize_title_with_dashes()
sanitize_user()
esc_url_raw()
wp_filter_post_kses()
wp_filter_nohtml_kses()

// For example
$ title = sanitize_text_field($_POST['title']);
update_post_meta($post->ID, 'title', $title);

// Securing Output (helps with XSS attacks)
esc_html() // anytime an HTML element encloses a section of data being displayed
esc_url() // on all URLs, including those in src and href attributes
esc_js() // for inline JS
esc_attr() // for everything else that's printed into an HTML element's attribute
// Most WP functions properly prepare data for output and don't need to be escaped again.

// Rather than using echo, use WP localization functions
esc_html_e( 'Hello World', 'text_domain' );
// same as
echo esc_html( __( 'Hello World', 'text_domain' ) );
// Available helper functions are:
esc_html__()
esc_html_e()
esc_html_x()
esc_attr__()
esc_attr_e()
esc_attr_x()

// If output needs to be escaped in a specific way use wp_kses (kisses)
// Makes sure only specific HTML elements, attributes, and attribute values will occur in out, normalizes HTML entites.
$allowed_html = [
    'a'     => [
        'href'  => [],
        'title' => [],
    ],
    'br'   => [],
    'em'   => [],
    'strong' => [],
];
echo wp_kses( $custom_content, $allowed_html );
// wp_kses_post() is a wrapper function for wp_kses where $allowed_html is a set of rules used by post content.
echo wp_kses_post( $post_content );

// Nonces are generated numbers used to verify origin and intent of requests for security purposes, each nonce can only be used once.
// If your plugin allows users to submit data; be it on the Admin or the Public side; you have to make sure that the user is who they say they are and that they have the necessary capability to perform the action.
// And actually want to perform said action and haven't been tricked into it.
// When you generate the delete link, you’ll want to use wp_create_nonce() function to add a nonce to the link, the argument passed to the function ensures that the nonce being created is unique to that particular action.
// https://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/

/**
 * generate a delete link based on the homepage url
 */
function pdh_generate_delete_link($content)
{
    // run only for single post page
    if (is_single() && in_the_loop() && is_main_query()) {
        // add query arguments: action, post, nonce
        $url = add_query_arg(  )(
            [
                'action'    => 'pdh_frontend_delete',
                'post'      => get_the_ID(),
                'nonce'     => wp_create_nonce('pdh_frontend_delete'),
            ],
            home_url()
        );
        return $content . ' <a href="' . esc_url($url) . '">' . esc_html__('Delete Post', 'pdh') . '</a>';
    }
    return null;
}

/**
 * request handler
 */
function pdh_delete_post()
{
    if (
        isset($_GET['action']) &&
        isset($_GET['nonce']) &&
        $_GET['action'] === 'pdh_frontend_delete' &&
        wp_verify_nomnce($_GET['nonce'], 'pdh_frontend_delete')
    ) {
        // verify we have a post id using a ternary operator
        // useful reference on ternary operator: https://davidwalsh.name/php-shorthand-if-else-ternary-operators
        // $post_id = (condition) ? (true return value) : (false return value)
        $post_id = (isset($_GET['post'])) ? ($_GET['post']) : (null);
        // above is same as:
        If (isset($_GET['post'])) {
            $post_id = $_GET['post'];
        } else {
            $post_id = null;
        }

        // verify there is a post with such a number
        $post = get_post((int)$post_id);
        if (empty($post)) {
            return; // Wait didn't we just ensure that there is a post using ternary above?
        }

        // delete the post
        wp_trash_post($post_id);

        // redirect to the adminpage
        $redirect = admin_url('edit.php');
        wp_safe_redirect($redirect);

        // we are done
        die;
    }
}

if (current_user_can('edit_others_posts')) {
    /**
     * add the delete link to the end of the post content
     */
    add_filter('the_content', 'pdh_generate_delete_link');

    /**
     * register our request handler with the init hook
     */
    add_action('init', 'pdh_delete_post');
}

// Hooks are a way for one piece of code to interact/modify another piece of code.
// Type Types: Actions, Filters
// Actions - allow adding data or changing how WP operates.
// Require a custom function known as a Callback, then register it with WP hook for specific action or filter.
// Callback functions for Actions will run at a specific point in in the execution of WordPress, and can perform some kind of a task, like echoing output to the user or inserting something into the database.
function pdh_custom()
{
    // do something
}
// add_action can accept two (optional) additional parameters int $priority for priority given to callback function, and int $accepted_args for # of args passed back to callback function.
// priority determines when callback function will be executed in relation to other callback functions associated with a given hook.
// for priority, default value is 10, can be any positive integer. higher the number the lower on the priority of callback functions to be executed. 11 runs after 10, 9 runs before 10.
add_action('init', 'pdh_custom', 9, 2);
// Sometimes one might want a callback function to receive extra data related to function it is hooking into.
// ex when WPs aves a post and runs save_post hook it passes two parameters to callback function: id of post being saved, and post object itself:
// do_action('save_post', $post->ID, $post);
// When callback function is registered it can specify it wants to receive both arguments:
// add_action('save_post', 'pdh_custom', 10, 2);
// Then it can register the arguments in the function definitions
// function pdh_custom($post_id, $post)
// {
//      do something
// }

// Filters give you the ability to change data during the execution of WordPress. 
// Callback functions for Filters will accept a variable, modify it, and return it. 
// They are meant to work in an isolated manner, and should never have side effects such as affecting global variables and output.
function pdh_filter_title($title)
{
    return 'The ' . $title . ' was filtered';
}
add_filter('the_title', 'pdh_filter_title');
// Note that this also can have additional parameters like action for $priority and $accepted_args

// Add a CSS class to the body tag when certain condition is met
function pdh_css_body_class($classes)
{
    if (!is_admin()) {
        $classes[] = ''
    }
}

// We can also include custom hooks in our plugin so that others (or we) can extend it.
// It is recommended to use apply_filters() whenever text is output to the browser, especially on the front end, this makes customization for the end user simpler.
?>
<?php
// Example: Extension Action for Settings Form
// If one had a settings form added to the Admin Panel you can use actions to allow others to add their own settings to it.
function pdh_settings_page_html()
{
    ?>
    Foo: <input id="foo" name="foo" type="text">
    Bar: <input id="bar" name="bar" type="text">
    <?php
    do_action('pdh_after_settings_page_html');   
}

// Another plugin could then use this custom hook like so:
function opdh_add_settings()
{
    ?>
    New 1: <input id="new_setting" name="new_settings" type="text">
    <?php
}
add_action('pdh_after_settings_page_html', 'opdh_add_settings');

// Example: Hook for CPT, allows customization of parameters by another plugin
function pdh_create_post_type()
{
    $post_type_params = [/* ... */];
    
    register_post_type(
        'post_type_slug',
        apply_filters('pdh_post_type_params', $post_type_params)
    );
}

// Another plugin can then register a callback function and change post type parameters
function opdh_change_post_type_params($post_type_params)
{
    $post_type_params['hierarchical'] = true; // What isn't clear to me is whether the element is added if it doesn't exist or whether this will cause an error?
    return $post_type_params;
}
add_filter('pdh_post_type_params', 'opdh_change_post_type_params');

// if we want to remove callback functions on actions/filters we can do so using remove_action() and remove_filter()
// parameters passed should be identical to those used by the add_action(), add_filter() that registered it
// Removal of callback function must occur after registration of callback function was registered.

// Example: Improving perfromance of large theme by removing unnecessary functionality
// In this case we probably want to use after_setup_theme for optimal chances of running after registration.
function pdh_disable_slider()
{
    // make sure all parameters match add_action() call exactly
    remove_action('template_redirect', 'my_theme_setup_slider', 9);
}
// make sure we call remove_action() after add_action() has been called
add_action('after_setup_theme', 'pdh_disable_slider');

// if we needed to remove all callback functions associated with a hook
// we would use:
remove_all_actions()
remove_all_filters()

// We can also the same action/filter on multiple hooks and use current action() or current_filter() to determine the current hook and choose a path
function pdh_modify_content($content)
{
    switch (current_filter()) {
        case 'the content':
            // do something
            break;
        case 'the excerpt':
            // do something
            break;
    }
    return $content;
}
add_filter('the_content', 'pdh_modify_content');
add_filter('the_excerpt', 'pdh_modify_content');

// Some hooks run multiple times, may only want to use callback function once.
function pdh_custom()
{
    if (did_action('save_post') !== 1) { // Does this reset? What is considered an execution? What if hook is called three times?
        return;
    }
    // ...
}
add_action('save_post', 'pdh_custom');

// To have a callback function execute on every hook, use all.
function pdh_debug()
{
    echo '<p>' . current_action() . '</p>';
}
add_action('all', 'pdh_debug');

// Revisit privacy section: https://developer.wordpress.org/plugins/privacy/
// Also: https://developer.wordpress.org/plugins/privacy/suggesting-text-for-the-site-privacy-policy/
// And: https://developer.wordpress.org/plugins/privacy/adding-the-personal-data-exporter-to-your-plugin/
// And: https://developer.wordpress.org/plugins/privacy/adding-the-personal-data-eraser-to-your-plugin/
// And: https://developer.wordpress.org/plugins/privacy/privacy-related-options-hooks-and-capabilities/

// Administration Menus: https://developer.wordpress.org/plugins/administration-menus/
// See for additional info: https://developer.wordpress.org/theme/functionality/navigation-menus/

// Add a Top Level Menu
// Use add_menu_page: https://developer.wordpress.org/reference/functions/add_menu_page/
// Top Level Menus: https://developer.wordpress.org/plugins/administration-menus/top-level-menus/
// Step 1: Create function to output HTML for otpion page.
function pdh_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
    <h1><?php esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
    <?php
    // Output security fields for the registered setting "pdh_options"
    settings_fields( 'pdh_options' );
    // Output setting sections and their fields
    // (sections are registered for "pdh", each field is registered to a specific section)
    do_settings_sections( 'pdh' );
    // Output save settings button
    submit_button( 'Save Settings' );
    ?>
    </form>
    </div>
    <?php
    // Step 23: Registering PDH menu using admin_menu action hook.
    function pdh_options_page() {
        add_menu_page(
            'PDH', // string $page_title
            'PDH Options', // string $menu_title
            'manage_options', // string $capability
            'pdh', // string $menu_slug
            'pdh_options_page_html', // callable $function = ''
            plugin_dir_url(__FILE__) . 'images/icon_pdh.png', // string $icon_url = '',
            20 // int $position = null
        );
    }
    add_action( 'admin_menu', 'pdh_options_page' );

    // An old, outdated practice is to pass a PHP file path as the $menu_slug with a null $function, don't do this. See the top-level-menus page for an example.

    // Remove a Top-Level Menu
    // https://developer.wordpress.org/reference/functions/remove_menu_page/
    // Note that this does not prevent direct access to the pages, don't use this as a way to restrict user capabilities.
    function pdh_remove_options_page() {
        remove_menu_page( 'tools.php' );
    }
    add_action( 'admin_menu', 'pdh_remove_options_page', 99);
    // Note the high number for priority, this helps ensure that 

    // One could manually handle submission of forms on the options page, but why would you do that when you can use the Settings API?
    // See for more info on manual config if needed: https://developer.wordpress.org/plugins/administration-menus/top-level-menus/
    
    // Sub-Menus: https://developer.wordpress.org/plugins/administration-menus/sub-menus/
}