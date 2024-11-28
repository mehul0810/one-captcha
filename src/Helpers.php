<?php
/**
 * OneCaptcha | Helpers file
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace OneCaptcha;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helpers {
	/**
	 * Get One Captcha Settings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return string
	 */
	public static function get_settings() {
		return get_option( 'onecaptcha_settings' );
	}

	/**
	 * Get list of supported captcha services.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return array
	 */
	public static function get_services() {
		/**
		 * This filter is used to update the list of supported captcha services.
		 *
		 * @since 1.0.0
		 *
		 * @return array
		 */
		return apply_filters(
			'onecaptcha_get_services',
			[
				'cloudflare-turnstile' => 'Cloudflare Turnstile',
				'google-recaptcha'     => 'Google ReCaptcha',
				'hcaptcha'             => 'hCaptcha',
			]
		);
	}
}
