<?php
/**
 * OneCaptcha | Module Init
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace MG\OneCaptcha\Modules;

use MG\OneCaptcha\Modules;

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
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		// WordPress Core.
		new Modules\Core\CommentForm();
		new Modules\Core\LoginForm();
		new Modules\Core\RegisterForm();
		new Modules\Core\LostPassword();
	}

}
