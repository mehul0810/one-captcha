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

class RegisterForm {
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'register_form', [ $this, 'add_captcha_to_register_form' ] );
		add_filter( 'registration_errors', [ $this, 'verify_on_register' ], 10, 3 );
	}

	/**
	 * Add Captcha to Login Form.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_captcha_to_register_form() : void {
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
	 * Verify on Register.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  WP_Error $errors
	 * @param  string   $sanitized_user_login
	 * @param  string   $user_email
	 *
	 * @return WP_Error
	 */
	public function verify_on_register( $errors, $sanitized_user_login, $user_email ) : WP_Error {
		$service     = Helpers::get_active_service();
		$credentials = Helpers::get_active_service_credentials();
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			$challenge = $_POST['g-recaptcha-response'] ?? '';
			$response  = API\CloudflareTurnstile::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				$errors->add( 'onecaptcha_error', esc_html__( 'Please complete the CAPTCHA challenge.', 'one-captcha' ) );
			}
		} else if ( 'google_recaptcha' === $service ) {
			$challenge = $_POST['g-recaptcha-response'] ?? '';
			$response  = API\GoogleReCaptcha::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				$errors->add( 'onecaptcha_error', esc_html__( 'Please complete the CAPTCHA challenge.', 'one-captcha' ) );
			}
		} else if ( 'hcaptcha' === $service ) {
			$challenge = $_POST['h-captcha-response'] ?? '';
			$response  = API\hCaptcha::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				$errors->add( 'onecaptcha_error', esc_html__( 'Please complete the CAPTCHA challenge.', 'one-captcha' ) );
			}
		}

		return $errors;
	}
}
