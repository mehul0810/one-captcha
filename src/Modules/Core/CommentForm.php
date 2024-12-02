<?php
/**
 * OneCaptcha | Comment Form Module.
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
 * Comment Form Module for WordPress.
 *
 * @since 1.0.0
 */
class CommentForm extends ModuleInit {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'comment_form_after_fields', [ $this, 'add_to_comment_form' ] );
		add_action( 'pre_comment_on_post', [ $this, 'verify_on_comment_submission' ] );
	}

	/**
	 * Add Turnstile to Comment Form.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_to_comment_form() : void {
		$service     = $this->active_service;
		$credentials = $this->active_service_credentials;
		$site_key    = $credentials['site_key'] ?? '';
		$secret_key  = $credentials['secret_key'] ?? '';

		if ( 'cloudflare_turnstile' === $service ) {
			$this->render_cloudflare_turnstile_html( $site_key );
		} else if ( 'google_recaptcha' === $service ) {
			$this->render_google_recaptcha_v2_checkbox_html( $site_key );
		} else if ( 'hcaptcha' === $service ) {
			$this->render_hcaptcha_html( $site_key );
		}
		echo '<div class="cf-turnstile" data-sitekey="' . esc_attr( $site_key) . '"></div>';
	}

	/**
	 * Verify Turnstile on Comment Submission.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function verify_on_comment_submission() : void {
		$service = $this->active_service;

		if ( 'cloudflare_turnstile' === $service ) {
			$this->verify_cloudflare_turnstile_response();
		} else if ( 'google_recaptcha' === $service ) {
			$this->verify_google_recaptcha_v2_response();
		} else if ( 'hcaptcha' === $service ) {
			$this->verify_hcaptcha_response();
		}
	}

	/**
	 * Render Cloudflare Turnstile HTML.
	 *
	 * @param string $site_key Site Key.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render_cloudflare_turnstile_html( $site_key ) : void {
		echo sprintf(
			'<div class="cf-turnstile" data-sitekey="%1$s"></div>',
			$site_key
		);
	}

	/**
	 * Verify Cloudflare Turnstile Response.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function verify_cloudflare_turnstile_response() : void {
		// Get token from POST request.
		$token = $_POST['cf-turnstile-response'] ?? '';

		// Bailout, if token is empty.
		if ( empty( $token ) ) {
			wp_die( __( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}

		// Verify Cloudflare Turnstile Captcha.
		$response = API\CloudflareTurnstile::verify( $token, $this->active_service_credentials['secret_key'] );

		// Bailout, if response is false.
		if ( ! $response ) {
			wp_die( __( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}
	}

	/**
	 * Render Google reCAPTCHA v2 Checkbox HTML.
	 *
	 * @param string $site_key Site Key.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render_google_recaptcha_v2_checkbox_html( $site_key ) : void {
		echo sprintf(
			'<div class="g-recaptcha" data-sitekey="%1$s"></div>',
			$site_key
		);
	}

	/**
	 * Verify Google reCaptcha v2 Response.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function verify_google_recaptcha_v2_response() : void {
		// Get token from POST request.
		$captcha_response = $_POST['g-recaptcha-response'] ?? '';

		// Bailout, if response is empty.
		if ( empty( $captcha_response ) ) {
			wp_die( __( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}

		// Verify Google ReCaptcha.
		$response = API\GoogleReCaptcha::verify( $captcha_response, $this->active_service_credentials['secret_key'] );

		// Bailout, if response is false.
		if ( ! $response ) {
			wp_die( __( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}
	}

	/**
	 * Render hCaptcha HTML.
	 *
	 * @param string $site_key Site Key.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render_hcaptcha_html( $site_key ) : void {
		echo sprintf(
			'<div class="h-captcha" data-sitekey="%1$s" data-theme="%2$s" data-error-callback="onError"></div>',
			$site_key,
			apply_filters( 'onecaptcha_hcaptcha_theme', 'light' )
		);
	}

	/**
	 * Verify hCaptcha Response.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function verify_hcaptcha_response() : void {
		// Get token from POST request.
		$captcha_response = $_POST['h-captcha-response'] ?? '';

		// Bailout, if response is empty.
		if ( empty( $captcha_response ) ) {
			wp_die( __( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}

		// Verify hCaptcha.
		$response = API\hCaptcha::verify( $captcha_response, $this->active_service_credentials['secret_key'] );

		// Bailout, if response is false.
		if ( ! $response ) {
			wp_die( __( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}
	}
}
