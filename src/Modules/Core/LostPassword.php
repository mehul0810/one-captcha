<?php
/**
 * OneCaptcha | Form Module - Lost Password.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Modules\Core;

use MG\OneCaptcha\Modules\ModuleInit;
use MG\OneCaptcha\API;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lost Password - Form Module for WordPress.
 *
 * @since 1.0.0
 */
class LostPassword extends ModuleInit {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'lostpassword_form', [ $this, 'add_captcha_to_lost_password_form' ] );
		add_action( 'lostpassword_post', [ $this, 'verify_on_lost_password' ] );
	}

	/**
	 * Add Captcha to Lost Password Form.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_captcha_to_lost_password_form() : void {
		$service     = $this->active_service;
		$credentials = $this->active_service_credentials;
		$site_key    = $credentials['site_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			API\CloudflareTurnstile::render_html( $site_key );
		} else if ( 'google_recaptcha' === $service ) {
			API\GoogleReCaptcha::render_html( $site_key );
		} else if ( 'hcaptcha' === $service ) {
			API\hCaptcha::render_html( $site_key );
		}
	}

	/**
	 * Verify on Lost Password.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  string $user_login User Login.
	 *
	 * @return void
	 */
	public function verify_on_lost_password( $user_login ) : void {
		$service     = $this->active_service;
		$credentials = $this->active_service_credentials;
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			$challenge = $_POST['g-recaptcha-response'] ?? '';
			$response  = API\CloudflareTurnstile::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				wp_die( esc_html__( 'Captcha verification failed.', 'onecaptcha' ) );
			}
		} else if ( 'google_recaptcha' === $service ) {
			$challenge = $_POST['g-recaptcha-response'] ?? '';
			$response  = API\GoogleReCaptcha::verify( $challenge, $secret_key );

			if ( ! $response->success ) {
				wp_die( esc_html__( 'Captcha verification failed.', 'onecaptcha' ) );
			}
		} else if ( 'hcaptcha' === $service ) {
			$challenge = $_POST['h-captcha-response'] ?? '';
			$response  = API\hCaptcha::verify( $challenge, $secret_key );

			if ( ! isset( $response->success ) ) {
				wp_die( esc_html__( 'Captcha verification failed.', 'onecaptcha' ) );
			}
		}
	}
}
