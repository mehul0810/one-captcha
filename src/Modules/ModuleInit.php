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
use MG\OneCaptcha\Modules\Core;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Module Init
 *
 * @since 1.0.0
 */
class ModuleInit {

	/**
	 * Active Service
	 *
	 * @var string
	 */
	protected string $active_service = '';

	/**
	 * Active Service Credentials
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var array<string>
	 */
	protected array $active_service_credentials = [];

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		$this->active_service             = Helpers::get_active_service();
		$this->active_service_credentials = Helpers::get_active_service_credentials();
	}

}
