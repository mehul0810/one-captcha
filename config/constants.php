<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin version in SemVer format.
if ( ! defined( 'ONECAPTCHA_VERSION' ) ) {
	define( 'ONECAPTCHA_VERSION', '1.0.0' );
}

// Define plugin root File.
if ( ! defined( 'ONECAPTCHA_PLUGIN_FILE' ) ) {
	define( 'ONECAPTCHA_PLUGIN_FILE', dirname( dirname( __FILE__ ) ) . '/one-captcha.php' );
}

// Define plugin basename.
if ( ! defined( 'ONECAPTCHA_PLUGIN_BASENAME' ) ) {
	define( 'ONECAPTCHA_PLUGIN_BASENAME', plugin_basename( ONECAPTCHA_PLUGIN_FILE ) );
}

// Define plugin directory Path.
if ( ! defined( 'ONECAPTCHA_PLUGIN_DIR' ) ) {
	define( 'ONECAPTCHA_PLUGIN_DIR', plugin_dir_path( ONECAPTCHA_PLUGIN_FILE ) );
}

// Define plugin directory URL.
if ( ! defined( 'ONECAPTCHA_PLUGIN_URL' ) ) {
	define( 'ONECAPTCHA_PLUGIN_URL', plugin_dir_url( ONECAPTCHA_PLUGIN_FILE ) );
}
