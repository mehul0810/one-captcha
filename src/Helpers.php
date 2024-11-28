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

	/**
	 * Get list of supported captcha service fields.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function get_service_fields() {
		/**
		 * This filter is used to update the list of supported captcha services.
		 *
		 * @since 1.0.0
		 *
		 * @return array
		 */
		return apply_filters(
			'onecaptcha_get_service_fields',
			[
				'cloudflare-turnstile' => [
					'site_key' => [
						'label' => esc_html__( 'Site Key', 'onecaptcha' ),
						'type'  => 'text',
					],
					'secret_key'  => [
						'label' => esc_html( 'Secret Key', 'onecaptcha' ),
						'type'  => 'password',
					],
				],
				'google-recaptcha'     => [
					'site_key' => [
						'label' => esc_html__( 'Site Key', 'onecaptcha' ),
						'type'  => 'text',
					],
					'secret_key'   => [
						'label' => esc_html__( 'Secret Key', 'onecaptcha' ),
						'type'  => 'password',
					],
				],
				'hcaptcha'             => [
					'site_key' => [
						'label' => esc_html( 'Site Key', 'onecaptcha' ),
						'type'  => 'text',
					],
					'secret_key'   => [
						'label' => esc_html__( 'Secret Key', 'onecaptcha' ),
						'type'  => 'password',
					],
				],
			]
		);
	}
}
