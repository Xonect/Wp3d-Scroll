<?php

namespace Wp3D_Scroll;

final class Plugin {
	private static ?Plugin $instance = null;

	private function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public static function instance(): Plugin {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init(): void {
		require_once WP3D_SCROLL_PATH . 'includes/Loader.php';
		require_once WP3D_SCROLL_PATH . 'includes/Base_Widget.php';
		require_once WP3D_SCROLL_PATH . 'includes/Widgets/Scene_Container.php';
		require_once WP3D_SCROLL_PATH . 'includes/Widgets/Model_Viewer.php';
		require_once WP3D_SCROLL_PATH . 'includes/Widgets/Scroll_Camera_Path.php';
		require_once WP3D_SCROLL_PATH . 'includes/Widgets/Scroll_Progress_Bar.php';

		Loader::instance();

		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	public function register_widgets( $widgets_manager ): void {
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}

		$widgets_manager->register( new Widgets\Scene_Container() );
		$widgets_manager->register( new Widgets\Model_Viewer() );
		$widgets_manager->register( new Widgets\Scroll_Camera_Path() );
		$widgets_manager->register( new Widgets\Scroll_Progress_Bar() );
	}
}
