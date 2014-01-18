<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'newzeo_options', 'newzeo_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'newzeo' ), __( 'Newzeo Options', 'newzeo' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create arrays for our select and radio options
 */

$replace_options = array(
	'yes' => array(
		'value' => 'yes',
		'label' => __( 'Yes', 'newzeo' )
	),
	'no' => array(
		'value' => 'no',
		'label' => __( 'No', 'newzeo' )
	)
);

$colorscheme_options = array(
	'light' => array(
		'value' => 'light',
		'thumbnail' => get_template_directory_uri() . '/images/light_thumb.jpg',
		'label' => __( 'Light', 'newzeo' )
	),
	'dark' => array(
		'value' => 'dark',
		'thumbnail' => get_template_directory_uri() . '/images/dark_thumb.jpg',
		'label' => __( 'Dark', 'newzeo' )
	)
);


 
/**
 * Create the options page
 */
function theme_options_do_page() {
	global $replace_options, $colorscheme_options; 

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'newzeo' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'newzeo' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'newzeo_options' ); ?>
			<?php $options = get_option( 'newzeo_theme_options' ); ?>
		
			
			<div id="newzeo-option">
				<h3>Social option </h3>
				<!-- Newzeo Twitter -->
				<div class="row">
					<div class="columntitle">
						Twitter ID
					</div>
					<div class="columntext">
						<input id="newzeo_theme_options[twitid]" class="regular-text" type="text" name="newzeo_theme_options[twitid]" value="<?php esc_attr_e( $options['twitid'] ); ?>" />
					</div>
					<div class="columnlabel">
						<label class="description" for="newzeo_theme_options[twitid]">Ex: illuminatheme, without <code>@</code> </label>
					</div>
				</div>
				<!-- Newzeo FB Fanpage URL -->
				<div class="row">
					<div class="columntitle">
						Facebook Page Url
					</div>
					<div class="columntext">
						<input id="newzeo_theme_options[facebook]" class="regular-text" type="text" name="newzeo_theme_options[facebook]" value="<?php esc_attr_e( $options['facebook'] ); ?>" />
					</div>
					<div class="columnlabel">
						<label class="description" for="newzeo_theme_options[facebook]">Facebook Page Url with <code>http://</code></label>
					</div>
				</div>
				<!-- Newzeo RSS URL -->
				<div class="row">
					<div class="columntitle">
						RSS Url
					</div>
					<div class="columntext">
						<input id="newzeo_theme_options[rss]" class="regular-text" type="text" name="newzeo_theme_options[rss]" value="<?php esc_attr_e( $options['rss'] ); ?>" />
					</div>
					<div class="columnlabel">
						<label class="description" for="newzeo_theme_options[rss]">RSS Url with <code>http://</code></label>
					</div>
				</div>
				<h3>Feature option </h3>
				<!-- Newzeo Dark color scheme option -->
				<div class="row">
					<div class="columntitle">
						Color scheme
					</div>
					<div class="columnthumb">
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color scheme option', 'newzeo' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $colorscheme_options as $option ) {
								$colorscheme_setting = $options['colorschemeinput'];

								if ( '' != $colorscheme_setting ) {
									if ( $options['colorschemeinput'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								
								<label class="description">
									<input type="radio" name="newzeo_theme_options[colorschemeinput]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> />
									<?php echo $option['label']; ?> 
									<br/>
									<img src="<?php echo esc_url( $option['thumbnail'] ); ?>" width="150" height="100" alt="" />
								</label>
								
								<?php
							}
						?>
						</fieldset>
					</div>
				</div>
				<!-- Newzeo related post option -->
				<div class="row">
					<div class="columntitle">
						Display related post on single
					</div>
					<div class="columnthumb">
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Display related post on single', 'newzeo' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $replace_options as $option ) {
								$replace_setting = $options['relatedinput'];

								if ( '' != $replace_setting ) {
									if ( $options['relatedinput'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<label class="description"><input type="radio" name="newzeo_theme_options[relatedinput]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label>
								<?php
							}
						?>
						
						</fieldset>
						<img src="<?php echo get_template_directory_uri() . '/images/related-thumb.jpg' ?>" width="325" height="160" alt="" />
					</div>
					
					
				</div>
			</div>		

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'newzeo' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {
	global $radio_options;

	// Say our text option must be safe text with no HTML tags
	$input['twitid'] = wp_filter_nohtml_kses( $input['twitid'] );
	$input['rss'] = wp_filter_nohtml_kses( $input['rss'] );
	$input['facebook'] = wp_filter_nohtml_kses( $input['facebook'] );
		
	// Say our textarea option must be safe text with the allowed tags for posts
	$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );
	
	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/