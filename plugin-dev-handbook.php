<?php
/**
 * Plugin Name: WP Plugin Dev Handbook Unofficial Reference Plugin
 * Plugin URI: https://github.com/davidshq/
 * Description: Takes the best practices and code from the handbook and provides them in a plugin along with useful notes and links.
 * Version: 0.0.2
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

 // We'll need some Hooks
 // Actions allow us to add/change WP functionality
 // Filters allow us to alter content as it is loaded/displayed to the user

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
     pdh_setup_post_type();
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
 * Creating our Own Custom Hooks - Actions and Filters
 */
 do_action();

/**
 * Removing Any Hook - Actions and Filters
 *
 */
 remove_action();

/**
 * If you are storing data for the plugin in the database use the built-in Options API.
 * If working with HTTP, use the HTTP API.
 * And for plugins (such as this one) don't forget the Plugin API.
 */


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
 * How to Structure One's Plugin Code
 *
 * The sample folder structure included in the dev handbook is:
 * plugin-name
 *  plugin-name.php
 *  uninstall.php
 *  /languages
 *  /includes
 *  /admin
 *      /admin
 *          /js
 *          /css
 *          /images
 *      /public
 *          /js
 *          /css
 *          /images
 *
 * There are three major ways in which WordPress plugins tend to be arranged:
 * 1. A single plugin file that contains functions (e.g. pdh-plugin.php is the entirety of the plugin
 *  - Example: https://github.com/GaryJones/move-floating-social-bar-in-genesis/blob/master/move-floating-social-bar-in-genesis.php
 * 2. A single plugin file that contains a class, instantiated object, and optionally functions
 *  - Example: https://github.com/norcross/wp-comment-notes/blob/master/wp-comment-notes.php
 * 3. A main plugin file with one or more class files.(#002)
 *  - Example: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * There are several different boilerplate options available that can help you get started:(#003)
 * - WPPB - Probably the most popular: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 * - WP CLI Scaffold - Pretty bare bones option: https://developer.wordpress.org/cli/commands/scaffold/plugin/
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
 * To Enqueue (Load) JavaScript Files
 */
wp_enqueue_script();

/**
 * To Enqueue (Load) CSS Files
 */
wp_enqueue_style();

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

    // Step 1: Create a function which will output the HTML.
    function pdh_options_page_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        } 
        ?>
        <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
<?php
        // output security fields for the registered setting "pdh_options"
        settings_fields('pdh_options');
        // output setting sections and their field
        // (sections are registered for "pdh", each field is registered to a specific section)
        do_settings_sections('pdh');
        // output save settings button
        submit_button('Save Settings');
        ?>
        </form>
        </div>
        <?php
    }

    // Step 2: Register our PDH Options Sub-menu, occurs during the admin_menu action hook.
    // https://developer.wordpress.org/reference/functions/add_submenu_page/
    function pdh_options_page()
    {
        add_submenu_page(
            'tools.php',  // string $parent_slug
            'PDH Options', // string $page_title
            'PDH Options', // string $menu_title
            'manage_options', // string $capability
            'pdh', // string $menu slug
            'pdh_options_page_html' // callable function = ''
        );
    }

    /**
     * Predefined Sub-Menus
     * add_dashboard_page() - index.php
     * add_posts_page() - edit.php
     * add_media_page() - upload.php
     * add_pages_page() - edit.php?post_type=page
     * add_comments_page() - edit-comments.php
     * add_theme_page() - themes.php
     * add_plugins_page() - plugins.php
     * add_users_page() - users.php
     * add_management_page() - tools.php
     * add_options_page() - options-general.php
     * add_options_page() - settings.php
     * add_links_page() - link-manager.php (requires plugin)
     * CPT - edit.php?post_type=pdh_post_type
     * Network Admin - settings.php
     */

     // Remove a Sub Menu
    // https://developer.wordpress.org/reference/functions/remove_menu_page/
    // Note that this does not prevent direct access to the pages, don't use this as a way to restrict user capabilities.
    function pdh_remove_options_page() {
        remove_menu_page( 'tools.php' );
    }
    add_action( 'admin_menu', 'pdh_remove_options_page', 99);

    // Shortcodes: https://developer.wordpress.org/plugins/shortcodes/
    // "Shortcodes are macros that can be used to perform dynamic interactions with the content. i.e. creating a gallery from images attached to the post or rendering a video."
    // "...are a valuable way of keeping content clean and semantic while allowing end users some ability to programmatically alter the presentation of their content."
    // Built-in Shortcodes: [caption] (wrap captions around content), [gallery] (show image gallery), [audio] (embed and play), [video] (embed and play), playlist (display collection of audio/video files), [embed] (wrap embedded items).
    // Shortcodes are basically filters, don't give them side effects!
    // Use init action hook
function pdh_shortcodes_init()
{
    function pdh_shortcode($atts = [], $content = null)
    {
        // do something to $content
        
        // always return
        return $content;
    }
    add_shortcode('pdh', 'pdh_shortcode'); // string $tag, callable $func

    // Enclosing shortcodes: https://developer.wordpress.org/plugins/shortcodes/enclosing-shortcodes/
    // Same code but use html-like syntax: [tag][/tag]

    // If nesting shortcodes, use do_shortcode() on final return value of the handler function.
    function pdh_shortcode2($atts = [], $content = null)
    {
        // do something to $content
        
        // run shortcode parser recursively
        $content = do_shortcode($content);

        // always return
        return $content;
    }
    add_shortcode('pdh2', 'pdh_shortcode2'); // string $tag, callable $func

    // Shortcode with parameters: https://developer.wordpress.org/plugins/shortcodes/shortcodes-with-parameters/
    // Shortcode handler function accepts 3 parameters: $atts (array of [$tag] attributes), $content (string of post content), $tag (string of the name of [$tag])
    // function pdh_shortcode($atts = [], $content = null, $tag = '') {}
    // User may enter invalid attributes, there is no way to enforce a policy on the use of attributes.
    // To gain control of how shortcodes are used:
    // 1. Declare default parameters for handler function
    // 2. Perform normalization of the key case for the attributes array with array_change_key_case(): http://php.net/manual/en/function.array-change-key-case.php
    // 3. Parse attributes using shortcode_atts() providing default values array and user $atts: https://developer.wordpress.org/reference/functions/shortcode_atts/
    // 4. Secure the output before returning: https://developer.wordpress.org/plugins/security/securing-output/
    function pdh_shortcode($atts = [], $content = null, $tag = '')
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // override default attributes with user attributes
        $pdh_atts = shortcode_atts([
            'title' => 'WordPress.org',
        ], $atts, $tag);

        // start output
        $output = '';

        // start box
        $output = '<div class="pdh-box">';

        // title
        $output = .= '<h2>' . esc_html__($pdh_atts['title'], 'pdh') . '</h2>';

        // enclosing tags
        if (!is_null($content)) {
            // secure output by executing the_content filter hook on $content
            $output .= apply_filters('the_content', $content);

            // run shortcode parser recursively
            $output .= do_shortcode($content);
        }

        // end box
        $output .= '</div>';

        // return output
        return $ouput;
    }

    function pdh_shortcodes_init()
    {
        add_shortcode('pdh', 'pdh_shortcode');
    }

    add_action('init', 'pdh_shortcodes_init');
}

    // Remove a shortcode
    // Remember to make the priority number higher for add_action() or hook into a later action hook.
    remove_shortcode(
        'pdh' // string $tag
    );

    // Check if shortcode exists
    shortcode_exists( 'pdh' );

    // TinyMCE Enhanced Shortcodes

    // Settings and Options: https://developer.wordpress.org/plugins/settings/
    // There are two core APIs for building admin interfaces - Settings API and Options API.
    // Settings API: https://developer.wordpress.org/plugins/settings/settings-api/
    // Using Settings API: https://developer.wordpress.org/plugins/settings/using-settings-api/
    // Using the Settings API allows for semi-automatic management (automatic handling of $_POST submissions and retrieval), includes security measures, and sanitizes data of admin pages containing settings forms.
    // Admin Page has 1+ sections which have 1+ fields each.
    // the form POST to wp-admin/options.php provides capabilities checking, users will need manage_options capability.

    // unregister setting
    unregister_setting()

    // Options Form Rendering
    setting_fields()
    do_settings_sections()
    do_settings_fields()

    // Errors
    add_settings_error()
    get_settings_error()
    settings_errors()

    function pdh_settings_init()
    {
        // register a new setting for "reading" page
        // creates an entry in the {$wpdb->prefix}_options table.
        // register with admin_init hook
        // https://developer.wordpress.org/reference/functions/register_setting/
        // register_setting( string $option_group, string $option_name, callabl $sanitize_callback = '' );
        register_setting('reading', 'pdh_setting_name');

        // register a new section in the "reading" page
        // https://developer.wordpress.org/reference/functions/add_settings_section/
        add_settings_section(
            'pdh_settings_section', // string $id
            'PDH Settings Section', // string $title
            'pdh_settings_section_cb', // callable $callback
            'reading' // string $page
        );

        // register a new field in the "pdh_settings_section", inside the "reading" page
        // https://developer.wordpress.org/reference/functions/add_settings_field/
        add_settings_field(
            'pdh_settings_field', // string $id
            'PDH Setting', // string $title
            'pdh_settings_field_cb', // callable $callback
            'reading', // string $page = 'default'
            'pdh_settings_section' // array $args = []
        );
    }

    /**
     * register pdh_settings_init in the admin_init action hook
     */
    add_action('admin_init', 'pdh_settings_init');

    /**
     * callback functions
     */

     // section content cb
     function pdh_settings_section_cb()
     {
         echo '<p>PDH Section Introduction.</p>';
     }

     // field content cb
     function pdh_settings_field_cb()
     {
         // get the value of the setting we've registered with register_setting()
         $setting = get_option('pdh_setting_name');
         // output the field
         ?>
         <input type="text" name="pdh_setting_name" value="<?php eco isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
         <?php
     }

     // Options
     // https://developer.wordpress.org/plugins/settings/options-api/
     // Allows creating, reading, updating, deleting of WP options.
     // Stored in {$wpdb->prefix}_options table. Find $table_prefix in wp-config.

     // Single Value Storage
     // add a new option: https://developer.wordpress.org/reference/functions/add_option/
     add_option('pdh_custom_option', 'hello world!');
     // get an option: https://developer.wordpress.org/reference/functions/get_option/
     $option = get_option('pdh_custom_option');

     // Array of Values Storage
     // May include key/value pairs
     // array of options
     // If your WP instance needs to access a lot of individual options this becomes individual transactions with the db, which is expensive, thus arrays are often preferable
     $data_r = ['title' => 'hello world!', 1, false];
     // add a new option
     add_option('pdh_custom_option_array', $data_r);
     // get an option
     $options_r = get_option('pdh_custom_option_array');
     //outut the title
     echo esc_html($options_r['title']);

     // See also: https://developer.wordpress.org/reference/functions/update_option/
     update_option()

     // https://developer.wordpress.org/reference/functions/delete_option/
     delete_option()

     // https://developer.wordpress.org/reference/functions/add_site_option/
     add_site_option()

     // https://developer.wordpress.org/reference/functions/get_site_option/
     update_site_option()

     // https://developer.wordpress.org/reference/functions/delete_site_option/
     delete_site_option()

     // See admin/plugin-pdh-custom-page.php for full settings/options example

     // Metadata: https://developer.wordpress.org/plugins/metadata/
     // Metadata is information about information. In WP it is associated with posts, users, comments, and terms.
     
}