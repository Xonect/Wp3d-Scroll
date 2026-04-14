<?php

namespace Wp3D_Scroll\Admin;

final class Settings_Page {
	public static function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Wp3D Scroll', 'wp3d-scroll' ); ?></h1>
			<p><?php echo esc_html__( 'Global settings for scroll-driven 3D widgets.', 'wp3d-scroll' ); ?></p>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'wp3d_scroll' );
				do_settings_sections( 'wp3d-scroll' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

