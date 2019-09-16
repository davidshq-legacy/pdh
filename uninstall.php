<?php

// if uninstall.php is not called by WP, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_name = 'wporg_option';

delete_option($option_name);

// for site options in multisite
delete_site_options($option_name);

// drop a custom database table
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pdhtable");