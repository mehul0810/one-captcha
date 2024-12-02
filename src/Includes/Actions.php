<?php
/**
 * OneCaptcha | Frontend Actions.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Includes;

use MG\OneCaptcha\Helpers;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Actions {
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @access public
     */
    public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
    }

	/**
	 * Register Assets.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_assets() {
		$active_service = Helpers::get_active_service();

		// Load API script based on active service.
		if ( 'cloudflare_turnstile' === $active_service ) {
			// Load Cloudflare Turnstile API script.
			wp_enqueue_script( 'onecaptcha-clouflare-turnstile-api', 'https://challenges.cloudflare.com/turnstile/v0/api.js', [], ONECAPTCHA_VERSION, false );
		} else if ( 'google_recaptcha' === $active_service ) {
			// Load Google reCAPTCHA API v3 script.
			wp_enqueue_script( 'onecaptcha-google-recaptcha-api', 'https://www.google.com/recaptcha/api.js', [], ONECAPTCHA_VERSION, false );
		} else if ( 'hcaptcha' === $active_service ) {
			// Load hCaptcha API script.
			wp_enqueue_script( 'onecaptcha-hcaptcha-api', 'https://js.hcaptcha.com/1/api.js', [], ONECAPTCHA_VERSION, false );
		}
	}
}
