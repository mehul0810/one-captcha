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
	 * @return array
	 */
	public static function get_settings() : array {
		return get_option( 'onecaptcha_settings', [] );
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
	 * @return array
	 */
	public static function get_service_fields() : array {
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
						'label' => esc_html__( 'Secret Key', 'onecaptcha' ),
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
	public static function display_settings_field( string $service ) : mixed {
		ob_start();
		$service_key    = sanitize_key( $service );
		$service_fields = self::get_service_fields();
		$settings       = self::get_settings();
		$credentials    = $settings['credentials'] ?? [];
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

	/**
	 * Get Active Service Credentials.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_active_service_credentials() {
		$settings       = self::get_settings();
		$active_service = self::get_active_service();
		$credentials    = $settings['credentials'] ?? [];

		// Return credentials for an active service.
		return $credentials[ $active_service ] ?? [];
	}

	/**
	 * Get Active Service.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return string
	 */
	public static function get_active_service() : string {
		// Get OneCaptcha Settings.
		$settings = self::get_settings();

		// Return active service.
		return $settings['service'] ?? self::get_default_service();
	}

	/**
	 * Get Default Service.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return string
	 */
	public static function get_default_service() : string {
		return 'cloudflare_turnstile';
	}

	/**
	 * Sanitize SVG Code.
	 *
	 * @param string $svg_code SVG Code.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @static
	 *
	 * @return string
	 */
	public static function sanitize_svg_code( $svg_code ) {
		// Bailout, if SVG code is empty.
		if ( empty( $svg_code ) ) {
			return new WP_Error( 'svg_sanitization_error', esc_html__( 'Empty SVG code.', 'onecaptcha' ) );
		}

		// Use DOMDocument to parse the SVG.
		$dom = new \DOMDocument();

		// Disable external entity loading for security.
		libxml_use_internal_errors(true);
		libxml_disable_entity_loader(true);

		try {
			// Load the SVG string.
			$dom->loadXML( $svg_code, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_NOERROR | LIBXML_NOWARNING );

			// Ensure it's an SVG file.
			if ( $dom->documentElement->tagName !== 'svg' ) {
				return new WP_Error( 'svg_sanitization_error', esc_html__( 'The file is not a valid SVG.', 'onecaptcha' ) );
			}

			// Remove potentially harmful attributes.
			$allowed_attributes = [ 'xmlns', 'viewBox', 'width', 'height', 'fill' ];
			foreach ( $dom->documentElement->attributes as $attribute ) {
				if ( ! in_array($attribute->name, $allowed_attributes, true ) ) {
					$dom->documentElement->removeAttribute( $attribute->name );
				}
			}

			// Remove script elements or any non-SVG tags.
			$xpath   = new \DOMXPath( $dom );
			$scripts = $xpath->query( '//script' );

			// Remove all script tags.
			foreach ( $scripts as $script ) {
				$script->parentNode->removeChild( $script );
			}

			// Return sanitized SVG code.
			return $dom->saveXML( $dom->documentElement );
		} catch ( Exception $e ) {
			return new WP_Error( 'svg_sanitization_error', esc_html__( 'Failed to sanitize SVG code.', 'onecaptcha' ) );
		} finally {
			libxml_clear_errors();
			libxml_use_internal_errors( false );
		}
   }

   /**
	* Allowed HTML for output escaping.
	*
	* @since  1.0.0
	* @access public
	*
	* @return array
	*/
   public static function get_allowed_html() : array {
		return [
			'div'    => [
				'class' => [],
				'id'    => [],
			],
			'input'  => [
				'type'  => [],
				'name'  => [],
				'value' => [],
				'class' => [],
			],
			'label'  => [
				'for'   => [],
				'class' => [],
			],
			'span'   => [
				'class' => [],
			],
		];
   }

}
