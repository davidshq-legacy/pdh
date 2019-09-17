<?php 
// Post Metadata: https://developer.wordpress.org/plugins/metadata/managing-post-metadata/

// add_post_meta( post_id, meta_key, meta_value, unique_flag )
// meta_value can be string, integer, or array - automatically serialized if last before storing in db.
// unique determines whether there can be multiple meta_values associated with a single meta_key and a single post.
$something = 'Hello World!';
add_post_meta($id, 'pdh_something', $something)


// update_post_meta( post_id, meta_key, meta_value, unique )
// note that if a key exists, it updates the value - if it does not exist it creates it.

// delete_post_meta( post_id, meta_key, optional_meta_value )
$something = 'Hello world!'


// Meta values are passed through the stripslashes() function when being stored to db, need to be aware of this as JSON, etc. may include escaped slashes.
// Can work around this by using wp_slash()
$escaped_json = '{"key":"value with \"escaped quotes\""}';
update_post_meta($id, 'double_escaped_json', wp_slash($escaped_json));
$fixed = get_post_meta($id, 'double_escaped_json', true);

// To hide custom fields (e.g., not meant to be seen/changed by end users) prefix a _ and WP will not display,
// useful when looking to display using the add_meta_box function also.
add_post_meta(68, '_color', 'red', true);

// Array meta values are also not displayed.

// Custom Meta Boxes: https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
// Default meta boxes on edit screen: Editor, Publish, Categories, Tags, etc.
// Plugins can add custom meta boxes to an edit screen of any post type.

// Note, need to refine this code so that it secures the input, checks user capabilities, and internationalizes - this code does not do any of these currently.

// Create a meta box
// Using Procedural Technique
function pdh_add_custom_box()
{
    $screens = ['post', 'pdh_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'pdh_box_id',       // Unique ID
            'Custom Meta Box Title', // Box Title
            'pdh_custom_box_html', // Content callback, must be of type callable.
            $screen
        );
    }
}
// Add custom meta box to add_meta_boxes hook
add_action('add_meta_boxes', 'pdh_add_custom_box');

// Add the contents of the custom meta box
// No submit button, included inside edit screen's form tags, transferred via POST when user clicks on Publish or Update.
function pdh_custom_box_html($post)
{
    // if there is saved data, retrieve it
    $value = get_post_meta($post->ID, '_pdh_meta_key', true);
    ?>
    <label for="pdh_field">Description for this field.</label>
    <select name="pdh_field" id="pdh_field" class="postbox">
        <option value="">Select something...</option>
        <!-- We display the data if available, show default msg otherwise. 
            https://developer.wordpress.org/reference/functions/selected/
        -->
        <option value="something"><?php selected($value, 'something'); ?>>Something</option>
        <option value="else"><?php selected($value, 'elese'); >>Else</option>
    </select>
    <?php
}

// We need to hook into an action_hook to save values.
// save_post can be used, just remember that it may upate more than once for a single update event.
function pdh_save_postdata($post_id)
{
    if (array_key_exists('pdh_field', $_POST)) {
        update_post_meta(
            $post_id,
            'pdh_meta_key',
            $_POST['pdh_field']
        );
    }
}
add_action('save_post', 'pdh_save_postdata');

// Removing a Meta Box: https://developer.wordpress.org/reference/functions/remove_meta_box/
// Passed parameters must exactly match those used to add meta box
remove_meta_box(
    'pdh_box_id',       // Unique ID
    'Custom Meta Box Title', // Box Title
    'pdh_custom_box_html', // Content callback, must be of type callable.
    'post' // You'd another to remove pdh_cpt
)

// End Create Metabox Using Procedural Techique
// Begin OOP Create Metabox
// Introduction to OOP: https://wpshout.com/courses/object-oriented-php-for-wordpress-developers/complete-guide-to-object-oriented-php/
// Short explanation of Abstract Class: https://stackoverflow.com/questions/2558559/what-is-abstract-class-in-php
// PHP Documentation: https://www.php.net/manual/en/language.oop5.abstract.php
abstract class PDH_Meta_Box
{
    // On Static: https://wpshout.com/courses/object-oriented-php-for-wordpress-developers/php-static-methods-in-depth/
    public static function add()
    {
        $screens = ['post', 'pdh_cpt'];
        foreach ($screens as $screen) {
            add_meta_box(
                'pdh_box_id', // Unique ID
                'Custom Meta Box Title', // Box Title
                [self::class, 'html'], // Content callback, must be of type callback
                $screen                 // Post type, any reason we are calling this "screen"?
            );
        }
    }

    public static function save($post_id)
    {
        if (array_key_exists('pdh_field', $_POST)) {
            update_post_meta(
                $post_id,
                '_pdh_meta_key',
                $POST['pdh_field']
            );
        }
    }

    public static function html($post)
    {
        $value = get_post_meta($post->ID, '_pdh_meta_key', true);
        ?>
        <label for="pdh_field">Description for this field</label>
        <select name="pdh_field" id="pdh_field" class="postbox">
            <option value="">Select something...</option>
            <option value="something" <?php selected($value, 'something'); ?>Something</option>
            <option value="lse" <?php selected($value, 'else'); ?>>Else</option>
        </select>
        <?php
    }

    add_action('add_meta_boxes', ['PDH_Meta_Box', 'add']);
    add_action('save_post', ['PDH_Meta_Box', 'save']);

function pdh_meta_box_script()
{
    // get current admin screen, or null
    $screen = get_current_screen();
    // verify admin screen object
    if (is_object($screen)) {
        // enqueue only for specific post types
        if (in_array($screen->post_type, ['post', 'pdh_cpt'])) {
            // enqueue script
            wp_enqueue_script('pdh_meta_box_script', plugin_dir_url(__FILE__) . 'admin/js/pdh_metadata.js', ['jquery']);
            // localize script, create a custom js object
            wp_localize_script(
                'pdh_meta_box_script',
                'pdh_meta_box_obj',
                [
                    'url' => admin_url('admin-ajax.php'),
                ]
                );
        }
    }
}
add_action('admin_enqueue_scripts', 'pdh_meta_box_scripts');

// Handler
function pdh_meta_box_ajax_handler()
{
    if (isset($_POST['pdh_field_value'])) {
        switch ($_POST['pdh_field_value']) {
            case 'something':
                echo 'success';
                break;
            default:
                echo 'failure';
                break;
        }
    }
    // ajax handlers must die
    die;
}
// wp_ajax_ is the prefix, pdh_ajax_change is the action we've used in the client side code
// Where?
add_action('wp_ajax_pdh_ajax_change', 'pdh_meta_box_ajax_handler');

// Rendering Post Metadata: https://developer.wordpress.org/plugins/metadata/rendering-post-metadata/

// https://developer.wordpress.org/reference/functions/get_post_meta/
/** get_post_meta(
  *  int $post_id,
  *  string $key = '',
  *  bool $single = false
  * );
  */
$pdh_meta_value = get_post_meta(get_the_ID(), 'pdh_meta_key');

// https://developer.wordpress.org/reference/functions/get_post_custom/
// get_post_custom( int $post_id );
$meta_array = get_post_custom(get_the_ID());
