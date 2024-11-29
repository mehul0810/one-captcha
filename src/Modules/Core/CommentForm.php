<?php
/**
 * OneCaptcha | Comment Form Module.
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

/**
 * Comment Form Module for WordPress.
 *
 * @since 1.0.0
 */
class CommentForm {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'comment_form_after_fields', [ $this, 'add_turnstile_to_comment_form' ] );
		add_action( 'pre_comment_on_post', [ $this, 'verify_turnstile_on_comment_submission' ] );
	}

	/**
	 * Add Turnstile to Comment Form.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_turnstile_to_comment_form() : void {
		$credentials = Helpers::get_active_service_credentials();
		$site_key    = $credentials['site_key'] ?? '';
		$secret_key  = $credentials['secret_key'] ?? '';

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
	public function verify_turnstile_on_comment_submission() : void {
		if (
			(
				isset( $_POST['cf-turnstile-response'] ) &&
				empty( $_POST['cf-turnstile-response'] )
			) ||
			(
				! empty( $_POST['cf-turnstile-response'] ) &&
				false === json_decode( $_POST['cf-turnstile-response'] )['success']
			)
		) {
			wp_die(__('Captcha verification failed. Please try again.', 'onecaptcha'));
		}
	}
}
