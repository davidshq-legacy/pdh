<?php
/** WP-Cron
 *
 * Cron is a time-based task scheduling system used on UNIX systems.
 *
 * WP Cron is an adaptation of this for the web, primarily because some hosts don't offer access to UNIX cron.
 *
 * WP Cron operates by loading a list o scheduled tasks on every page view, if any of the requests is current, it runs
 * the task.
 *
 * Note: System cron runs on schedule without an external stimulus, WP-Cron requires someone to view a page of the site,
 * if traffic to the site is low this may not happen frequently enough. On the other hand, if the site receives
 * significant traffic one is adding overhead on every single request by loading and checking the list.
 *
 * On the other hand, WP-Cron continues to keep tasks in a queue until they are completed whereas cron forgets the task
 * if its time has passed for some reason. Thus WP-Cron may be a more reliable way to ensure a task is executed always.
 *
 * WP-Cron doesn't operate at specific times of the day but instead at specific intervals. Built-in intervals include
 * hourly, twicedaily, and daily.
 *
 * Handbook: https://developer.wordpress.org/plugins/cron/
 */

/**
 * Adding a Custom Interval
 *
 * Handbook: https://developer.wordpress.org/plugins/cron/understanding-wp-cron-scheduling/
 */
add_filter( 'cron_schedules', 'pdh_add_cron_interval' ); // Why is this before rather than after function as
// elsewhere?

function pdh_add_cron_interval( $schedules ) {
	$schedules['five_seconds'] = array(
		'interval'  => 5, // All intervals are in seconds
		'display'   => esc_html__( 'Every Five Seconds' ),
	);
	return $schedules;
}

/**
 * Scheduling a Recurring WP Cron Task
 *
 * Handbook: https://developer.wordpress.org/plugins/cron/scheduling-wp-cron-events/
 */

/**
 * Step 1: Create a custom hook, give hook name of function that represents task.
 */
add_action( 'pdh_cron_hook', 'pdh_cron_exec' );

/**
 * Step 2: Checking if the task has been created previously
 *
 * Reference:https://developer.wordpress.org/reference/functions/wp_next_scheduled/
 */
if ( ! wp_next_scheduled( 'pdh_cron_hook') ) {
	/**
	 * Step 3: Schedule the task
	 *
	 * Reference: https://developer.wordpress.org/reference/functions/wp_schedule_event/
	 */

	// wp_schedule_event ( $timestamp, $recurrence, $hook );
	wp_schedule_event( time(), 'five_seconds', 'pdh_cron_hook' ); // uses UNIX timestamp
}

/**
 * Removing a Task from WP-Cron
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_unschedule_event/
 */

/**
 * Step 1: Get the time when the task is next scheduled to run
 */
// $timestamp = wp_next_schedule( $hook );
$timestamp = wp_next_scheduled( 'pdh_cron_hook' );

/**
 * Step 2: Use timestamp returned above to remove hook
 */
// wp_unschedule_event ( $timestamp, $hook );
wp_unschedule_event( $timestamp, 'pdh_cron_hook' );


/**
 * REMEMBER: Use register_deactivation_hook to remove your scheduled tasks when your plugin is being deactivated.
 *
 * Reference: https://developer.wordpress.org/reference/functions/register_deactivation_hook/
 */

/**
 * Hooking WP-Cron into the System Task Scheduler
 *
 * If tasks need to occur at a specific time or if one wants to reduce load on the server one can use the
 * system cron (assuming there is access!) or a remote machine to hit wp-cron.php when desired.
 *
 * The handbook includes examples of implementing this on one's local Windows or Mac/Linux system.
 * Alternatively, @davidshq has used EasyCron, a third party service: https://www.easycron.com/ (freemium)
 *
 * Handbook: https://developer.wordpress.org/plugins/cron/hooking-wp-cron-into-the-system-task-scheduler/
 */

// Tell WP to stop running WP-Cron on every page load
define('DISABLE_WP_CRON', true);

/**
 * Force WP to show all currently scheduled tasks
 *
 * Note: This function is undocumented.
 *
 * Handbook: https://developer.wordpress.org/plugins/cron/simple-testing/
 */
function pdh_print_tasks() {
	echo '<pre>';
	print_r( _get_cron_array() );
	echo '</pre>';
}