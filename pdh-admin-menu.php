<?php
/**
 * Administration Menus
 *
 * Handbook: https://developer.wordpress.org/plugins/administration-menus/
 * Reference: https://developer.wordpress.org/theme/functionality/navigation-menus/
 */

/**
 * Add a Top Level Menu
 *
 * Handbook: https://developer.wordpress.org/plugins/administration-menus/top-level-menus/
 * Reference: https://developer.wordpress.org/reference/functions/add_menu_page/
 */

// Step 1: Create function to output HTML for options page.
function pdh_options_page_html() {
    // check user capabilities
}
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
// Step 2: Registering PDH menu using admin_menu action hook.(#
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

/**
 * Remove a Top-Level Menu
 *
 * Note: Removing a top-level menu does not restrict direct access to the page. Don't use this as a way to restrict
 * user capabilities!
 *
 * Reference: https://developer.wordpress.org/reference/functions/remove_menu_page/
 */
function pdh_remove_options_page() {
	// Note the high number for priority, this helps ensure... TODO: What should the rest of this be?
	remove_menu_page( 'tools.php' );
}
add_action( 'admin_menu', 'pdh_remove_options_page', 99);

/**
 * Create a Sub-Menu
 *
 * Handbook: https://developer.wordpress.org/plugins/administration-menus/sub-menus/
 */