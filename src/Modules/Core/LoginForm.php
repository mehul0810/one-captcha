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
use MG\OneCaptcha\API;
use MG\OneCaptcha\Modules\BaseModule;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LoginForm extends BaseModule {
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'login_form', [ $this, 'add_captcha_to_login_form' ] );
		add_filter( 'authenticate', [ $this, 'verify_on_login' ], 30, 3 );
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
			API\CloudflareTurnstile::render_html( $site_key );
		} else if ( 'google_recaptcha' === $service ) {
			API\GoogleReCaptcha::render_html( $site_key );
		} else if ( 'hcaptcha' === $service ) {
			API\hCaptcha::render_html( $site_key );
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
	 * @return \WP_User|\WP_Error
	 */
	public function verify_on_login( $user, $username, $password ) : \WP_User|\WP_Error {
		// Bailout, if user is already logged in.
		if ( is_a( $user, 'WP_User' ) ) {
			return $user;
		}

		$service     = Helpers::get_active_service();
		$credentials = Helpers::get_active_service_credentials();
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			$token    = $_POST['cf-turnstile-token'] ?? '';
			$response = API\CloudflareTurnstile::verify( $token, $secret_key );
		} else if ( 'google_recaptcha' === $service ) {
			$token    = $_POST['g-recaptcha-response'] ?? '';
			$response = API\GoogleReCaptcha::verify( $token, $secret_key );
		} else if ( 'hcaptcha' === $service ) {
			$token    = $_POST['h-captcha-response'] ?? '';
			$response = API\hCaptcha::verify( $token, $secret_key );
		}

		// Bailout, if response is empty.
		if ( ! $response ) {
			return new \WP_Error( 'onecaptcha_error', esc_html__( 'Please complete the captcha.', 'onecaptcha' ) );
		}

		// Return user.
		return $user;
	}
}
