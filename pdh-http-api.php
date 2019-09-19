<?php
/**
 * HTTP API
 * 
 * Handbook: https://developer.wordpress.org/plugins/http-api/
 * 
 * Can be used to interact with other APIs
 * 
 * HTTP Methods (aka verbs)
 * 
 * Whenever a HTTP request is made a method is passed to let the server determine what kind of action the client is requesting.
 * WP has built-in support for three of these:
 *  - GET - for retrieving data, most commonly used verb.
 *  - POST - send data to server for server to act upon.
 *  - HEAD - similar to GET but gathers only information about the requested content, not content itself (helpful with caching, etc.)
 *      - Always use HEAD before GET. This allows one to see if there is new data and avoid unnecessary requests.
 * 
 * HTTP Response Codes: utilizes both numeric and string response codes.
 * Code Classes
 *  - 1xx - Custom Codes
 *  - 2xx - Request was Successful
 *  - 3xx - Request was redirected to another URL
 *  - 4xx - Request failed due to client error. Usually invalid authentication or missing data
 *  - 5xx - Request failed due to a server error. Commonly missing or misconfigured config files
 * 
 * Common Codes
 *  - 200 - OK - Request was successful
 *  - 301 - Resource was moved permanently
 *  - 302 - Resource was moved temporarily
 *  - 403 - Forbidden - Usually due to an invalid authentication
 *  - 404 - Resource not found
 *  - 500 - Internal Server Error
 *  - 503 - Service Unavailable
 */

/**
 * GETting Data from an API
 *
 * Using GitHub b/c it doesn't require app registration
 *
 * Defaults for $args when not provided: method = GET, timeout = 5 (give up trying), redirection = 5
 * (how many redirects to follow), httpversion = 1.0, blocking = true (should rest of page wait to finish loading
 * until this operation is complete)
 *
 * headers = array(), body = null, cookies = array()
 *
 * The response can be any one of the wp_remote_x functions
 *
 * Handbook: https://developer.wordpress.org/reference/functions/wp_remote_get/
 * Reference: https://developer.wordpress.org/?s=wp_remote_&post_type%5B%5D=wp-parser-function
 */
 // wp_remote_get( $url, array $args );
 $response = wp_remote_get( 'https://api.github.com/users/davidshq' );

/**
 * Getting Only the Body of HTTP Response
 *
 * $response will contain all the headers, content, and other meta data about the request

 * Reference: https://developer.wordpress.org/reference/functions/wp_remote_retrieve_body/
 */
// $body = wp_remote_retrieve_body( $url);
 $body = wp_remote_retrieve_body( $response );  //


/**
 * Check if response was successful using the response code
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_remote_retrieve_response_code/
 */
 $http_code = wp_remote_retrieve_response_code( $response ); // 200 = success

/**
 * Retrieve a Specific Header
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_remote_retrieve_header
 */
// wp_remote_retrieve_header( $response, $header ); // To retrieve all headers leave out $header param
 $last_modified = wp_remote_retrieve_header( $response, 'last-modified' );

/**
 * Make a GET Request Using Basic Authentication
 *
 *
 */
 $args = array(
     'headers'  => array(
         'Authorization' => 'Basic ' . base64_encode( YOUR_USERNAME . ':' . YOUR_PASSWORD )
     )
     );
wp_remote_get( $url, $args );

/**
 * POSTing Data to an API
 *
 * The same helper methods that are available for GET (wp_remote_retrieve_body, etc.) have their correlatives with POSTing and are used in the same manner.
 *
 * Example below is fake, Github doesn't allow posting.
 *
 * TODO: Add a working demo.
 */

// Content to POST
$body = array(
    'name'      => 'Jane Smith',
    'email'     => 'some@email.com',
    'subject'   => 'API Stuff',
    'comment'   => 'Great tutorial by Ben Lobaugh.'
);

// Arguments for wp_remote_post, includes content to post above
$args = array(
    'body'      => $body,
    'timeout'   => '5',
    'redirection'   => '5',
    'httpversion'    => '1.0',
    'blocking'      => true,
    'headers'   => array(),
    'cookies'   => array()
);

$response = wp_remote_post( 'http://your-contact-form.com', $args );

/**
 * Getting HEAD Data
 *
 * There is a variety of information in the headers, pay particular attention to x-ratelimit (# requests allowed in x
 * period), x-rate-limit-remaining (# remaining in x period), content-length (in bytes), last-modified, cache-control
 * (server instructions on how client should handle caching of this content)
 *
 * Additional headers to watch for are API specific, see their documentation.
 */
$response = wp_remote_head( 'https://api.github.com/users/blobaugh' );

/**
 * To Make Any HTTP Request
 *
 * TODO: Add a real example
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_remote_request
 */
$args = array(
    'method' => 'DELETE'
);
$response = wp_remote_request( 'http://some-api.com/object/to/delete', $args );

/**
 * Caching with WP Using Transients
 *
 * Caching can be performed in a number of different levels and ways (e.g., using a CDN, static file generation,
 * through the web server itself or a reverse proxy.
 *
 * Below we will look at how to do WP native caching using Transients.
 */

// Get something to cache
$response = wp_remote_get( 'https://api.github.com/users/blobaugh' );

// Create a transient to cache what we've received
// Example below caches for one hour.
// set_transient( $transient, $value, $expiration );
set_transient( 'blobaugh_github_userinfo', $response, 60*60 );

// Pull the transient (e.g. cache) so we can read it.
$github_userinfo = get_transient( 'blobaugh_github_userinfo' );

// Make sure the transient hasn't expired
if ( false === $github_userinfo ) {
    $response = wp_remote_get( 'https://api.github.com/users/blobaugh' );
    // If transient expired, pull data from original source and create a new transient
    set_transient( 'blobaugh_github_userinfo', $response, 60*60 );
}

// Delete the transient
delete_transient( 'blobaugh_github_userinfo' );