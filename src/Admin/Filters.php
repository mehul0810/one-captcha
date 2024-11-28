<?php
/**
 * OneCaptcha | Admin Filters.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Admin;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Filters {
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @access public
     */
    public function __construct() {
        add_filter( 'plugin_action_links_' . ONECAPTCHA_PLUGIN_BASENAME, [ $this, 'add_plugin_action_links' ] );
    }

    /**
	 * Plugin page action links.
	 *
	 * @param array $actions An array of plugin action links.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $actions ) {
		$new_actions = [
			'settings' => sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url( 'options-general.php?page=onecaptcha' ),
				esc_html__( 'Settings', 'onecaptcha' )
			),
			'support'  => sprintf(
				'<a target="_blank" href="%1$s">%2$s</a>',
				esc_url_raw( 'https://wordpress.org/support/plugin/onecaptcha/' ),
				esc_html__( 'Support', 'onecaptcha' )
			),
		];

		return array_merge( $new_actions, $actions );
	}
}
