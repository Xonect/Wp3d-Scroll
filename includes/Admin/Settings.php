<?php

namespace Wp3D_Scroll\Admin;

final class Settings {
	public const OPTION_NAME = 'wp3d_scroll_options';

	public static function defaults(): array {
		return [
			'enabled'                 => 1,
			'debug_markers'           => 0,
			'performance_tier'        => 'medium',
			'max_pixel_ratio'         => 2,
			'disable_mobile_default'  => 0,
			'min_hardware_concurrency'=> 4,
			'min_device_memory'       => 4,
			'reduced_motion_mode'     => 'disable',
			'version_mode'            => 'plugin',
		];
	}

	public static function get_options(): array {
		$saved = get_option( self::OPTION_NAME, [] );
		if ( ! is_array( $saved ) ) {
			$saved = [];
		}

		return array_merge( self::defaults(), $saved );
	}

	public static function sanitize( $input ): array {
		$in = is_array( $input ) ? $input : [];
		$out = self::defaults();

		$out['enabled'] = empty( $in['enabled'] ) ? 0 : 1;
		$out['debug_markers'] = empty( $in['debug_markers'] ) ? 0 : 1;

		$tier = isset( $in['performance_tier'] ) ? sanitize_key( (string) $in['performance_tier'] ) : 'medium';
		if ( ! in_array( $tier, [ 'low', 'medium', 'high' ], true ) ) {
			$tier = 'medium';
		}
		$out['performance_tier'] = $tier;

		$max_dpr = isset( $in['max_pixel_ratio'] ) ? (int) $in['max_pixel_ratio'] : 2;
		$out['max_pixel_ratio'] = max( 1, min( 3, $max_dpr ) );

		$out['disable_mobile_default'] = empty( $in['disable_mobile_default'] ) ? 0 : 1;

		$min_hc = isset( $in['min_hardware_concurrency'] ) ? (int) $in['min_hardware_concurrency'] : 4;
		$out['min_hardware_concurrency'] = max( 0, min( 32, $min_hc ) );

		$min_dm = isset( $in['min_device_memory'] ) ? (int) $in['min_device_memory'] : 4;
		$out['min_device_memory'] = max( 0, min( 64, $min_dm ) );

		$rm = isset( $in['reduced_motion_mode'] ) ? sanitize_key( (string) $in['reduced_motion_mode'] ) : 'disable';
		if ( ! in_array( $rm, [ 'disable', 'static_frame' ], true ) ) {
			$rm = 'disable';
		}
		$out['reduced_motion_mode'] = $rm;

		$vm = isset( $in['version_mode'] ) ? sanitize_key( (string) $in['version_mode'] ) : 'plugin';
		if ( ! in_array( $vm, [ 'plugin', 'filemtime' ], true ) ) {
			$vm = 'plugin';
		}
		$out['version_mode'] = $vm;

		return $out;
	}

	public static function frontend_settings(): array {
		$o = self::get_options();

		return [
			'enabled'               => (int) $o['enabled'],
			'debugMarkers'          => (int) $o['debug_markers'],
			'performanceTier'       => (string) $o['performance_tier'],
			'maxPixelRatio'         => (int) $o['max_pixel_ratio'],
			'disableMobileDefault'  => (int) $o['disable_mobile_default'],
			'minHardwareConcurrency'=> (int) $o['min_hardware_concurrency'],
			'minDeviceMemory'       => (int) $o['min_device_memory'],
			'reducedMotionMode'     => (string) $o['reduced_motion_mode'],
		];
	}
}

