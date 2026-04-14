<?php

namespace Wp3D_Scroll\Widgets;

use Wp3D_Scroll\Base_Widget;

final class Model_Viewer extends Base_Widget {
	public function get_name() {
		return 'wp3d_model_viewer';
	}

	public function get_title() {
		return esc_html__( 'Model Viewer', 'wp3d-scroll' );
	}

	public function get_icon() {
		return 'eicon-upload';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Model', 'wp3d-scroll' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->scene_common_controls();

		$this->add_control(
			'model_url',
			[
				'label'       => esc_html__( 'GLB/GLTF URL', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'options'     => [ 'url' ],
				'label_block' => true,
			]
		);

		$this->add_control(
			'animation_preset',
			[
				'label'   => esc_html__( 'Animation Preset', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'rotate',
				'options' => [
					'rotate'    => esc_html__( 'Rotate', 'wp3d-scroll' ),
					'float'     => esc_html__( 'Float', 'wp3d-scroll' ),
					'translate' => esc_html__( 'Translate', 'wp3d-scroll' ),
					'scale'     => esc_html__( 'Scale', 'wp3d-scroll' ),
				],
			]
		);

		$this->add_control(
			'rotation_axis',
			[
				'label'   => esc_html__( 'Rotation Axis', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'y',
				'options' => [
					'x' => 'X',
					'y' => 'Y',
					'z' => 'Z',
				],
			]
		);

		$this->add_control(
			'scroll_start',
			[
				'label'       => esc_html__( 'Scroll Start', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'top bottom',
				'label_block' => true,
			]
		);

		$this->add_control(
			'scroll_end',
			[
				'label'       => esc_html__( 'Scroll End', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'bottom top',
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$attrs = $this->build_scene_data_attrs(
			[
				'data-wp3d-widget'        => 'model-viewer',
				'data-wp3d-model-url'     => esc_url( (string) ( $settings['model_url']['url'] ?? '' ) ),
				'data-wp3d-preset'        => sanitize_key( (string) ( $settings['animation_preset'] ?? 'rotate' ) ),
				'data-wp3d-rotation-axis' => sanitize_key( (string) ( $settings['rotation_axis'] ?? 'y' ) ),
				'data-wp3d-start'         => sanitize_text_field( (string) ( $settings['scroll_start'] ?? 'top bottom' ) ),
				'data-wp3d-end'           => sanitize_text_field( (string) ( $settings['scroll_end'] ?? 'bottom top' ) ),
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
