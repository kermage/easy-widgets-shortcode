<?php

/**
 * @package Easy Widgets Shortcode
 * @since 0.1.0
 */

/**
 * Plugin Name: Easy Widgets Shortcode
 * Plugin URI:  https://github.com/kermage/easy-widgets-shortcode/
 * Author:      Gene Alyson Fortunado Torcende
 * Author URI:  mailto:genealyson.torcende@gmail.com
 * Description: Easily embed any widget and/or sidebar to content using shortcodes [ews_widget] and [ews_sidebar].
 * Version:     1.0.0
 * License:     GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: ews
 */

// Accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/* ==================================================
Global constants
================================================== */
define( 'EWS_VERSION',  '1.0.0' );
define( 'EWS_FILE',     __FILE__ );
define( 'EWS_URL',      plugin_dir_url( EWS_FILE ) );
define( 'EWS_PATH',     plugin_dir_path( EWS_FILE ) );

// Load the Easy Widgets Shortcode plugin
require_once EWS_PATH . 'class.' . basename( EWS_FILE );
