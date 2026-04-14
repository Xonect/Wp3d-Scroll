<?php

namespace Wp3D_Scroll;

use Wp3D_Scroll\Admin\Settings;

final class Loader {
	private static ?Loader $instance = null;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend' ] );
	}

	public static function instance(): Loader {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function enqueue_frontend(): void {
		if ( is_admin() ) {
			return;
		}

		$options = Settings::get_options();
		if ( empty( $options['enabled'] ) ) {
			return;
		}

		$dist_dir = WP3D_SCROLL_PATH . 'dist/';
		$dist_url = WP3D_SCROLL_URL . 'dist/';

		$bootstrap_js_path = $dist_dir . 'wp3d-scroll-bootstrap.js';
		$main_js_path      = $dist_dir . 'wp3d-scroll.js';
		$css_path = $dist_dir . 'wp3d-scroll.css';

		$version_mode = isset( $options['version_mode'] ) ? (string) $options['version_mode'] : 'plugin';
		$ver = WP3D_SCROLL_VERSION;
		if ( $version_mode === 'filemtime' && file_exists( $bootstrap_js_path ) ) {
			$ver = (string) filemtime( $bootstrap_js_path );
		}

		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				'wp3d-scroll',
				$dist_url . 'wp3d-scroll.css',
				[],
				$ver
			);
		}

		if ( file_exists( $bootstrap_js_path ) ) {
			wp_register_script(
				'wp3d-scroll-bootstrap',
				$dist_url . 'wp3d-scroll-bootstrap.js',
				[],
				$ver,
				true
			);

			wp_script_add_data( 'wp3d-scroll-bootstrap', 'type', 'module' );

			$settings = Settings::frontend_settings();
			$settings['mainUrl'] = file_exists( $main_js_path ) ? add_query_arg( 'ver', $ver, $dist_url . 'wp3d-scroll.js' ) : '';

			wp_localize_script( 'wp3d-scroll-bootstrap', 'wp3dScrollSettings', $settings );

			wp_enqueue_script( 'wp3d-scroll-bootstrap' );
		}
	}
}
