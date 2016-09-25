<?php
/**
 * The themes Header file.
 *
 * Displays all of the <head> section and everything up till </header>
 *
 * @package Baylys
 * @since Baylys 1.0
 */
?><!DOCTYPE html>
<!--[if lte IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<?php 
	$options = get_option('baylys_theme_options');
	if( $options['custom_favicon'] != '' ) : ?>
<link rel="shortcut icon" type="image/ico" href="<?php echo $options['custom_favicon']; ?>" />
<?php endif  ?>
<?php 
	$options = get_option('baylys_theme_options');
	if( $options['custom_apple_icon'] != '' ) : ?>
<link rel="apple-touch-icon" href="<?php echo $options['custom_apple_icon']; ?>" />
<?php endif  ?>
<!-- HTML5 enabling script for older IE -->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php
	wp_enqueue_script('jquery');
	if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

	<header id="header" class="clearfix">

	<div id="site-nav-container" class="clearfix">

			<div id="site-title">
					<?php $options = get_option('baylys_theme_options');
						if( $options['custom_logo'] != '' ) : ?>
						<a href="<?php echo home_url( '/' ); ?>" class="logo"><img src="<?php echo $options['custom_logo']; ?>" alt="<?php bloginfo('name'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
					<?php else: ?>
						<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
						<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
					<?php endif  ?>
				</div><!-- end #site-title -->

					<a href="#nav-mobile" id="mobile-menu-btn"><?php _e('Menu', 'baylys') ?></a>
					<nav id="site-nav">
						<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
					</nav><!-- end #site-nav -->
				</div><!-- end #site-nav-container -->
</header><!-- end #header -->

			<?php
				// Check to see if the header image has been removed
				$header_image = get_header_image();
				if ( $header_image ) :
					// Compatibility with versions of WordPress prior to 3.4.
					if ( function_exists( 'get_custom_header' ) ) {
						// We need to figure out what the minimum width should be for our featured image.
						// This result would be the suggested width if the theme were to implement flexible widths.
						$header_image_width = get_theme_support( 'custom-header', 'width' );
					} else {
						$header_image_width = HEADER_IMAGE_WIDTH;
					}
					?>

				<?php
					// The header image
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() && has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( $header_image_width, $header_image_width ) ) ) &&
							$image[1] >= $header_image_width ) :
						// Houston, we have a new header image!
						echo '<div class="header-image">';
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
						echo '</div>';
					else :
						// Compatibility with versions of WordPress prior to 3.4.
						if ( function_exists( 'get_custom_header' ) ) {
							$header_image_width  = get_custom_header()->width;
							$header_image_height = get_custom_header()->height;
						} else {
							$header_image_width  = HEADER_IMAGE_WIDTH;
							$header_image_height = HEADER_IMAGE_HEIGHT;
						}
						?>
					<?php if (is_front_page() ) : ?>
					
					<div class="header-image">
						<img src="<?php header_image(); ?>" width="<?php echo $header_image_width; ?>" height="<?php echo $header_image_height; ?>" alt="" />
					</div><!-- end .header-image -->
				
					<?php endif; ?>
				<?php endif; // end check for featured image or standard header ?>

			<?php endif; // end check for removed header image ?>

			<?php // Include Responsive Slider, see Baylys Theme Options
				$options = get_option('baylys_theme_options');
				if( $options['use-slider'] ) : ?>

				<?php if(is_front_page() ) { ?>
					<div class="slider-wrap">
					<?php echo do_shortcode( '[responsive_slider]' ); ?>
					</div><!-- end .slider-wrap -->
				<?php } ?>

			<?php endif; ?>