<?php
/**
 * Namespaced functions.
 */

namespace Required\RestApiStrictOrigin;

/**
 * Inits plugin.
 */
function bootstrap() {
	add_action( 'rest_api_init', __NAMESPACE__ . '\send_cors_headers', 11 );
}

/**
 * Sends Cross-Origin Resource Sharing headers with API requests
 * for allowed origins.
 *
 * @see is_allowed_http_origin()
 */
function send_cors_headers() {
	// Remove default headers.
	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );

	// Set the header only if origin is allowed.
	add_filter(
		'rest_pre_serve_request',
		function( $value ) {
			$origin = get_http_origin();

			if ( is_allowed_http_origin( $origin ) ) {
				header( 'Access-Control-Allow-Origin: ' . $origin );
				header( 'Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE' );
				header( 'Access-Control-Allow-Credentials: true' );
				header( 'Vary: Origin' );
			}

			return $value;
		}
	);
}
