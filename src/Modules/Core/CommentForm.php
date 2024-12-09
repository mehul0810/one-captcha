<?php
/**
 * OneCaptcha | Comment Form Module.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Modules\Core;

use MG\OneCaptcha\Modules\BaseModule;
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
class CommentForm extends BaseModule {

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
			API\CloudflareTurnstile::render_html( $site_key );
		} else if ( 'google_recaptcha' === $service ) {
			API\GoogleReCaptcha::render_html( $site_key );
		} else if ( 'hcaptcha' === $service ) {
			API\hCaptcha::render_html( $site_key );
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
	 * Verify Cloudflare Turnstile Response.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function verify_cloudflare_turnstile_response() : void {
		// Get token from POST request.
		$token = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) ) : '';

		// Bailout, if token is empty.
		if ( empty( $token ) ) {
			wp_die( esc_html__( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}

		// Verify Cloudflare Turnstile Captcha.
		$response = API\CloudflareTurnstile::verify( $token, $this->active_service_credentials['secret_key'] );

		// Bailout, if response is false.
		if ( ! $response ) {
			wp_die( esc_html__( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}
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
		$captcha_response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';

		// Bailout, if response is empty.
		if ( empty( $captcha_response ) ) {
			wp_die( esc_html__( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}

		// Verify Google ReCaptcha.
		$response = API\GoogleReCaptcha::verify( $captcha_response, $this->active_service_credentials['secret_key'] );

		// Bailout, if response is false.
		if ( ! $response ) {
			wp_die( esc_html__( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}
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
		$captcha_response = isset( $_POST['h-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['h-recaptcha-response'] ) ) : '';

		// Bailout, if response is empty.
		if ( empty( $captcha_response ) ) {
			wp_die( esc_html__( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}

		// Verify hCaptcha.
		$response = API\hCaptcha::verify( $captcha_response, $this->active_service_credentials['secret_key'] );

		// Bailout, if response is false.
		if ( ! $response ) {
			wp_die( esc_html__( 'Captcha verification failed. Please try again.', 'onecaptcha' ) );
		}
	}
}
