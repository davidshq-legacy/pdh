<?php
/**
 * Security in WordPress
 *
 * There are several major ways to secure one's WordPress code:
 * - Check User Capabilities
 * - Validate
 * - Sanitize Input
 * - Sanitize Output
 * - Create and Validate Nonces
 */

/**
 * Checking User Capabilities
 *
 * WordPress uses roles, essentially named groups, that have specific capabilities associated with them. Users are
 * assigned roles and thus gain the associated capabilities.
 *
 * Capabilities are the specific permissions assigned to a user / user role.
 *
 * You need to ensure your code only runs where users have the appropriate capabilities.
 *
 * Roles are a hierarchy and inherit all lower roles' capabilities.
 */

/**
 * Generate a Delete link based on homepage url
 *
 */
function pdh_generate_delete_link($content)
{
	// Run this code only if this is a single post that is part of the loop and part of the main query.
	if (is_single() && in_the_loop() && is_main_query()) {
		// Add query arguments: action, post
		$url = add_query_arg(
			[
				'action' => 'phd_frontend_delete',
				'post'   => get_the_ID()
			],
		home_url()
		);

		// If the capabililties are available and we are looking at a single post then we return a link to the delete
		// post url.
		return $content . ' <a href="' . esc_url($url) . '">' . esc_html__('Delete Post', 'pdh' ) . '</a>';
	}
	return null;
}

/**
 * Delete Post Request Handler
 *
 */
function pdh_delete_post()
{
	if ( isset($_GET['action']) && $_GET['action'] === 'pdh_frontend_delete') {
		// verify we have a post id
		$post_id = (isset($_GET['post'])) ? ($_GET['post']) : (null);

		// verify there is a post with such a number
		$post = get_post((int)$post_id);
		if (empty($post)) {
			return;
		}

		// Actually delete the post
		wp_trash_post($post_id);

		// Redirect to admin page
		$redirect = admin_url('edit.php');
		wp_safe_redirect($redirect);

		// we are done
		die;
	}
}

if (current_user_can('edit_others_post')) {
	/**
	 * Add the delete link to the end of the post content
	 */
	add_filter('the_content', 'pdh_generate_delete_link');

	/**
	 * Register our request handler with the init hook
	 */
	add_action('init', 'pdh_delete_post');
}

/**
 * Validating Data
 *
 * There are several ways to validate data in your code:
 * 1. Use built-in PHP functions.
 * 2. Use built-in WP functions.
 *    - Includes is_email(), term_exists(), username_exists(), validate_file() and some others that are named variants
 * of *_exists(), *_validate(), and is_*(). Confusingly, not all variants are for validation.
 * 3. Create custom functions.
 *    - If creating custom functions the name of the function should be in the form of a question: ex. is_phone_number()
 */

/**
 * Custom Function to Validate US Zip Code
 */
function pdh_is_us_zip_code($zip_code)
{
	// 1: Check if Zip Code is empty
	if (empty($zip_code)) {
		return false;
	}

	// 2: Check if more than 10 characters
	if (strlen(trim($zip_code)) > 10) {
		return false;
	}

	// 3: Check if it is in an incorrect format
	if (!preg_match('/^\d{5}(\-?\d{4})?$/', $zip_code)) {
		return false;
	}

	// If all validations have been passed.
	return true;
}


/**
 * Call validation
 */
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

/**
 * Data Sanitization
 *
 * WP includes a number of built-in data sanitization functions:
 *
 * - sanitize_email(), sanitize_file_name(), sanitize_hex_color(), sanitize_hex_color_no_hash(), sanitize_html_class(),
 * sanitize_key(), sanitize_meta(), sanitize_mime_type(), sanitize_option(), sanitize_sql_orderby(),
 * sanitize_text_field(), sanitize_title(), sanitize_title_for_query(), sanitize_title_with_dashes(), sanitize_user(),
 * esc_url_raw(), wp_filter_post_kses(), and wp_filter_nohtml_kses()
 */

$title = sanitize_text_field($_POST['title']);
update_post_meta($post->ID, 'title', $title);

/**
 * Securing Output
 *
 * This is particularly helpful in preventing cross-site scripting attacks (XSS). Built-in WP functions include:
 *  - esc_html() // Use any time a HTML element encloses a section of data being displayed.
 *  - esc_url() // Use on all URLs including those in src and href attributes.
 *  - esc_js() // For inline JS.
 *  - esc_attr() // For everything else that is printed into an HTML element's attributes.
 *
 * Note: Most WP functions properly prepare data for output and the data doesn't need to be escaped again.
 */

// Rather than using echo, use WP localization functions
// esc_html_e( $string, $text_domain
esc_html_e( 'Hello World', 'pdh' );

// Below is essentially the same as:
echo esc_html( __( 'Hello World', 'text_domain' ) );

/**
 * Other Available Helper Functions are:
 * - esc_html__()
 * - esc_html_e()
 * - esc_html_x()
 * - esc_attr__()
 * - esc_attr_e()
 * - esc_attr_x()
 */

/**
 * If the output needs to be escaped in a specific way use wp_kses (pronounced: kisses)
 *
 * Below we'll make sure that only specific HTML elements, attributes, and attribute values will occur in our output,
 * wp_kses() normalizes HTML entities.
 */
$allowed_html = [
	'a'     => [
		'href'  => [],
		'title' => [],
		],
	'br'   => [],
	'em'   => [],
	'strong' => [],
	];

$custom_content = "<div><table><tr><td><em>'Hello World!'</em><br></td></tr></table></div>";
echo wp_kses( $custom_content, $allowed_html );

// There is a wrapper function for wp_kses where $allowed_html is a set of rules used by the post content.
echo wp_kses_post( $post_content );

/**
 * Using Nonces
 *
 * Nonces are generated numbers used to verify the origin and intent of requests. Real nonces can only be used once.
 *
 * If your plugin allows users to submit data (whether on the Admin or Public side) you need to make sure that the user
 * is who they say they are AND that they have the necessary capabilities to perform the action AND actually want
 * to perform said action and haven't been tricked into it.
 *
 * When you generate the delete link you'll want to use wp_create_nonce() function to add a nonce to the link. The
 * argument passed to the function ensures that the nonce being created is unique to that particular action.
 *
 * Additional Resource: https://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
 */

/**
* Generate a delete link based on the homepage url
*/
function pdh_generate_delete_link_more($content) // TODO: Is same as above?
{
	// run only for single post page
	if (is_single() && in_the_loop() && is_main_query()) {
		// add query arguments: action, post, nonce
		$url = add_query_arg()(
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
* Delete Post Request Handler
*/
function pdh_delete_post()  // TODO: Check against previous instance, merge.
{
	if (
		isset($_GET['action']) &&
		isset($_GET['nonce']) &&
		$_GET['action'] === 'pdh_frontend_delete' &&
		wp_verify_nonce($_GET['nonce'], 'pdh_frontend_delete')
	) {
		// verify we have a post id using a ternary operator
		// useful reference on ternary operator: https://davidwalsh.name/php-shorthand-if-else-ternary-operators
		// $post_id = (condition) ? (true return value) : (false return value)
		// $post_id = (isset($_GET['post'])) ? ($_GET['post']) : (null);
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

/**
 * Add the delete link to the end of the post content
 */
if (current_user_can('edit_others_posts')) {
	add_filter('the_content', 'pdh_generate_delete_link');

	/**
	 * Register our request handler with the init hook
	 */
	add_action('init', 'pdh_delete_post');
}