<?php

namespace Wp3D_Scroll;

abstract class Base_Widget extends \Elementor\Widget_Base {
	protected function scene_common_controls(): void {
		$this->add_control(
			'scene_id',
			[
				'label'       => esc_html__( 'Scene ID', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'scene-1',
				'label_block' => true,
			]
		);

		$this->add_control(
			'disable_mobile',
			[
				'label'        => esc_html__( 'Disable 3D on Mobile', 'wp3d-scroll' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wp3d-scroll' ),
				'label_off'    => esc_html__( 'No', 'wp3d-scroll' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'fallback_image',
			[
				'label'   => esc_html__( 'Fallback Image URL', 'wp3d-scroll' ),
				'type'    => \Elementor\Controls_Manager::URL,
				'options' => [ 'url' ],
			]
		);

		$this->add_control(
			'alt_description',
			[
				'label'       => esc_html__( 'Alt Description', 'wp3d-scroll' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Describe the scene for screen readers', 'wp3d-scroll' ),
				'rows'        => 3,
			]
		);
	}

	protected function build_scene_data_attrs( array $extra = [] ): string {
		$settings = $this->get_settings_for_display();

		$attrs = [
			'data-wp3d-scene'          => '1',
			'data-wp3d-scene-id'       => sanitize_key( (string) ( $settings['scene_id'] ?? '' ) ),
			'data-wp3d-disable-mobile' => ( ( $settings['disable_mobile'] ?? '' ) === 'yes' ) ? '1' : '0',
			'data-wp3d-fallback'       => esc_url( (string) ( $settings['fallback_image']['url'] ?? '' ) ),
		];

		foreach ( $extra as $k => $v ) {
			$attrs[ $k ] = $v;
		}

		$out = '';
		foreach ( $attrs as $k => $v ) {
			if ( $v === '' ) {
				continue;
			}
			$out .= ' ' . esc_attr( $k ) . '="' . esc_attr( (string) $v ) . '"';
		}

		return $out;
	}
}
