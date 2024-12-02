<?php
/**
 * OneCaptcha | Comment Form Module.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Modules;

use MG\OneCaptcha\Helpers;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core Init
 *
 * @since 1.0.0
 */
class Core {

	/**
	 * Active Service
	 *
	 * @var string
	 */
	public string $active_service = '';

	/**
	 * Active Service Credentials
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var array<string>
	 */
	public array $active_service_credentials = [];

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		$this->active_service = Helpers::get_active_service();
		$this->active_service_credentials = Helpers::get_active_service_credentials();
	}

}
