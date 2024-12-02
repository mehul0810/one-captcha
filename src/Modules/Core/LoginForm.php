<?php
/**
 * OneCaptcha | WordPress Core Module.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Modules\Core;

use MG\OneCaptcha\Helpers;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoginForm {
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'login_form', [ $this, 'add_captcha_to_login_form' ] );
		add_filter( 'authenticate', [ $this, 'verify_on_login' ], 30, 2 );
	}

	/**
	 * Add Captcha to Login Form.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_captcha_to_login_form() : void {
		$service     = Helpers::get_active_service();
		$credentials = Helpers::get_active_service_credentials();
		$site_key    = $credentials['site_key'] ?? '';
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			API\CloudflareTurnstile::render( $site_key );
		} else if ( 'google_recaptcha' === $service ) {
			API\GoogleReCaptcha::render( $site_key );
		} else if ( 'hcaptcha' === $service ) {
			API\hCaptcha::render( $site_key );
		}
	}

	/**
	 * Verify on Login.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  WP_User|WP_Error $user
	 * @param  string           $username
	 * @param  string           $password
	 *
	 * @return WP_User|WP_Error
	 */
	public function verify_on_login( $user, $username, $password ) : WP_User|WP_Error {
		$service     = Helpers::get_active_service();
		$credentials = Helpers::get_active_service_credentials();
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			$response = API\CloudflareTurnstile::verify( $secret_key );
		} else if ( 'google_recaptcha' === $service ) {
			$response = API\GoogleReCaptcha::verify( $secret_key );
		} else if ( 'hcaptcha' === $service ) {
			$response = API\hCaptcha::verify( $secret_key );
		}

		if ( ! $response ) {
			return new \WP_Error( 'onecaptcha_error', esc_html__( 'Please complete the captcha.', 'one-captcha' ) );
		}

		return $user;
	}
}
