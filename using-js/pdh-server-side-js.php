<?php
/**
 * Server Side PHP and Enqueueing
 *
 * The way WP handles AJAX is a bit different from the way many developers are used to coding AJAX functionality. The
 * two major differences are:
 *
 * 1. One needs to use enqueue scripts in order to get meta links to appear correctly in the page's head section.
 * 2. All AJAX requests should be sent through wp-admin/admin-ajax.php.
 *
 * Handbook: https://developer.wordpress.org/plugins/javascript/enqueuing/
 */

/**
 * Enqueueing a Script
 *
 * You must enqueue your script and list its location and dependencies. It cannot be loaded from plugin page directly,
 * it needs to use one of a few specific admin hooks.
 *  - Admin Pages: admin_enqueue_scripts
 *  - Front End Pages: wp_enqueue_scripts
 *  - Login Pages: login_enqueue_scripts
 *
 * TODO: Go through all of this again.
 *
 * Handbook: https://developer.wordpress.org/plugins/javascript/enqueuing/
 * Reference: https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 */
add_action( 'admin_enqueue_scripts', 'pdh_enqueue' );
function pdh_enqueue( $hook ) { // Named function not closure, this is to avoid incompatibility with earlier versions of PHP

	/**
	 * Enqueuing a Script for Real
	 */
	if( 'pdh_settings.php' != $hook ) return;
	// wp_enqueue_script( $arbitrary_tag_or_handle, plugins_url( $path_from_current_file, __FILE__ ), array( $dependencies );
	wp_enqueue_script( 'pdh-ajax-script',
		plugins_url( '/js/pdh-jquery.js', __FILE__ ),
		array( 'jquery' )
	);

	/**
	* Registering A Script
	 *
	 * Creates a handle for your script that makes it easy to reference from code outside your script.
	 *
	 * Reference: https://developer.wordpress.org/reference/functions/wp_register_script/
	 */
	// $variable = wp_create_nonce( $name_of_nonce );
	$title_nonce = wp_create_nonce( 'title_nonce' );

	// wp_localize_script( $handle_for_script, $global_ajax_object, array( 'ajax_url' => admin_url( 'admin-ajax.php ),
	// 'nonce' => $variable_nonce, ) );
	wp_localize_script( 'ajax-script', 'pdh_ajax_obj', array(
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'nonce'     => $title_nonce,
	) );

}

add_action( 'wp_ajax_pdh_tag_count', 'pdh_ajax_handler' );
/**
 * The AJAX Handler
 *
 * In the AJAX handler we will:
 * 1. Check that the nonce is valid.
 * 2. Add/Update the data returned by jQuery to the user's meta.
 * 3. Query the DB for the count of tags matching the user's data.
 * 4. Send the tag count result from the query back to jQuery.
 *
 * Note: One can communicate data between PHP and JavaScript/jQuery in a number of formats including:
 *  - XML: See function pdh_ajax_xml_handler below.
 * - JSON: See links below.
 * - Any Shared Data Transfer Medium (CSV, Tab Delimited, raw stream)
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_send_json/
 * Reference: https://developer.wordpress.org/reference/functions/wp_send_json_success/
 * Reference: https://developer.wordpress.org/reference/functions/wp_send_json_error/
 */
function pdh_ajax_handler() {
	// check_ajax_referer( $variable_nonce );
	check_ajax_referer( 'title_nonce' );

	// Add/Update the submitted data to the user's meta
	// https://developer.wordpress.org/reference/functions/update_user_meta/
	update_user_meta( get_current_user_id(), 'title_preference', $_POST['title']);

	// Query to get the tag count
	$args = array(
		'tag'   => $_POST['title'],
	);
	$the_query = new WP_Query( $args );
		// We use the JSON specific functions rather than the wp_ajax_response used later in the code because
		// these are newer/better replacements for wp_ajax_response when working with jQuery.
		wp_send_json( $_POST['title'] . ' (' . $the_query->post_count . ') ' );

	// If using the WP_AJAX_RESPONSE or wp_send_json* functions, wp_die() is called automatically.
	// Otherwise, call wp_die() manually to ensure your ajax handler dies upon completion.
	wp_die();
}

/**
 * Using XML as the Data Transfer Medium
 *
 * TODO: Rather than returning a raw stream, return XML.
 *
 * Reference: https://developer.wordpress.org/reference/classes/wp_ajax_response/
 */
function pdh_ajax_xml_handler() {
	check_ajax_referer( 'title_nonce' );
	update_user_meta( get_current_user_id(), 'title_preference', $_POST['title'] );
	$args = array(
		'tag'   => $_POST['title'],
	);
	$the_query = new WP_Query( $args );
	echo $_POST['title'].' ('.$the_query->post_count.') ';
	wp_die();
}

/**
 * Using the Heartbeat API
 *
 * The Heartbeat API is a server polling API built into WP. When a client has successfully loaded the page some
 * client-side heartbeat code begins running every 15-60 seconds.
 *
 * Each time client-side heartbeat runs it gathers any data to send via a jQuery event and sends this to the server,
 * then waits for a response.
 *
 * The server receives the data using an admin-ajax handler, prepares a response, filters the response, and returns the
 * data in JSON format.
 *
 * The client receives this data and fires a final jQuery event to indicate the data has been received.
 *
 * The process can be summarized as:
 *
 *  1. JS heartbeat-send Event: Add additional fields to the data to be sent.
 *  2. Server Side heartbeat_received Filter: Detect sent fields in PHP and add additional response fields.
 *  3. JS heartbeat-tick: Process returned  data in JS.
 *
 * To use the heartbeat API one needs:
 *
 *  1. send and receive callbacks in JS
 *  2. a server side filter to process the received data in PHP.
 */

/**
 * Client-Side JavaScript: Send Data to Server
 */
?>
<script>
	jQuery( document ).on( 'heartbeat-send', function ( event, data ) {
	    // Add additional data to heartbeat data.
		data.pdh_customfield = 'some_data';
	});
</script>

<?php
/**
 * Server Side PHP: Receiving and Responding to Data
 */

// Add filter to receive hook, and specify we need two parameters.
add_filter( 'heartbeat_received', 'pdh_receive_heartbeat', 10, 2 );

/**
 * Server Side PHP Receive Heartbeat data and respond.
 *
 * Processes data received via a Heartbeat request and returns additional data to the front end.
 *
 * @param   array   $response   Heartbeat response data to pass back to front end.
 * @param   array   $data       Data received from the frontend (unslashed).
 */
function pdh_receive_heartbeat( $response, $data ) {
	// Return empty response if no data received.
	if ( empty( $data['pdh_customfield'] ) ) {
		return $response;
	}

	// Calculate our data and pass it back. In this example we hash it.
	$received_data = $data['pdh_customfield'];

	$response['pdh_customfield_hashed'] = sha1( $received_data);
	return $response;
}

/**
 * Client-Side JavaScript: Processing Response
 */
?>
<script>
	jQuery( document ).on( 'heartbeat-tick', function ( event, data ) {
	    // Check for data, use if available.
		if ( ! data.pdh_customfield_hashed ) {
		    return;
		}

		alert( 'The hash is' + data.pdh_customfield_hashed );
	});
</script>

// TODO: update with https://developer.wordpress.org/plugins/javascript/summary/