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