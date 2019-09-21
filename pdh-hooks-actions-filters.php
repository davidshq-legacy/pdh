<?php
// We'll need some Hooks
// Actions allow us to add/change WP functionality
// Filters allow us to alter content as it is loaded/displayed to the user

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
 * About Hooks
 *
 * Hooks are a way for one piece of code to interact with/modify another piece of code.
 * Hook Types: Actions, Filters
 * - Actions - allow adding data or changing how WP operates.
 *   - Require a custom function known as a callback.
 *   - Once created it must be registered with the WP hook for a specific action or filter.
 * Callback functions for Actions will run at a specific point in the execution of WP, and can perform some task, e.g.
 * echoing output to the user or inserting something into the database.
 */

function pdh_custom()
{
	// do something
}

/**
 * Adding an Action
 *
 * add_action() can accept two (optional) additional parameters:
 *  1. int $priority - Gives a priority for the given callback function's execution when there are other callback
 * functions registered on the same hook.
 *      - The default priority value is 10, it can be any positive integer.
 *      - The higher the number the lower the priority of the callback function being executed.
 *      - A callback with a priority of 10 will run before one with a priority of 11 and after one with a priority
 * of 9.
 *  2. int $accepted_args for number of arguments that will be passed back to the callback function.
 */
add_action('init', 'pdh_custom', 9, 2);

/**
 * Sometimes one might want a callback function to receive extra data related to the function it is hooking into. For
 * example, when WP saves a post and runs the save_post() hook it passes two parameters to the callback function:
 *  1. The id of the post being saved.
 *  2. The post object itself.
 *
 * do_action( 'save_post', $post->ID, $post);
 *
 * When the callback function is registered it can specify whether it wants to receive both arguments
 *
 * add_action( 'save_post', 'pdh_custom', 10, 2 );
 *
 * Then it can register the arguments in the function definitions:
 *
 * function pdh_custom($post_id, $post)
 * {
 *      // do something
 * }
 */

/**
 * Filters give you the ability to change data during the execution of WP.
 *
 * Callback functions for Filters will accept a variable, modify it, and return it.
 *
 * Filters are meant to work in an isolated manner and should never have side effects such as affecting global
 * variables and output.
 */
function pdh_filter_title($title)
{
	return 'The ' . $title . ' was filtered';
}
// Can also take additional parameters like action for $priority and $accepted_args
// add_filter( $hook, $callback, $priority, $accepted_args );
add_filter('the_title', 'pdh_filter_title');

// Add a CSS class to the body tag when certain condition is met
function pdh_css_body_class($classes)
{
	if (!is_admin()) {
		$classes[] = '';
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