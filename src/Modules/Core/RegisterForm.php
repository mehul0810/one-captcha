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

class RegisterForm extends BaseModule{
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
			API\CloudflareTurnstile::render_html( $site_key );
		} else if ( 'google_recaptcha' === $service ) {
			API\GoogleReCaptcha::render_html( $site_key );
		} else if ( 'hcaptcha' === $service ) {
			API\hCaptcha::render_html( $site_key );
		}
	}

	/**
	 * Verify on Register.
	 *
	 * @param  WP_Error $errors
	 * @param  string   $sanitized_user_login
	 * @param  string   $user_email
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return \WP_Error
	 */
	public function verify_on_register( $errors, $sanitized_user_login, $user_email ) : \WP_Error {
		$service     = Helpers::get_active_service();
		$credentials = Helpers::get_active_service_credentials();
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			$challenge = $_POST['g-recaptcha-response'] ?? '';
			$response  = API\CloudflareTurnstile::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				$errors->add( 'onecaptcha_error', esc_html__( 'Please complete the CAPTCHA challenge.', 'onecaptcha' ) );
			}
		} else if ( 'google_recaptcha' === $service ) {
			$challenge = $_POST['g-recaptcha-response'] ?? '';
			$response  = API\GoogleReCaptcha::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				$errors->add( 'onecaptcha_error', esc_html__( 'Please complete the CAPTCHA challenge.', 'onecaptcha' ) );
			}
		} else if ( 'hcaptcha' === $service ) {
			$challenge = $_POST['h-captcha-response'] ?? '';
			$response  = API\hCaptcha::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				$errors->add( 'onecaptcha_error', esc_html__( 'Please complete the CAPTCHA challenge.', 'onecaptcha' ) );
			}
		}

		return $errors;
	}
}
