<?php

namespace Wp3D_Scroll\Widgets;

use Wp3D_Scroll\Base_Widget;

final class Scroll_Camera_Path extends Base_Widget {
	public function get_name() {
		return 'wp3d_scroll_camera_path';
	}

	public function get_title() {
		return esc_html__( 'Scroll Camera Path', 'wp3d-scroll' );
	}

	public function get_icon() {
		return 'eicon-video-camera';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Camera Path', 'wp3d-scroll' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->scene_common_controls();

		$this->add_control(
			'control_points_json',
			[
				'label'       => esc_html__( 'Control Points (JSON)', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'default'     => '[{\"x\":0,\"y\":0,\"z\":6},{\"x\":0,\"y\":0,\"z\":2},{\"x\":2,\"y\":1,\"z\":0}]',
				'label_block' => true,
			]
		);

		$this->add_control(
			'camera_fov',
			[
				'label'   => esc_html__( 'Camera FOV', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 50,
				'min'     => 10,
				'max'     => 120,
			]
		);

		$this->add_control(
			'pin',
			[
				'label'        => esc_html__( 'Pin Section', 'wp3d-scroll' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wp3d-scroll' ),
				'label_off'    => esc_html__( 'No', 'wp3d-scroll' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'pin_spacing',
			[
				'label'        => esc_html__( 'Pin Spacing', 'wp3d-scroll' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wp3d-scroll' ),
				'label_off'    => esc_html__( 'No', 'wp3d-scroll' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'scroll_start',
			[
				'label'       => esc_html__( 'Scroll Start', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'top top',
				'label_block' => true,
			]
		);

		$this->add_control(
			'scroll_end',
			[
				'label'       => esc_html__( 'Scroll End', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '+=2000',
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$attrs = $this->build_scene_data_attrs(
			[
				'data-wp3d-widget'       => 'camera-path',
				'data-wp3d-points'       => wp_json_encode( json_decode( (string) ( $settings['control_points_json'] ?? '[]' ), true ) ?: [] ),
				'data-wp3d-camera-fov'   => (string) (int) ( $settings['camera_fov'] ?? 50 ),
				'data-wp3d-pin'          => ( ( $settings['pin'] ?? 'yes' ) === 'yes' ) ? '1' : '0',
				'data-wp3d-pin-spacing'  => ( ( $settings['pin_spacing'] ?? 'yes' ) === 'yes' ) ? '1' : '0',
				'data-wp3d-start'        => sanitize_text_field( (string) ( $settings['scroll_start'] ?? 'top top' ) ),
				'data-wp3d-end'          => sanitize_text_field( (string) ( $settings['scroll_end'] ?? '+=2000' ) ),
			]
		);

		$alt = (string) ( $settings['alt_description'] ?? '' );
		?>
		<div class="wp3d-scroll-scene"<?php echo $attrs; ?>>
			<canvas class="wp3d-scroll-canvas" aria-hidden="true"></canvas>
			<?php if ( $alt !== '' ) : ?>
				<div class="wp3d-scroll-sr-only"><?php echo esc_html( $alt ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}

