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
		$service     = $this->active_service();
		$credentials = $this->active_service_credentials();
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
		$service = $this->active_service();

		if (
			'cloudflare_turnstile' === $service &&
			(
				(
					isset( $_POST['cf-turnstile-response'] ) &&
					empty( $_POST['cf-turnstile-response'] )
				) ||
				(
					! empty( $_POST['cf-turnstile-response'] ) &&
					false === json_decode( $_POST['cf-turnstile-response'] )['success']
				)
			)
		) {
			wp_die(__('Captcha verification failed. Please try again.', 'onecaptcha'));
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
}
