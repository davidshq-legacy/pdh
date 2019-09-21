<?php
/**
 * Creating an Admin Options Page(#007)
 *
 * Handbook: https://developer.wordpress.org/plugins/administration-menus/top-level-menus/
 */

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