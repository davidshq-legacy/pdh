<?php
/**
 * Creating and Using Shortcodes
 */

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