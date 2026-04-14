<?php

namespace Wp3D_Scroll\Admin;

final class Admin {
	private static ?Admin $instance = null;

	private function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	public static function instance(): Admin {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function admin_menu(): void {
		add_menu_page(
			esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ),
			esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ),
			'manage_options',
			'wp3d-scroll',
			[ Settings_Page::class, 'render' ],
			'dashicons-admin-generic',
			58
		);

		add_options_page(
			esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ),
			esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ),
			'manage_options',
			'wp3d-scroll',
			[ Settings_Page::class, 'render' ]
		);

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			add_submenu_page(
				'elementor',
				esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ),
				esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ),
				'manage_options',
				'wp3d-scroll',
				[ Settings_Page::class, 'render' ]
			);
		}
	}

	public function admin_init(): void {
		register_setting(
			'wp3d_scroll',
			Settings::OPTION_NAME,
			[
				'type'              => 'array',
				'sanitize_callback' => [ Settings::class, 'sanitize' ],
				'default'           => Settings::defaults(),
			]
		);

		add_settings_section(
			'wp3d_scroll_general',
			esc_html__( 'General', 'wp3d-scroll' ),
			static function () {
				echo '<p>' . esc_html__( 'Main plugin controls.', 'wp3d-scroll' ) . '</p>';
			},
			'wp3d-scroll'
		);

		add_settings_field(
			'enabled',
			esc_html__( 'Enable Plugin', 'wp3d-scroll' ),
			[ $this, 'field_enabled' ],
			'wp3d-scroll',
			'wp3d_scroll_general'
		);

		add_settings_section(
			'wp3d_scroll_debug',
			esc_html__( 'Debug', 'wp3d-scroll' ),
			static function () {
				echo '<p>' . esc_html__( 'Debug helpers for ScrollTrigger.', 'wp3d-scroll' ) . '</p>';
			},
			'wp3d-scroll'
		);

		add_settings_field(
			'debug_markers',
			esc_html__( 'ScrollTrigger Markers', 'wp3d-scroll' ),
			[ $this, 'field_debug_markers' ],
			'wp3d-scroll',
			'wp3d_scroll_debug'
		);

		add_settings_section(
			'wp3d_scroll_performance',
			esc_html__( 'Performance', 'wp3d-scroll' ),
			static function () {
				echo '<p>' . esc_html__( 'Global renderer defaults.', 'wp3d-scroll' ) . '</p>';
			},
			'wp3d-scroll'
		);

		add_settings_field(
			'performance_tier',
			esc_html__( 'Performance Tier', 'wp3d-scroll' ),
			[ $this, 'field_performance_tier' ],
			'wp3d-scroll',
			'wp3d_scroll_performance'
		);

		add_settings_field(
			'max_pixel_ratio',
			esc_html__( 'Max Pixel Ratio', 'wp3d-scroll' ),
			[ $this, 'field_max_pixel_ratio' ],
			'wp3d-scroll',
			'wp3d_scroll_performance'
		);

		add_settings_section(
			'wp3d_scroll_fallback',
			esc_html__( 'Device Fallback', 'wp3d-scroll' ),
			static function () {
				echo '<p>' . esc_html__( 'Control when 3D is disabled for compatibility.', 'wp3d-scroll' ) . '</p>';
			},
			'wp3d-scroll'
		);

		add_settings_field(
			'disable_mobile_default',
			esc_html__( 'Disable 3D on Mobile (default)', 'wp3d-scroll' ),
			[ $this, 'field_disable_mobile_default' ],
			'wp3d-scroll',
			'wp3d_scroll_fallback'
		);

		add_settings_field(
			'min_hardware_concurrency',
			esc_html__( 'Min CPU Cores', 'wp3d-scroll' ),
			[ $this, 'field_min_hardware_concurrency' ],
			'wp3d-scroll',
			'wp3d_scroll_fallback'
		);

		add_settings_field(
			'min_device_memory',
			esc_html__( 'Min Device Memory (GB)', 'wp3d-scroll' ),
			[ $this, 'field_min_device_memory' ],
			'wp3d-scroll',
			'wp3d_scroll_fallback'
		);

		add_settings_field(
			'reduced_motion_mode',
			esc_html__( 'Reduced Motion', 'wp3d-scroll' ),
			[ $this, 'field_reduced_motion_mode' ],
			'wp3d-scroll',
			'wp3d_scroll_fallback'
		);

		add_settings_section(
			'wp3d_scroll_assets',
			esc_html__( 'Assets', 'wp3d-scroll' ),
			static function () {
				echo '<p>' . esc_html__( 'How assets are versioned and loaded.', 'wp3d-scroll' ) . '</p>';
			},
			'wp3d-scroll'
		);

		add_settings_field(
			'version_mode',
			esc_html__( 'Version Mode', 'wp3d-scroll' ),
			[ $this, 'field_version_mode' ],
			'wp3d-scroll',
			'wp3d_scroll_assets'
		);
	}

	private function opt( string $key ) {
		$o = Settings::get_options();
		return $o[ $key ] ?? null;
	}

	public function field_enabled(): void {
		$val = (int) $this->opt( 'enabled' );
		echo '<label><input type="checkbox" name="' . esc_attr( Settings::OPTION_NAME ) . '[enabled]" value="1" ' . checked( 1, $val, false ) . '> ' . esc_html__( 'Enable frontend loading', 'wp3d-scroll' ) . '</label>';
	}

	public function field_debug_markers(): void {
		$val = (int) $this->opt( 'debug_markers' );
		echo '<label><input type="checkbox" name="' . esc_attr( Settings::OPTION_NAME ) . '[debug_markers]" value="1" ' . checked( 1, $val, false ) . '> ' . esc_html__( 'Show ScrollTrigger markers (can also use ?wp3d_debug=1)', 'wp3d-scroll' ) . '</label>';
	}

	public function field_performance_tier(): void {
		$val = (string) $this->opt( 'performance_tier' );
		echo '<select name="' . esc_attr( Settings::OPTION_NAME ) . '[performance_tier]">';
		echo '<option value="low" ' . selected( 'low', $val, false ) . '>' . esc_html__( 'Low', 'wp3d-scroll' ) . '</option>';
		echo '<option value="medium" ' . selected( 'medium', $val, false ) . '>' . esc_html__( 'Medium', 'wp3d-scroll' ) . '</option>';
		echo '<option value="high" ' . selected( 'high', $val, false ) . '>' . esc_html__( 'High', 'wp3d-scroll' ) . '</option>';
		echo '</select>';
	}

	public function field_max_pixel_ratio(): void {
		$val = (int) $this->opt( 'max_pixel_ratio' );
		echo '<input type="number" min="1" max="3" step="1" name="' . esc_attr( Settings::OPTION_NAME ) . '[max_pixel_ratio]" value="' . esc_attr( (string) $val ) . '">';
	}

	public function field_disable_mobile_default(): void {
		$val = (int) $this->opt( 'disable_mobile_default' );
		echo '<label><input type="checkbox" name="' . esc_attr( Settings::OPTION_NAME ) . '[disable_mobile_default]" value="1" ' . checked( 1, $val, false ) . '> ' . esc_html__( 'Disable 3D on small screens by default', 'wp3d-scroll' ) . '</label>';
	}

	public function field_min_hardware_concurrency(): void {
		$val = (int) $this->opt( 'min_hardware_concurrency' );
		echo '<input type="number" min="0" max="32" step="1" name="' . esc_attr( Settings::OPTION_NAME ) . '[min_hardware_concurrency]" value="' . esc_attr( (string) $val ) . '">';
		echo '<p class="description">' . esc_html__( 'Set 0 to disable this check.', 'wp3d-scroll' ) . '</p>';
	}

	public function field_min_device_memory(): void {
		$val = (int) $this->opt( 'min_device_memory' );
		echo '<input type="number" min="0" max="64" step="1" name="' . esc_attr( Settings::OPTION_NAME ) . '[min_device_memory]" value="' . esc_attr( (string) $val ) . '">';
		echo '<p class="description">' . esc_html__( 'Set 0 to disable this check.', 'wp3d-scroll' ) . '</p>';
	}

	public function field_reduced_motion_mode(): void {
		$val = (string) $this->opt( 'reduced_motion_mode' );
		echo '<select name="' . esc_attr( Settings::OPTION_NAME ) . '[reduced_motion_mode]">';
		echo '<option value="disable" ' . selected( 'disable', $val, false ) . '>' . esc_html__( 'Disable 3D (fallback)', 'wp3d-scroll' ) . '</option>';
		echo '<option value="static_frame" ' . selected( 'static_frame', $val, false ) . '>' . esc_html__( 'Render static frame', 'wp3d-scroll' ) . '</option>';
		echo '</select>';
	}

	public function field_version_mode(): void {
		$val = (string) $this->opt( 'version_mode' );
		echo '<select name="' . esc_attr( Settings::OPTION_NAME ) . '[version_mode]">';
		echo '<option value="plugin" ' . selected( 'plugin', $val, false ) . '>' . esc_html__( 'Plugin version', 'wp3d-scroll' ) . '</option>';
		echo '<option value="filemtime" ' . selected( 'filemtime', $val, false ) . '>' . esc_html__( 'File modified time', 'wp3d-scroll' ) . '</option>';
		echo '</select>';
	}
}

