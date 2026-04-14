<?php

namespace Wp3D_Scroll\Widgets;

final class Scroll_Progress_Bar extends \Elementor\Widget_Base {
	public function get_name() {
		return 'wp3d_scroll_progress_bar';
	}

	public function get_title() {
		return esc_html__( 'Scroll Progress Bar', 'wp3d-scroll' );
	}

	public function get_icon() {
		return 'eicon-progress-tracker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Progress Bar', 'wp3d-scroll' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'position',
			[
				'label'   => esc_html__( 'Position', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top'    => esc_html__( 'Top', 'wp3d-scroll' ),
					'bottom' => esc_html__( 'Bottom', 'wp3d-scroll' ),
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label'   => esc_html__( 'Color', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);

		$this->add_control(
			'height',
			[
				'label'   => esc_html__( 'Height (px)', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 20,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$pos   = ( ( $settings['position'] ?? 'top' ) === 'bottom' ) ? 'bottom' : 'top';
		$color = sanitize_hex_color( (string) ( $settings['color'] ?? '#ffffff' ) ) ?: '#ffffff';
		$h     = (int) ( $settings['height'] ?? 4 );
		?>
		<div
			class="wp3d-scroll-progress wp3d-scroll-progress--<?php echo esc_attr( $pos ); ?>"
			data-wp3d-progress="1"
			style="--wp3d-progress-color: <?php echo esc_attr( $color ); ?>; --wp3d-progress-height: <?php echo esc_attr( (string) $h ); ?>px;"
		></div>
		<?php
	}
}

