<div class="wrap apple-news-theme-edit">
	<form method="post" action="" id="apple-news-theme-edit-form">
		<?php wp_nonce_field( 'apple_news_save_theme' ); ?>
		<input type="hidden" name="action" value="apple_news_save_edit_theme" />
		<p>
			<h3><?php echo esc_html_e( 'Theme Name', 'apple-news' ) ?></h3>
			<br />
			<input type="text" id="apple_news_theme_name" name="apple_news_theme_name" value="<?php echo esc_attr( $theme_name ) ?>" maxlength="45" />
		</p>
		<?php
			// Get formatting settings
			$section = new Admin_Apple_Settings_Section_Formatting( 'apple-news-theme-edit' );
			$section->before_section();
			include plugin_dir_path( __FILE__ ) . 'page_options_section.php';
			$section->after_section();
			submit_button();
		?>
	</form>
</div>
