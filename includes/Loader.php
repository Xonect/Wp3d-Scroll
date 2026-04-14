<?php

namespace Wp3D_Scroll;

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

		$dist_dir = WP3D_SCROLL_PATH . 'dist/';
		$dist_url = WP3D_SCROLL_URL . 'dist/';

		$js_path  = $dist_dir . 'wp3d-scroll.js';
		$css_path = $dist_dir . 'wp3d-scroll.css';

		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				'wp3d-scroll',
				$dist_url . 'wp3d-scroll.css',
				[],
				WP3D_SCROLL_VERSION
			);
		}

		if ( file_exists( $js_path ) ) {
			wp_enqueue_script(
				'wp3d-scroll',
				$dist_url . 'wp3d-scroll.js',
				[],
				WP3D_SCROLL_VERSION,
				true
			);
		}
	}
}
