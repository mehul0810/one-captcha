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

	public function register_assets() {
		$active_service = Helpers::get_active_service();

		// Load API script based on active service.
		if ( 'cloudflare_turnstile' === $active_service ) {
			// Load Cloudflare Turnstile API script.
			wp_enqueue_script( 'onecaptcha-clouflare-turnstile-api', 'https://challenges.cloudflare.com/turnstile/v0/api.js', [], ONECAPTCHA_VERSION, false );
		}
	}
}
