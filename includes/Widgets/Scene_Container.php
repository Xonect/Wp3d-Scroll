<?php

namespace Wp3D_Scroll\Widgets;

use Wp3D_Scroll\Base_Widget;

final class Scene_Container extends Base_Widget {
	public function get_name() {
		return 'wp3d_scene_container';
	}

	public function get_title() {
		return esc_html__( '3D Scene Container', 'wp3d-scroll' );
	}

	public function get_icon() {
		return 'eicon-3d';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Scene', 'wp3d-scroll' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->scene_common_controls();

		$this->add_control(
			'height',
			[
				'label'   => esc_html__( 'Canvas Height (px)', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 600,
				'min'     => 100,
				'step'    => 10,
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'   => esc_html__( 'Background Color', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$attrs = $this->build_scene_data_attrs(
			[
				'data-wp3d-height'   => (string) (int) ( $settings['height'] ?? 600 ),
				'data-wp3d-bg-color' => sanitize_hex_color( (string) ( $settings['bg_color'] ?? '#000000' ) ),
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
