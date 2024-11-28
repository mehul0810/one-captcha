<?php
/**
 * OneCaptcha | Helpers file
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha;

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
				'cloudflare_turnstile' => 'Cloudflare Turnstile',
				'google_recaptcha'     => 'Google ReCaptcha',
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
				'cloudflare_turnstile' => [
					'site_key' => [
						'label' => esc_html__( 'Site Key', 'onecaptcha' ),
						'type'  => 'text',
						'slug'  => 'site_key',
						'desc'  => esc_html__( 'The site key is used to identify your website to the captcha service.', 'onecaptcha' ),
					],
					'secret_key'  => [
						'label' => esc_html( 'Secret Key', 'onecaptcha' ),
						'type'  => 'password',
						'slug'  => 'secret_key',
						'desc'  => esc_html__( 'The site key is used to identify your website to the captcha service.', 'onecaptcha' ),
					],
				],
				'google_recaptcha'     => [
					'site_key' => [
						'label' => esc_html__( 'Site Key', 'onecaptcha' ),
						'type'  => 'text',
						'slug'  => 'site_key',
						'desc'  => esc_html__( 'The site key is used to identify your website to the captcha service.', 'onecaptcha' ),
					],
					'secret_key'   => [
						'label' => esc_html__( 'Secret Key', 'onecaptcha' ),
						'type'  => 'password',
						'slug'  => 'secret_key',
						'desc'  => esc_html__( 'The site key is used to identify your website to the captcha service.', 'onecaptcha' ),
					],
				],
				'hcaptcha'             => [
					'site_key' => [
						'label' => esc_html( 'Site Key', 'onecaptcha' ),
						'type'  => 'text',
						'slug'  => 'site_key',
						'desc'  => esc_html__( 'The site key is used to identify your website to the captcha service.', 'onecaptcha' ),
					],
					'secret_key'   => [
						'label' => esc_html__( 'Secret Key', 'onecaptcha' ),
						'type'  => 'password',
						'slug'  => 'secret_key',
						'desc'  => esc_html__( 'The site key is used to identify your website to the captcha service.', 'onecaptcha' ),
					],
				],
			]
		);
	}

	/**
	 * Display Settings Field.
	 *
	 * @param string $service Service.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return mixed
	 */
	public static function display_settings_field( $service ) {
		ob_start();
		$service_key    = sanitize_key( $service );
		$service_fields = self::get_service_fields();
		$credentials    = self::get_settings()['credentials'] ?? [];
		?>
		<div class="onecaptcha-group-fields <?php echo esc_attr( "onecaptcha-{$service_key}-group" ); ?>">
			<?php
			foreach ( $service_fields[ $service ] as $slug => $fields ) {
				$label       = $fields['label'] ?? '';
				$type        = $fields['type'] ?? 'text';
				$description = $fields['desc'] ?? '';
				$value	     = $credentials[ $service ][ $slug ] ?? '';
				?>
				<div class="onecaptcha-field">
					<label class="onecaptcha-field-label" for="<?php echo esc_html( $label ); ?>">
						<?php echo esc_html( $label ); ?>
					</label>
					<input
						type="<?php echo esc_html( $type ); ?>"
						name="onecaptcha_settings[credentials][<?php echo esc_attr( $service ); ?>][<?php echo esc_attr( $slug ); ?>]"
						value="<?php echo esc_html( $value ); ?>"
						class="onecaptcha-field-input"
					/>
					<p class="onecaptcha-field-description">
						<?php echo esc_html( $description ); ?>
					</p>
				</div>
				<?php
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}
}
