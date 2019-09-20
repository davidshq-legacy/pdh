<?php
/**
 * Using Uninstall.php
 *
 * This file can be included in the base folder of a plugin and will automatically be run by WP when your plugin is
 * uninstalled.
 *
 * Handbook: https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 */

// if uninstall.php is not called by WP, die
if ( !defined('WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

// Provide option to be removed
$option_name = 'pdh_option';

// Delete the option
delete_option($option_name);

// for site options in multisite
delete_site_options($option_name);

// drop a custom database table
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pdhtable");