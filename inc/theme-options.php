<?php
/**
 * Baylys Theme Options
 *
 * @package WordPress
 * @subpackage Baylys
 * @since Baylys 1.0
 */

/*-----------------------------------------------------------------------------------*/
/* Properly enqueue styles and scripts for our theme options page.
/*
/* This function is attached to the admin_enqueue_scripts action hook.
/*
/* @param string $hook_suffix The action passes the current page to the function.
/* We don't do anything if we're not on our theme options page.
/*-----------------------------------------------------------------------------------*/

function baylys_admin_enqueue_scripts( $hook_suffix ) {
	if ( $hook_suffix != 'appearance_page_theme_options' )
		return;

	wp_enqueue_style( 'baylys-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2012-07-22' );
	wp_enqueue_script( 'baylys-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2012-07-22' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_enqueue_scripts', 'baylys_admin_enqueue_scripts' );

/*-----------------------------------------------------------------------------------*/
/* Register the form setting for our baylys_options array.
/*
/* This function is attached to the admin_init action hook.
/*
/* This call to register_setting() registers a validation callback, baylys_theme_options_validate(),
/* which is used when the option is saved, to ensure that our option values are complete, properly
/* formatted, and safe.
/*
/* We also use this function to add our theme option if it doesn't already exist.
/*-----------------------------------------------------------------------------------*/

function baylys_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === baylys_get_theme_options() )
		add_option( 'baylys_theme_options', baylys_get_default_theme_options() );

	register_setting(
		'baylys_options',       // Options group, see settings_fields() call in theme_options_render_page()
		'baylys_theme_options', // Database option, see baylys_get_theme_options()
		'baylys_theme_options_validate' // The sanitization callback, see baylys_theme_options_validate()
	);
}
add_action( 'admin_init', 'baylys_theme_options_init' );

/*-----------------------------------------------------------------------------------*/
/* Add our theme options page to the admin menu.
/* 
/* This function is attached to the admin_menu action hook.
/*-----------------------------------------------------------------------------------*/

function baylys_theme_options_add_page() {
	add_theme_page(
		__( 'Theme Options', 'baylys' ), // Name of page
		__( 'Theme Options', 'baylys' ), // Label in menu
		'edit_theme_options',                  // Capability required
		'theme_options',                       // Menu slug, used to uniquely identify the page
		'theme_options_render_page'            // Function that renders the options page
	);
}
add_action( 'admin_menu', 'baylys_theme_options_add_page' );


/*-----------------------------------------------------------------------------------*/
/* Returns an array of layout options registered for Baylys
/*-----------------------------------------------------------------------------------*/
function baylys_layouts() {
	$layout_options = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Sidebar right', 'baylys' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Sidebar left', 'baylys' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'content' => array(
			'value' => 'content',
			'label' => __( 'One-column', 'baylys' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
	);

	return apply_filters( 'baylys_layouts', $layout_options );
}

/*-----------------------------------------------------------------------------------*/
/* Returns the default options for Baylys
/*-----------------------------------------------------------------------------------*/

function baylys_get_default_theme_options() {
	$default_theme_options = array(
		'link_color'   => '#00CA4C',
		'linkhover_color'   => '#059129',
		'footerbg_color'   => '#2D3138',
		'theme_layout' => 'content-sidebar',
		'custom_logo' => '',
		'custom_footertext' => '',
		'custom_favicon' => '',
		'custom_apple_icon' => '',
		'show-excerpt' => '',
		'share-posts' => '',
		'share-singleposts' => '',
		'share-pages' => '',
		'use-slider' => '',
		'portfolio-cat' => '',
	);

	return apply_filters( 'baylys_default_theme_options', $default_theme_options );
}

/*-----------------------------------------------------------------------------------*/
/* Returns the options array for Baylys
/*-----------------------------------------------------------------------------------*/

function baylys_get_theme_options() {
	return get_option( 'baylys_theme_options' );
}

/*-----------------------------------------------------------------------------------*/
/* Returns the options array for Baylys
/*-----------------------------------------------------------------------------------*/

function theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'baylys' ), wp_get_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'baylys_options' );
				$options = baylys_get_theme_options();
				$default_options = baylys_get_default_theme_options();
			?>

			<table class="form-table">
			<h3 style="margin-top:30px;"><?php _e( 'Custom Colors', 'baylys' ); ?></h3>
				<tr valign="top"><th scope="row"><?php _e( 'Custom Link Color', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Link Color', 'baylys' ); ?></span></legend>
							 <input type="text" name="baylys_theme_options[link_color]" value="<?php echo esc_attr( $options['link_color'] ); ?>" id="link-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker1"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom link color, the default link color is: %s. Do not forget to include the # before the color value.', 'baylys' ), $default_options['link_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Link Hover Color', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Link Hover Color', 'baylys' ); ?></span></legend>
							 <input type="text" name="baylys_theme_options[linkhover_color]" value="<?php echo esc_attr( $options['linkhover_color'] ); ?>" id="linkhover-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker3"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom link hover color, the default link hover color is: %s.', 'baylys' ), $default_options['linkhover_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Custom Footer Background Color', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Footer Background Color', 'baylys' ); ?></span></legend>
							 <input type="text" name="baylys_theme_options[footerbg_color]" value="<?php echo esc_attr( $options['footerbg_color'] ); ?>" id="footerbg-color" />
							<div style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;" id="colorpicker2"></div>
							<br />
							<small class="description"><?php printf( __( 'Choose your custom footer background color, the default color is: %s.', 'baylys' ), $default_options['footerbg_color'] ); ?></small>
						</fieldset>
					</td>
				</tr>
				</table>
				
			<table class="form-table">
			<h3 style="margin-top:30px;"><?php _e( 'Layout Options', 'baylys' ); ?></h3>
				<tr valign="top" class="image-radio-option"><th scope="row"><?php _e( 'Sidebar Placement', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Sidebar Placement', 'baylys' ); ?></span></legend>
						<?php
							foreach ( baylys_layouts() as $layout ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="baylys_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>"/>
										<?php echo $layout['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>
			</table>

				<table class="form-table">
				<h3 style="margin-top:30px;"><?php _e( 'Logo, Post Excerpts and Footer Text', 'baylys' ); ?></h3>
				<tr valign="top"><th scope="row"><?php _e( 'Custom Logo', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Logo image', 'baylys' ); ?></span></legend>
							<input class="regular-text" type="text" name="baylys_theme_options[custom_logo]" value="<?php echo esc_attr( $options['custom_logo'] ); ?>" />
						<br/><label class="description" for="baylys_theme_options[custom_logo]"><?php _e('Upload your own logo image using the ', 'baylys'); ?><a href="<?php echo home_url(); ?>/wp-admin/media-new.php" target="_blank"><?php _e('WordPress Media Uploader', 'baylys'); ?></a><?php _e('. Then copy your logo image file URL and insert the URL here.', 'baylys'); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Post Excerpts', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Post Excerpts', 'baylys' ); ?></span></legend>
							<input id="baylys_theme_options[show-excerpt]" name="baylys_theme_options[show-excerpt]" type="checkbox" value="1" <?php checked( '1', $options['show-excerpt'] ); ?> />
							<label class="description" for="baylys_theme_options[show-excerpt]"><?php _e( 'Check this box to show automatic post excerpts. With this option you will not need to add the more tag in posts.', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Footer Text', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Footer text', 'baylys' ); ?></span></legend>
							<textarea id="baylys_theme_options[custom_footertext]" class="small-text" cols="120" rows="3" name="baylys_theme_options[custom_footertext]"><?php echo esc_textarea( $options['custom_footertext'] ); ?></textarea>
						<br/><label class="description" for="baylys_theme_options[custom_footertext]"><?php _e( 'Customize the footer credit text. Standard HTML is allowed.', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>
				
				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Portfolio Category', 'baylys' ); ?></h3>

				<tr valign="top"><th scope="row"><?php _e( 'Choose your Portfolio Category', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Choose your Portfolio Category', 'baylys' ); ?></span></legend>
							<input class="regular-text" type="text" name="baylys_theme_options[portfolio-cat]" value="<?php echo esc_attr( $options['portfolio-cat'] ); ?>" />
							<br/><label class="description" for="baylys_theme_options[portfolio-cat]"><?php _e( 'Type in the category slug of the category you want to use as your portfolio category (You will find the slug in Posts/Categories).', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

			</table>
				
				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Favicon and Apple Touch Icon', 'baylys' ); ?></h3>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Favicon', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Favicon', 'baylys' ); ?></span></legend>
							<input class="regular-text" type="text" name="baylys_theme_options[custom_favicon]" value="<?php echo esc_attr( $options['custom_favicon'] ); ?>" />
						<br/><label class="description" for="baylys_theme_options[custom_favicon]"><?php _e( 'Create a <strong>16x16px</strong> image and generate a .ico favicon using a favicon online generator (e.g. <a href="http://www.faviconr.com/" target="_blank">Faviconr</a>). Now upload your favicon to your themes folder (via FTP) and enter your Favicon URL here (the URL path should be similar to: yourdomain.com/wp-content/themes/baylys/favicon.ico).', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Custom Apple Touch Icon', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Custom Apple Touch Icon', 'baylys' ); ?></span></legend>
							<input class="regular-text" type="text" name="baylys_theme_options[custom_apple_icon]" value="<?php echo esc_attr( $options['custom_apple_icon'] ); ?>" />
						<br/><label class="description" for="baylys_theme_options[custom_apple_icon]"><?php _e('Create a <strong>128x128px png</strong> image for your webclip icon. Upload your image using the ', 'baylys'); ?><a href="<?php echo home_url(); ?>/wp-admin/media-new.php" target="_blank"><?php _e('WordPress Media Uploader', 'baylys'); ?></a><?php _e('. Now copy the image file URL and insert the URL here.', 'baylys'); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>

				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Share Buttons', 'baylys' ); ?></h3>

				<tr valign="top"><th scope="row"><?php _e( 'Share option for posts', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Share option for posts', 'baylys' ); ?></span></legend>
							<input id="baylys_theme_options[share-posts]" name="baylys_theme_options[share-posts]" type="checkbox" value="1" <?php checked( '1', $options['share-posts'] ); ?> />
							<label class="description" for="baylys_theme_options[share-posts]"><?php _e( 'Check this box to include share buttons (for Twitter, Facebook, Google+) on your blogs front page and on single post pages.', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Share option on single posts only', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Share option on single posts only', 'baylys' ); ?></span></legend>
							<input id="baylys_theme_options[share-singleposts]" name="baylys_theme_options[share-singleposts]" type="checkbox" value="1" <?php checked( '1', $options['share-singleposts'] ); ?> />
							<label class="description" for="baylys_theme_options[share-singleposts]"><?php _e( 'Check this box to include the share post buttons <strong>only</strong> on single posts (below the post content).', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Share option for pages', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Share option for pages', 'baylys' ); ?></span></legend>
							<input id="baylys_theme_options[share-pages]" name="baylys_theme_options[share-pages]" type="checkbox" value="1" <?php checked( '1', $options['share-pages'] ); ?> />
							<label class="description" for="baylys_theme_options[share-pages]"><?php _e( 'Check this box to also include the share buttons on pages.', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

				</table>

				<table class="form-table">

				<h3 style="margin-top:30px;"><?php _e( 'Responsive Slider', 'baylys' ); ?></h3>

				<tr valign="top"><th scope="row"><?php _e( 'Include Responsive Slider', 'baylys' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Include Responsive Slider', 'baylys' ); ?></span></legend>
							<input id="baylys_theme_options[use-slider]" name="baylys_theme_options[use-slider]" type="checkbox" value="1" <?php checked( '1', $options['use-slider'] ); ?> />
							<label class="description" for="baylys_theme_options[use-slider]"><?php _e( 'Check this box to inlcude the Responsive Slider WordPress-Plugin below the blog title in the header section of your front page(this can either be your blog or a static page, see Settings/Reading).', 'baylys' ); ?></label>
						</fieldset>
					</td>
				</tr>

			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/*-----------------------------------------------------------------------------------*/
/* Sanitize and validate form input. Accepts an array, return a sanitized array.
/*-----------------------------------------------------------------------------------*/

function baylys_theme_options_validate( $input ) {
	global $layout_options, $font_options;

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
			$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// Link hover color must be 3 or 6 hexadecimal characters
	if ( isset( $input['linkhover_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['linkhover_color'] ) )
			$output['linkhover_color'] = '#' . strtolower( ltrim( $input['linkhover_color'], '#' ) );

	// Footer background color must be 3 or 6 hexadecimal characters
	if ( isset( $input['footerbg_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['footerbg_color'] ) )
			$output['footerbg_color'] = '#' . strtolower( ltrim( $input['footerbg_color'], '#' ) );

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], baylys_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	// Text options must be safe text with no HTML tags
	$input['custom_logo'] = wp_filter_nohtml_kses( $input['custom_logo'] );
	$input['custom_favicon'] = wp_filter_nohtml_kses( $input['custom_favicon'] );
	$input['custom_apple_icon'] = wp_filter_nohtml_kses( $input['custom_apple_icon'] );
	$input['portfolio-cat'] = wp_filter_nohtml_kses( $input['portfolio-cat'] );

	// checkbox values are either 0 or 1
	if ( ! isset( $input['share-posts'] ) )
		$input['share-posts'] = null;
	$input['share-posts'] = ( $input['share-posts'] == 1 ? 1 : 0 );

	if ( ! isset( $input['share-singleposts'] ) )
		$input['share-singleposts'] = null;
	$input['share-singleposts'] = ( $input['share-singleposts'] == 1 ? 1 : 0 );
	
	if ( ! isset( $input['share-pages'] ) )
		$input['share-pages'] = null;
	$input['share-pages'] = ( $input['share-pages'] == 1 ? 1 : 0 );
	
	if ( ! isset( $input['show-excerpt'] ) )
		$input['show-excerpt'] = null;
	$input['show-excerpt'] = ( $input['show-excerpt'] == 1 ? 1 : 0 );
	
	if ( ! isset( $input['use-slider'] ) )
		$input['use-slider'] = null;
	$input['use-slider'] = ( $input['use-slider'] == 1 ? 1 : 0 );

	return $input;
}


/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the current link color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function baylys_print_link_color_style() {
	$options = baylys_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = baylys_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
<style type="text/css">
/* Custom Link Color */
a,
#site-title h1 a:hover,
.entry-header h2.entry-title a:hover,
.author-info .author-details h3 a:hover,
#footerwidget-wrap .widget a:hover,
#footer #site-info a:hover,
#site-nav li a:hover,
#site-nav li li a:hover,
.widget_categories a:hover,
.widget_meta a:hover,
.widget_links a:hover,
.widget_recent_entries a:hover,
.widget_pages a:hover,
.widget_archive a:hover,
.widget_nav_menu a:hover,
.slider-wrap .responsive-slider.flexslider .slide-title a:hover,
#smart-archives-list ul li a:hover,
#smart-archives-fancy ul.archive-list li a:hover,
#content .post.portfolio-element h2.entry-title a:hover {
	color:<?php echo $link_color; ?>;
}
input#submit, 
input.wpcf7-submit,
.format-link .entry-content a.link,
.jetpack_subscription_widget form#subscribe-blog input[type="submit"] {
	background:<?php echo $link_color; ?>;
}
@media screen and (min-width: 1100px) {
#site-nav li a:hover,
#site-nav li li a:hover,
#site-nav li:hover > a,
#site-nav li li:hover > a {
	color: <?php echo $link_color; ?> !important;
}
}
</style>
<?php
}
add_action( 'wp_head', 'baylys_print_link_color_style' );

/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the currentlink hover color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function baylys_print_linkhover_color_style() {
	$options = baylys_get_theme_options();
	$linkhover_color = $options['linkhover_color'];

	$default_options = baylys_get_default_theme_options();

	// Don't do anything if the current  footer widget background color is the default.
	if ( $default_options['linkhover_color'] == $linkhover_color )
		return;
?>
<style type="text/css">
/* Custom Link Hover Color */
a:hover {color:<?php echo $linkhover_color; ?>;}
.format-link .entry-content a.link:hover,
input#submit:hover, 
input.wpcf7-submit:hover,
.jetpack_subscription_widget form#subscribe-blog input[type="submit"]:hover {background:<?php echo $linkhover_color; ?>;}
</style>
<?php
}
add_action( 'wp_head', 'baylys_print_linkhover_color_style' );

/*-----------------------------------------------------------------------------------*/
/* Add a style block to the theme for the current footer background color.
/* 
/* This function is attached to the wp_head action hook.
/*-----------------------------------------------------------------------------------*/

function baylys_print_footerbg_color_style() {
	$options = baylys_get_theme_options();
	$footerbg_color = $options['footerbg_color'];

	$default_options = baylys_get_default_theme_options();

	// Don't do anything if the current  footer widget background color is the default.
	if ( $default_options['footerbg_color'] == $footerbg_color )
		return;
?>
<style type="text/css">
/* Custom Footer Bg Color */
#footer {background:<?php echo $footerbg_color; ?>;}
</style>
<?php
}
add_action( 'wp_head', 'baylys_print_footerbg_color_style' );


/*-----------------------------------------------------------------------------------*/
/* Adds Baylys layout classes to the array of body classes.
/*-----------------------------------------------------------------------------------*/
function baylys_layout_classes( $existing_classes ) {
	$options = baylys_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	else
		$classes = array( 'one-column' );

	$classes[] = $current_layout;

	$classes = apply_filters( 'baylys_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'baylys_layout_classes' );
