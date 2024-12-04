<?php
/**
 * One Captcha
 *
 * @package           OneCaptcha
 * @author            Mehul Gohil
 * @copyright         2024 Mehul Gohil
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       OneCaptcha — Universal Captcha Plugin
 * Plugin URI:        https://mehulgohil.com/plugins/one-captcha/
 * Description:       OneCaptcha is a WordPress plugin that acts as a bridge between the WordPress plugins and Captcha services
 * Version:           1.0.0
 * Requires at least: 4.8
 * Requires PHP:      8.1
 * Author:            Mehul Gohil
 * Author URI:        https://mehulgohil.com
 * Text Domain:       onecaptcha
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace MG\OneCaptcha;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load Constants.
require_once __DIR__ . '/config/constants.php';

// Automatically loads files used throughout the plugin.
require_once 'vendor/autoload.php';

// Initialize the plugin.
$plugin = new Plugin();
$plugin->register();
