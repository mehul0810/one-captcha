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
 * Plugin Name:       One Captcha
 * Plugin URI:        https://mehulgohil.com/plugins/one-captcha/
 * Description:       One Captcha is a WordPress plugin that acts as a bridge between the WordPress plugins and Captcha services
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Mehul Gohil
 * Author URI:        https://mehulgohil.com
 * Text Domain:       one-captcha
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

