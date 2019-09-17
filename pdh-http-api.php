<?php
/**
 * HTTP API
 * 
 * https://developer.wordpress.org/plugins/http-api/
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

 // GETting Data from an API
 // https://developer.wordpress.org/reference/functions/wp_remote_get/
 // Using GitHub b/c it doesn't require app registration
 // wp_remote_get( $url, array $args );
 // Defaults for $args when not provided: method = GET, timeout = 5 (give up trying), redirection = 5 (how many redirects to follow), httpversion = 1.0, blocking = true (should rest of page wait to finish loading until this operation is complete),
 // headers = array(), body = null, cookies = array()
 $response = wp_remote_get( 'https://api.github.com/users/davidshq' );


 // $response will contain all the headers, content, and other meta data about the request

 // One can get only the body: https://developer.wordpress.org/reference/functions/wp_remote_retrieve_body/
 $body = wp_remote_retrieve_body( $response );  // $response is any one of the wp_remote_x functions: https://developer.wordpress.org/?s=wp_remote_&post_type%5B%5D=wp-parser-function

 // If one is only getting the body one can do $body = wp_remote_retrieve_Body( wp_remote_get( $url ));

 // Check if response was successful using response code: https://developer.wordpress.org/reference/functions/wp_remote_retrieve_response_code/
 $http_code = wp_remote_retrieve_response_code( $response ); // 200 = success

 // Retrieve a specific header: https://developer.wordpress.org/reference/functions/wp_remote_retrieve_header
 // wp_remote_retrieve_header( $response, $header );
 // To retrieve all headers leave out $header param
 $last_modified = wp_remote_retrieve_header( $response, 'last-modified' );

 // Perform GET with Basic Authentication
 $args = array(
     'headers'  => array(
         'Authorization' => 'Basic ' . base64_encode( YOUR_USERNAME . ':' . YOUR_PASSWORD )
     )
     );
wp_remote_get( $url, $args );

// POSTing data to an API
// The same helper methdos that are available for GET (wp_remote_retrieve_body() etc.) are also available for all of the HTTP method calls and used in the same manner.
// wp_remote_post()
// Github doesn't allow POSTing, so we are faking, add a real example
$body = array(
    'name'      => 'Jane Smith',
    'email'     => 'some@email.com',
    'subject'   => 'API Stuff',
    'comment'   => 'Great tutorial by Ben Lobaugh.'
);

$args = array(
    'body'      => $body,
    'timeout'   => '5',
    'redirection'   => '5',
    'httpvrsion'    => '1.0',
    'blocking'      => true,
    'headers'   => array(),
    'cookies'   => array()
);

$response = wp_remote_post( 'http://your-contact-form.com', $args );

// Getting HEAD Data
// Watch for x-ratelimit-limit (# requests allowed in x period), x-rate-limit-remaining (# requests remaining in x period), content-length (in bytes), last-modified, cache-control (how should client handle caching)
// Read documentation of each specific API for other headers to watch for
$response = wp_remote_head( 'https://api.github.com/users/blobaugh' );