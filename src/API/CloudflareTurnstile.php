<?php
/**
 * OneCaptcha | Cloudflare Turnstile API.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace OneCaptcha\API;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CloudflareTurnstile {
	/**
	 * Verify Site.
	 *
	 * @param string $token Token.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function verify( $token, $key ) {
		$response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'body' => [
                'secret'   => $key,
                'response' => $token,
            ],
        ]);

		// Bailout, if response is an error.
        if ( is_wp_error( $response ) ) {
            return false;
        }

        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Bailout, if response body is empty.
		if ( empty( $response_body ) ) {
			return false;
		}

		// Return response status.
		return $response_body['success'] ?? false;
	}

	/**
	 * Render HTML.
	 *
	 * @param string $site_key Site Key.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function render_html( $site_key ) : void {
		echo sprintf(
			'<div class="cf-turnstile" data-sitekey="%1$s"></div>',
			esc_html( $site_key )
		);
	}
}
