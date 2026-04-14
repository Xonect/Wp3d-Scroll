<?php
/**
 * Plugin Name: Wp3D Scroll
 * Description: GSAP + Three.js powered 3D scroll effects for Elementor.
 * Version: 0.1.0
 * Requires at least: 6.2
 * Requires PHP: 8.0
 * Author: Xonect
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP3D_SCROLL_VERSION', '0.1.0' );
define( 'WP3D_SCROLL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP3D_SCROLL_URL', plugin_dir_url( __FILE__ ) );

require_once WP3D_SCROLL_PATH . 'includes/Plugin.php';

add_action(
	'plugins_loaded',
	static function () {
		\Wp3D_Scroll\Plugin::instance();
	}
);
