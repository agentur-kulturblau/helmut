<?php
/**
 * Baylys functions and definitions
 *
 * @package Baylys
 * @since Baylys 1.0
 */

/*-----------------------------------------------------------------------------------*/
/* Set the content width based on the theme's design and stylesheet.
/*-----------------------------------------------------------------------------------*/

if ( ! isset( $content_width ) )
	$content_width = 780; /* pixels */

/*-----------------------------------------------------------------------------------*/
/* Call JavaScript Scripts for Baylys (Fitvids for elasic videos, Custom and Placeholder)
/*-----------------------------------------------------------------------------------*/

add_action('wp_enqueue_scripts','baylys_scripts_function');
	function baylys_scripts_function() {
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', false, '1.1');
		wp_enqueue_script( 'placeholder', get_template_directory_uri() . '/js/jquery.placeholder.min.js', false, '1.0');
		wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', false, '1.0');
}

/*-----------------------------------------------------------------------------------*/
/* Include Google Webfonts
/*-----------------------------------------------------------------------------------*/

function load_fonts() {
          wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700,800');
          wp_enqueue_style( 'googleFonts');
       }

add_action('wp_print_styles', 'load_fonts');

/*-----------------------------------------------------------------------------------*/
/* Sets up theme defaults and registers support for various WordPress features.
/*-----------------------------------------------------------------------------------*/
/**
 * Tell WordPress to run baylys_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'baylys_setup' );

if ( ! function_exists( 'baylys_setup' ) ):
/**
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override baylys_setup() in a child theme, add your own baylys_setup to your child theme's
 * functions.php file.
 */
function baylys_setup() {

	// Make Baylys available for translation. Translations can be added to the /languages/ directory.
	load_theme_textdomain( 'baylys', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Load up the Baylys theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'status', 'link', 'quote', 'image', 'gallery', 'video', 'audio','chat' ) );

	// This theme uses wp_nav_menu().
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'baylys' ),
		'optional' => __( 'Footer Navigation (no sub menus supported)', 'baylys' )
	) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'baylys_custom_background_args', array(
		'default-color' => 'ffffff',
	) ) );

	// Add support for custom headers.
	$custom_header_support = array(
		'width' => apply_filters( 'baylys_header_image_width', 1400 ),
		'height' => apply_filters( 'baylys_image_height', 550 ),
		'flex-height' => true,
		'random-default' => true,
		'header-text' => false,
		'wp-head-callback' => '',
		'admin-head-callback' => 'baylys_admin_header_style',
		'admin-preview-callback' => 'baylys_admin_header_image',
	);

	add_theme_support( 'custom-header', $custom_header_support );

	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		define( 'HEADER_IMAGE', '' );
		define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
		add_custom_image_header( $custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback'] );
	}

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

	// Add Baylys's custom image sizes.
	add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'summer' => array(
			'url' => '%s/images/headers/summer.jpg',
			'thumbnail_url' => '%s/images/headers/summer-thumbnail.jpg',
			'description' => __( 'Summer', 'baylys' )
		),
		'boats' => array(
			'url' => '%s/images/headers/boats.jpg',
			'thumbnail_url' => '%s/images/headers/boats-thumbnail.jpg',
			'description' => __( 'Boats', 'baylys' )
		),
		'sand' => array(
			'url' => '%s/images/headers/sand.jpg',
			'thumbnail_url' => '%s/images/headers/sand-thumbnail.jpg',
			'description' => __( 'Sand', 'baylys' )
		),
		'cactus' => array(
			'url' => '%s/images/headers/cactus.jpg',
			'thumbnail_url' => '%s/images/headers/cactus-thumbnail.jpg',
			'description' => __( 'Cactus', 'baylys' )
		)
	) );
}
endif; // baylys_setup

if ( ! function_exists( 'baylys_admin_header_style' ) ) :

/*-----------------------------------------------------------------------------------*/
/* Styles the header image displayed on the Appearance > Header admin panel.
/*-----------------------------------------------------------------------------------*/

function baylys_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg img {
		max-width: 1132px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // baylys_admin_header_style

if ( ! function_exists( 'baylys_admin_header_image' ) ) :

/*-----------------------------------------------------------------------------------*/
/* Custom header image markup displayed on the Appearance > Header admin panel.
/*-----------------------------------------------------------------------------------*/

function baylys_admin_header_image() { ?>
	<div id="headimg">
		<?php
		$image = get_header_image();
		if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // baylys_admin_header_image


/*-----------------------------------------------------------------------------------*/
/* Creates a nicely formatted and more specific title element text
/* for output in head of document, based on current view.
/*-----------------------------------------------------------------------------------*/

function baylys_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'baylys' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'baylys_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
/*-----------------------------------------------------------------------------------*/

function baylys_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'baylys_page_menu_args' );

/*-----------------------------------------------------------------------------------*/
/* Sets the post excerpt length to 35 characters.
/*-----------------------------------------------------------------------------------*/

function baylys_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'baylys_excerpt_length' );

/*-----------------------------------------------------------------------------------*/
/* Returns a "Continue Reading" link for excerpts
/*-----------------------------------------------------------------------------------*/

function baylys_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Read more', 'baylys' ) . '</a>';
}

/*-----------------------------------------------------------------------------------*/
/* Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and baylys_continue_reading_link().
/*
/* To override this in a child theme, remove the filter and add your own
/* function tied to the excerpt_more filter hook.
/*-----------------------------------------------------------------------------------*/

function baylys_auto_excerpt_more( $more ) {
	return ' &hellip;' . baylys_continue_reading_link();
}
add_filter( 'excerpt_more', 'baylys_auto_excerpt_more' );

/*-----------------------------------------------------------------------------------*/
/* Adds a pretty "Continue Reading" link to custom post excerpts.
/*
/* To override this link in a child theme, remove the filter and add your own
/* function tied to the get_the_excerpt filter hook.
/*-----------------------------------------------------------------------------------*/

function baylys_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= baylys_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'baylys_custom_excerpt_more' );

/*-----------------------------------------------------------------------------------*/
/* Remove inline styles printed when the gallery shortcode is used.
/*-----------------------------------------------------------------------------------*/

function baylys_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'baylys_remove_gallery_css' );


if ( ! function_exists( 'baylys_comment' ) ) :
/*-----------------------------------------------------------------------------------*/
/* Comments template waipoua_comment
/*-----------------------------------------------------------------------------------*/

function baylys_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">

			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 60 ); ?>
			</div>

<div class="comment-content">
				<ul class="comment-meta">
					<li class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'baylys' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></li>
					<li class="comment-author"><?php printf( __( '%s', 'baylys' ), sprintf( '%s', get_comment_author_link() ) ); ?></li>
					<li class="comment-time"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s &#64; %2$s', 'baylys' ),
						get_comment_date('j. F Y'),
						get_comment_time() );
					?></a></li>
					<li class="comment-edit"><?php edit_comment_link( __( 'Edit', 'baylys' ), ' ' );?></li>
				</ul>
					<div class="comment-text">
						<?php comment_text(); ?>

						<?php if ( $comment->comment_approved == '0' ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'baylys' ); ?></p>
						<?php endif; ?>
					</div><!-- end .comment-text -->

			</div><!-- end .comment-content -->

		</article><!-- end .comment -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="pingback">
		<p><?php _e( '<span>Pingback:</span>', 'baylys' ); ?> <?php comment_author_link(); ?></p>
		<p><?php edit_comment_link( __('Edit', 'baylys'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Register widgetized areas
/*-----------------------------------------------------------------------------------*/

function baylys_widgets_init() {

	register_sidebar( array (
		'name' => __( 'Main Sidebar', 'baylys' ),
		'id' => 'sidebar-1',
		'description' => __( 'Widgets will appear in the main sidebar on posts and pages.', 'baylys' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Column 1', 'baylys' ),
		'id' => 'sidebar-2',
		'description' => __( 'Widgets will appear in the first column of the optional 4-column footer widget area.', 'baylys' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Column 2', 'baylys' ),
		'id' => 'sidebar-3',
		'description' => __( 'Widgets will appear in the second column of the optional 4-column footer widget area.', 'baylys' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Column 3', 'baylys' ),
		'id' => 'sidebar-4',
		'description' => __( 'Widgets will appear in the third column of the optional 4-column footer widget area.', 'baylys' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Column 4', 'baylys' ),
		'id' => 'sidebar-5',
		'description' => __( 'Widgets will appear in the fourth column of the optional 4-column footer widget area.', 'baylys' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action( 'init', 'baylys_widgets_init' );


if ( ! function_exists( 'baylys_content_nav' ) ) :

/*-----------------------------------------------------------------------------------*/
/* Display navigation to next/previous pages when applicable
/*-----------------------------------------------------------------------------------*/

function baylys_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="clearfix">
				<div class="nav-previous"><?php next_posts_link( __( '&laquo; Older Posts', 'baylys'  ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer Posts &raquo;', 'baylys' ) ); ?></div>
			</nav><!-- end #nav-below -->
	<?php endif;
}

endif; // baylys_content_nav

/*-----------------------------------------------------------------------------------*/
/* Removes the default CSS style from the WP image gallery
/*-----------------------------------------------------------------------------------*/
add_filter('gallery_style', create_function('$a', 'return "
<div class=\'gallery\'>";'));

/*-----------------------------------------------------------------------------------*/
/* Baylys Shortcodes
/*-----------------------------------------------------------------------------------*/

// Enable shortcodes in widget areas
add_filter( 'widget_text', 'do_shortcode' );

// Replace WP autop formatting
if (!function_exists( "baylys_remove_wpautop")) {
	function baylys_remove_wpautop($content) {
		$content = do_shortcode( shortcode_unautop( $content ) );
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Multi Columns Shortcodes
/* Don't forget to add "_last" behind the shortcode if it is the last column.
/*-----------------------------------------------------------------------------------*/

// Two Columns
function baylys_shortcode_two_columns_one( $atts, $content = null ) {
   return '<div class="two-columns-one">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'two_columns_one', 'baylys_shortcode_two_columns_one' );

function baylys_shortcode_two_columns_one_last( $atts, $content = null ) {
   return '<div class="two-columns-one last">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'two_columns_one_last', 'baylys_shortcode_two_columns_one_last' );

// Three Columns
function baylys_shortcode_three_columns_one($atts, $content = null) {
   return '<div class="three-columns-one">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_one', 'baylys_shortcode_three_columns_one' );

function baylys_shortcode_three_columns_one_last($atts, $content = null) {
   return '<div class="three-columns-one last">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_one_last', 'baylys_shortcode_three_columns_one_last' );

function baylys_shortcode_three_columns_two($atts, $content = null) {
   return '<div class="three-columns-two">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_two', 'baylys_shortcode_three_columns' );

function baylys_shortcode_three_columns_two_last($atts, $content = null) {
   return '<div class="three-columns-two last">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_two_last', 'baylys_shortcode_three_columns_two_last' );

// Four Columns
function baylys_shortcode_four_columns_one($atts, $content = null) {
   return '<div class="four-columns-one">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_one', 'baylys_shortcode_four_columns_one' );

function baylys_shortcode_four_columns_one_last($atts, $content = null) {
   return '<div class="four-columns-one last">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_one_last', 'baylys_shortcode_four_columns_one_last' );

function baylys_shortcode_four_columns_two($atts, $content = null) {
   return '<div class="four-columns-two">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_two', 'baylys_shortcode_four_columns_two' );

function baylys_shortcode_four_columns_two_last($atts, $content = null) {
   return '<div class="four-columns-two last">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_two_last', 'baylys_shortcode_four_columns_two_last' );

function baylys_shortcode_four_columns_three($atts, $content = null) {
   return '<div class="four-columns-three">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_three', 'baylys_shortcode_four_columns_three' );

function baylys_shortcode_four_columns_three_last($atts, $content = null) {
   return '<div class="four-columns-three last">' . baylys_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_three_last', 'baylys_shortcode_four_columns_three_last' );

// Divide Text Shortcode
function baylys_shortcode_divider($atts, $content = null) {
   return '<div class="divider"></div>';
}
add_shortcode( 'divider', 'baylys_shortcode_divider' );

/*-----------------------------------------------------------------------------------*/
/* Text Highlight and Info Boxes Shortcodes
/*-----------------------------------------------------------------------------------*/

function baylys_shortcode_white_box($atts, $content = null) {
   return '<div class="white-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'white_box', 'baylys_shortcode_white_box' );

function baylys_shortcode_yellow_box($atts, $content = null) {
   return '<div class="yellow-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'yellow_box', 'baylys_shortcode_yellow_box' );

function baylys_shortcode_red_box($atts, $content = null) {
   return '<div class="red-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'red_box', 'baylys_shortcode_red_box' );

function baylys_shortcode_blue_box($atts, $content = null) {
   return '<div class="blue-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'blue_box', 'baylys_shortcode_blue_box' );

function baylys_shortcode_green_box($atts, $content = null) {
   return '<div class="green-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'green_box', 'baylys_shortcode_green_box' );

function baylys_shortcode_lightgrey_box($atts, $content = null) {
   return '<div class="lightgrey-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'lightgrey_box', 'baylys_shortcode_lightgrey_box' );

function baylys_shortcode_grey_box($atts, $content = null) {
   return '<div class="grey-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'grey_box', 'baylys_shortcode_grey_box' );

function baylys_shortcode_dark_box($atts, $content = null) {
   return '<div class="dark-box">' . do_shortcode( baylys_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'dark_box', 'baylys_shortcode_dark_box' );

/*-----------------------------------------------------------------------------------*/
/* General Buttons Shortcodes
/*-----------------------------------------------------------------------------------*/

function baylys_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'link'	=> '#',
    'target' => '',
    'color'	=> '',
    'size'	=> '',
	 'form'	=> '',
	 'style'	=> '',
    ), $atts));

	$color = ($color) ? ' '.$color. '-btn' : '';
	$size = ($size) ? ' '.$size. '-btn' : '';
	$form = ($form) ? ' '.$form. '-btn' : '';
	$target = ($target == 'blank') ? ' target="_blank"' : '';

	$out = '<a' .$target. ' class="standard-btn' .$color.$size.$form. '" href="' .$link. '"><span>' .do_shortcode($content). '</span></a>';

    return $out;
}
add_shortcode('button', 'baylys_button');


/*-----------------------------------------------------------------------------------*/
/* Include Baylys Flickr Widget
/*-----------------------------------------------------------------------------------*/

class baylys_flickr extends WP_Widget {

	function baylys_flickr() {
		$widget_ops = array('description' => 'Show your Flickr preview images' , 'baylys');

		parent::WP_Widget(false, __('Baylys Flickr', 'baylys'),$widget_ops);
	}

	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$id = $instance['id'];
		$linktext = $instance['linktext'];
		$linkurl = $instance['linkurl'];
		$number = $instance['number'];
		$type = $instance['type'];
		$sorting = $instance['sorting'];

		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

        <div class="flickr_badge_wrapper"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=<?php echo $sorting; ?>&amp;&amp;source=<?php echo $type; ?>&amp;<?php echo $type; ?>=<?php echo $id; ?>&amp;size=s&amp;layout=v"></script>
		  <div class="clear"></div>
		  <?php if($linktext == ''){echo '';} else {echo '<div class="flickr-bottom"><a href="'.$linkurl.'" class="flickr-home" target="_blank">'.$linktext.'</a></div>';}?>
		</div><!-- end .flickr_badge_wrapper -->

	   <?php
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$id = esc_attr($instance['id']);
		$linktext = esc_attr($instance['linktext']);
		$linkurl = esc_attr($instance['linkurl']);
		$number = esc_attr($instance['number']);
		$type = esc_attr($instance['type']);
		$sorting = esc_attr($instance['sorting']);
		?>

		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $id; ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>

		  <p>
            <label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Flickr Profile Link Text:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linktext'); ?>" value="<?php echo $linktext; ?>" class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('Flickr Profile URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linkurl'); ?>" value="<?php echo $linkurl; ?>" class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" />
        </p>

       	<p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos:','baylys'); ?></label>
            <select name="<?php echo $this->get_field_name('number'); ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>">
                <?php for ( $i = 1; $i <= 10; $i += 1) { ?>
                <option value="<?php echo $i; ?>" <?php if($number == $i){ echo "selected='selected'";} ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Choose user or group:','baylys'); ?></label>
            <select name="<?php echo $this->get_field_name('type'); ?>" class="widefat" id="<?php echo $this->get_field_id('type'); ?>">
                <option value="user" <?php if($type == "user"){ echo "selected='selected'";} ?>><?php _e('User', 'baylys'); ?></option>
                <option value="group" <?php if($type == "group"){ echo "selected='selected'";} ?>><?php _e('Group', 'baylys'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sorting'); ?>"><?php _e('Show latest or random pictures:','baylys'); ?></label>
            <select name="<?php echo $this->get_field_name('sorting'); ?>" class="widefat" id="<?php echo $this->get_field_id('sorting'); ?>">
                <option value="latest" <?php if($sorting == "latest"){ echo "selected='selected'";} ?>><?php _e('Latest', 'baylys'); ?></option>
                <option value="random" <?php if($sorting == "random"){ echo "selected='selected'";} ?>><?php _e('Random', 'baylys'); ?></option>
            </select>
        </p>
		<?php
	}
}

register_widget('baylys_flickr');

/*-----------------------------------------------------------------------------------*/
/* Include Baylys About Widget
/*-----------------------------------------------------------------------------------*/

class baylys_about extends WP_Widget {

	function baylys_about() {
		$widget_ops = array('description' => 'About widget with picture and intro text' , 'baylys');

		parent::WP_Widget(false, __('Baylys About', 'baylys'),$widget_ops);
	}

	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$imageurl = $instance['imageurl'];
		$imagewidth = $instance['imagewidth'];
		$imageheight = $instance['imageheight'];
		$abouttext = $instance['abouttext'];

		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

			<div class="about-image-wrap">
			<img src="<?php echo $imageurl; ?>" width="<?php echo $imagewidth; ?>" height="<?php echo $imageheight; ?>" class="about-image">
			</div><!-- end .about-image-wrap -->
			<div class="about-text-wrap">
			<p class="about-text"><?php echo $abouttext; ?></p>
			</div><!-- end .about-text-wrap -->
	   <?php
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$imageurl = esc_attr($instance['imageurl']);
		$imagewidth = esc_attr($instance['imagewidth']);
		$imageheight = esc_attr($instance['imageheight']);
		$abouttext = esc_attr($instance['abouttext']);
		?>

		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

		  <p>
            <label for="<?php echo $this->get_field_id('imageurl'); ?>"><?php _e('Image URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('imageurl'); ?>" value="<?php echo $imageurl; ?>" class="widefat" id="<?php echo $this->get_field_id('imageurl'); ?>" />
        </p>

		  <p>
            <label for="<?php echo $this->get_field_id('imagewidth'); ?>"><?php _e('Image Width (only value, no px):','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('imagewidth'); ?>" value="<?php echo $imagewidth; ?>" class="widefat" id="<?php echo $this->get_field_id('imagewidth'); ?>" />
        </p>

		   <p>
            <label for="<?php echo $this->get_field_id('imageheight'); ?>"><?php _e('Image Height (only value, no px):','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('imageheight'); ?>" value="<?php echo $imageheight; ?>" class="widefat" id="<?php echo $this->get_field_id('imageheight'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('abouttext'); ?>"><?php _e('About Text:','baylys'); ?></label>
           <textarea name="<?php echo $this->get_field_name('abouttext'); ?>" class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('abouttext'); ?>"><?php echo( $abouttext ); ?></textarea>
        </p>

		<?php
	}
}

register_widget('baylys_about');

/*-----------------------------------------------------------------------------------*/
/* Include Baylys Video Widget
/*-----------------------------------------------------------------------------------*/

class baylys_video extends WP_Widget {

	function baylys_video() {
		$widget_ops = array('description' => 'Show a featured video' , 'baylys');

		parent::WP_Widget(false, __('Baylys Featured Video', 'baylys'),$widget_ops);
	}

	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$embedcode = $instance['embedcode'];

		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

        <div class="video_widget">
		  <div class="featured-video"><?php echo $embedcode; ?></div>
		  </div><!-- end .video_widget -->

	   <?php
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$embedcode = esc_attr($instance['embedcode']);
		?>

		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Video embed code:','baylys'); ?></label>
				<textarea name="<?php echo $this->get_field_name('embedcode'); ?>" class="widefat" rows="6" id="<?php echo $this->get_field_id('embedcode'); ?>"><?php echo( $embedcode ); ?></textarea>
        </p>

		<?php
	}
}

register_widget('baylys_video');

/*-----------------------------------------------------------------------------------*/
/* Including Baylys Social Links Widget
/*-----------------------------------------------------------------------------------*/

 class baylys_sociallinks extends WP_Widget {

	function baylys_sociallinks() {
		$widget_ops = array('description' => 'Link to your social profile sites' , 'baylys');

		parent::WP_Widget(false, __('Baylys Social Links', 'baylys'),$widget_ops);
	}

	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$twitter = $instance['twitter'];
		$facebook = $instance['facebook'];
		$googleplus = $instance['googleplus'];
		$flickr = $instance['flickr'];
		$instagram = $instance['instagram'];
		$picasa = $instance['picasa'];
		$fivehundredpx = $instance['fivehundredpx'];
		$youtube = $instance['youtube'];
		$vimeo = $instance['vimeo'];
		$dribbble = $instance['dribbble'];
		$ffffound = $instance['ffffound'];
		$pinterest = $instance['pinterest'];
		$behance = $instance['behance'];
		$deviantart = $instance['deviantart'];
		$squidoo = $instance['squidoo'];
		$slideshare = $instance['slideshare'];
		$lastfm = $instance['lastfm'];
		$grooveshark = $instance['grooveshark'];
		$soundcloud = $instance['soundcloud'];
		$foursquare = $instance['foursquare'];
		$github = $instance['github'];
		$linkedin = $instance['linkedin'];
		$xing = $instance['xing'];
		$wordpress = $instance['wordpress'];
		$tumblr = $instance['tumblr'];
		$rss = $instance['rss'];
		$rsscomments = $instance['rsscomments'];
		$target = $instance['target'];


		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

        <ul class="sociallinks">
			<?php
			if($twitter != '' && $target != ''){
				echo '<li><a href="'.$twitter.'" class="twitter" title="Twitter" target="_blank">Twitter</a></li>';
			} elseif($twitter != '') {
				echo '<li><a href="'.$twitter.'" class="twitter" title="Twitter">Twitter</a></li>';
			}
			?>

			<?php
			if($facebook != '' && $target != ''){
				echo '<li><a href="'.$facebook.'" class="facebook" title="Facebook" target="_blank">Facebook</a></li>';
			} elseif($facebook != '') {
				echo '<li><a href="'.$facebook.'" class="facebook" title="Facebook">Facebook</a></li>';
			}
			?>

			<?php
			if($googleplus != '' && $target != ''){
				echo '<li><a href="'.$googleplus.'" class="googleplus" title="Google+" target="_blank">Google+</a></li>';
			} elseif($googleplus != '') {
				echo '<li><a href="'.$googleplus.'" class="googleplus" title="Google+">Google+</a></li>';
			}
			?>

			<?php if($flickr != '' && $target != ''){
				echo '<li><a href="'.$flickr.'" class="flickr" title="Flickr" target="_blank">Flickr</a></li>';
			} elseif($flickr != '') {
				echo '<li><a href="'.$flickr.'" class="flickr" title="Flickr">Flickr</a></li>';
			}
			?>

			<?php if($instagram != '' && $target != ''){
				echo '<li><a href="'.$instagram.'" class="instagram" title="Instagram" target="_blank">Instagram</a></li>';
			} elseif($instagram != '') {
				echo '<li><a href="'.$instagram.'" class="instagram" title="Instagram">Instagram</a></li>';
			}
			?>

			<?php if($picasa != '' && $target != ''){
				echo '<li><a href="'.$picasa.'" class="picasa" title="Picasa" target="_blank">Picasa</a></li>';
			} elseif($picasa != '') {
				echo '<li><a href="'.$picasa.'" class="picasa" title="Picasa">Picasa</a></li>';
			}
			?>

			<?php if($fivehundredpx != '' && $target != ''){
				echo '<li><a href="'.$fivehundredpx.'" class="fivehundredpx" title="500px" target="_blank">500px</a></li>';
			} elseif($fivehundredpx != '') {
				echo '<li><a href="'.$fivehundredpx.'" class="fivehundredpx" title="500px">500px</a></li>';
			}
			?>

			<?php if($youtube != '' && $target != ''){
			echo '<li><a href="'.$youtube.'" class="youtube" title="YouTube" target="_blank">YouTube</a></li>';
			} elseif($youtube != '') {
				echo '<li><a href="'.$youtube.'" class="youtube" title="YouTube">YouTube</a></li>';
			}
			?>

			<?php if($vimeo != '' && $target != ''){
			echo '<li><a href="'.$vimeo.'" class="vimeo" title="Vimeo" target="_blank">Vimeo</a></li>';
			} elseif($vimeo != '') {
				echo '<li><a href="'.$vimeo.'" class="vimeo" title="Vimeo">Vimeo</a></li>';
			}
			?>

			<?php if($dribbble != '' && $target != ''){
			echo '<li><a href="'.$dribbble.'" class="dribbble" title="Dribbble" target="_blank">Dribbble</a></li>';
			} elseif($dribbble != '') {
				echo '<li><a href="'.$dribbble.'" class="dribbble" title="Dribbble">Dribbble</a></li>';
			}
			?>

			<?php if($ffffound != '' && $target != ''){
			echo '<li><a href="'.$ffffound.'" class="ffffound" title="Ffffound" target="_blank">Ffffound</a></li>';
			} elseif($ffffound != '') {
				echo '<li><a href="'.$ffffound.'" class="ffffound" title="Ffffound">Ffffound</a></li>';
			}
			?>

			<?php if($pinterest != '' && $target != ''){
			echo '<li><a href="'.$pinterest.'" class="pinterest" title="Pinterest" target="_blank">Pinterest</a></li>';
			} elseif($pinterest != '') {
				echo '<li><a href="'.$pinterest.'" class="pinterest" title="Pinterest">Pinterest</a></li>';
			}
			?>

			<?php if($behance != '' && $target != ''){
				echo '<li><a href="'.$behance.'" class="behance" title="Behance Network" target="_blank">Behance Network</a></li>';
			} elseif($behance != '') {
				echo '<li><a href="'.$behance.'" class="behance" title="Behance Network">Behance Network</a></li>';
			}
			?>

			<?php if($deviantart != '' && $target != ''){
				echo '<li><a href="'.$deviantart.'" class="deviantart" title="deviantART" target="_blank">deviantART</a></li>';
			} elseif($deviantart != '') {
				echo '<li><a href="'.$deviantart.'" class="deviantart" title="deviantART">deviantART</a></li>';
			}
			?>

			<?php if($squidoo != '' && $target != ''){
				echo '<li><a href="'.$squidoo.'" class="squidoo" title="Squidoo" target="_blank">Squidoo</a></li>';
			} elseif($squidoo != '') {
				echo '<li><a href="'.$squidoo.'" class="squidoo" title="Squidoo">Squidoo</a></li>';
			}
			?>

			<?php if($slideshare != '' && $target != ''){
				echo '<li><a href="'.$slideshare.'" class="slideshare" title="Slideshare" target="_blank">Slideshare</a></li>';
			} elseif($slideshare != '') {
				echo '<li><a href="'.$slideshare.'" class="slideshare" title="Slideshare">Slideshare</a></li>';
			}
			?>

			<?php if($lastfm != '' && $target != ''){
				echo '<li><a href="'.$lastfm.'" class="lastfm" title="Lastfm" target="_blank">Lastfm</a></li>';
			} elseif($lastfm != '') {
				echo '<li><a href="'.$lastfm.'" class="lastfm" title="Lastfm">Lastfm</a></li>';
			}
			?>

			<?php if($grooveshark != '' && $target != ''){
				echo '<li><a href="'.$grooveshark.'" class="grooveshark" title="Grooveshark" target="_blank">Grooveshark</a></li>';
			} elseif($grooveshark != '') {
				echo '<li><a href="'.$grooveshark.'" class="grooveshark" title="Grooveshark">Grooveshark</a></li>';
			}
			?>

			<?php if($soundcloud != '' && $target != ''){
				echo '<li><a href="'.$soundcloud.'" class="soundcloud" title="Soundcloud" target="_blank">Soundcloud</a></li>';
			} elseif($soundcloud != '') {
				echo '<li><a href="'.$soundcloud.'" class="soundcloud" title="Soundcloud">Soundcloud</a></li>';
			}
			?>

			<?php if($foursquare != '' && $target != ''){
				echo '<li><a href="'.$foursquare.'" class="foursquare" title="Foursquare" target="_blank">Foursquare</a></li>';
			} elseif($foursquare != '') {
				echo '<li><a href="'.$foursquare.'" class="foursquare" title="Foursquare">Foursquare</a></li>';
			}
			?>

			<?php if($github != '' && $target != ''){
				echo '<li><a href="'.$github.'" class="github" title="GitHub" target="_blank">GitHub</a></li>';
			} elseif($github != '') {
				echo '<li><a href="'.$github.'" class="github" title="GitHub">GitHub</a></li>';
			}
			?>

			<?php if($linkedin != '' && $target != ''){
				echo '<li><a href="'.$linkedin.'" class="linkedin" title="LinkedIn" target="_blank">LinkedIn</a></li>';
			} elseif($linkedin != '') {
				echo '<li><a href="'.$linkedin.'" class="linkedin" title="LinkedIn">LinkedIn</a></li>';
			}
			?>

			<?php if($xing != '' && $target != ''){
				echo '<li><a href="'.$xing.'" class="xing" title="Xing" target="_blank">Xing</a></li>';
			} elseif($xing != '') {
				echo '<li><a href="'.$xing.'" class="xing" title="Xing">Xing</a></li>';
			}
			?>

			<?php if($wordpress != '' && $target != ''){
				echo '<li><a href="'.$wordpress.'" class="wordpress" title="WordPress" target="_blank">WordPress</a></li>';
			} elseif($wordpress != '') {
				echo '<li><a href="'.$wordpress.'" class="wordpress" title="WordPress">WordPress</a></li>';
			}
			?>

			<?php if($tumblr != '' && $target != ''){
				echo '<li><a href="'.$tumblr.'" class="tumblr" title="Tumblr" target="_blank">Tumblr</a></li>';
			} elseif($tumblr != '') {
				echo '<li><a href="'.$tumblr.'" class="tumblr" title="Tumblr">Tumblr</a></li>';
			}
			?>

			<?php if($rss != '' && $target != ''){
				echo '<li><a href="'.$rss.'" class="rss" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			} elseif($rss != '') {
				echo '<li><a href="'.$rss.'" class="rss" title="RSS Feed">RSS Feed</a></li>';
			}
			?>

			<?php if($rsscomments != '' && $target != ''){
				echo '<li><a href="'.$rsscomments.'" class="rsscomments" title="RSS Comments" target="_blank">RSS Comments</a></li>';
			} elseif($rsscomments != '') {
				echo '<li><a href="'.$rsscomments.'" class="rsscomments" title="RSS Comments">RSS Comments</a></li>';
			}
			?>

		</ul><!-- end .sociallinks -->

	   <?php
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$twitter = esc_attr($instance['twitter']);
		$facebook = esc_attr($instance['facebook']);
		$googleplus = esc_attr($instance['googleplus']);
		$flickr = esc_attr($instance['flickr']);
		$instagram = esc_attr($instance['instagram']);
		$picasa = esc_attr($instance['picasa']);
		$fivehundredpx = esc_attr($instance['fivehundredpx']);
		$youtube = esc_attr($instance['youtube']);
		$vimeo = esc_attr($instance['vimeo']);
		$dribbble = esc_attr($instance['dribbble']);
		$ffffound = esc_attr($instance['ffffound']);
		$pinterest = esc_attr($instance['pinterest']);
		$behance = esc_attr($instance['behance']);
		$deviantart = esc_attr($instance['deviantart']);
		$squidoo = esc_attr($instance['squidoo']);
		$slideshare = esc_attr($instance['slideshare']);
		$lastfm = esc_attr($instance['lastfm']);
		$grooveshark = esc_attr($instance['grooveshark']);
		$soundcloud = esc_attr($instance['soundcloud']);
		$foursquare = esc_attr($instance['foursquare']);
		$github = esc_attr($instance['github']);
		$linkedin = esc_attr($instance['linkedin']);
		$xing = esc_attr($instance['xing']);
		$wordpress = esc_attr($instance['wordpress']);
		$tumblr = esc_attr($instance['tumblr']);
		$rss = esc_attr($instance['rss']);
		$rsscomments = esc_attr($instance['rsscomments']);
		$target = esc_attr($instance['target']);

		?>

		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $twitter; ?>" class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $facebook; ?>" class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+ URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $googleplus; ?>" class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('flickr'); ?>" value="<?php echo $flickr; ?>" class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" />
        </p>

		 <p>
            <label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e('Instagram URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('instagram'); ?>" value="<?php echo $instagram; ?>" class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('picasa'); ?>"><?php _e('Picasa URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('picasa'); ?>" value="<?php echo $picasa; ?>" class="widefat" id="<?php echo $this->get_field_id('picasa'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('fivehundredpx'); ?>"><?php _e('500px URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('fivehundredpx'); ?>" value="<?php echo $fivehundredpx; ?>" class="widefat" id="<?php echo $this->get_field_id('fivehundredpx'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $youtube; ?>" class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('vimeo'); ?>"><?php _e('Vimeo URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('vimeo'); ?>" value="<?php echo $vimeo; ?>" class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('dribbble'); ?>"><?php _e('Dribbble URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('dribbble'); ?>" value="<?php echo $dribbble; ?>" class="widefat" id="<?php echo $this->get_field_id('dribbble'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('ffffound'); ?>"><?php _e('Ffffound URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('ffffound'); ?>" value="<?php echo $ffffound; ?>" class="widefat" id="<?php echo $this->get_field_id('ffffound'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('pinterest'); ?>" value="<?php echo $pinterest; ?>" class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('behance'); ?>"><?php _e('Behance Network URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('behance'); ?>" value="<?php echo $behance; ?>" class="widefat" id="<?php echo $this->get_field_id('behance'); ?>" />
        </p>

		 <p>
            <label for="<?php echo $this->get_field_id('deviantart'); ?>"><?php _e('deviantART URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('deviantart'); ?>" value="<?php echo $deviantart; ?>" class="widefat" id="<?php echo $this->get_field_id('deviantart'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('squidoo'); ?>"><?php _e('Squidoo URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('squidoo'); ?>" value="<?php echo $squidoo; ?>" class="widefat" id="<?php echo $this->get_field_id('squidoo'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('slideshare'); ?>"><?php _e('Slideshare URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('slideshare'); ?>" value="<?php echo $slideshare; ?>" class="widefat" id="<?php echo $this->get_field_id('slideshare'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('lastfm'); ?>"><?php _e('Last.fm URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('lastfm'); ?>" value="<?php echo $lastfm; ?>" class="widefat" id="<?php echo $this->get_field_id('lastfm'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('grooveshark'); ?>"><?php _e('Grooveshark URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('grooveshark'); ?>" value="<?php echo $grooveshark; ?>" class="widefat" id="<?php echo $this->get_field_id('grooveshark'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('soundcloud'); ?>"><?php _e('Soundcloud URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('soundcloud'); ?>" value="<?php echo $soundcloud; ?>" class="widefat" id="<?php echo $this->get_field_id('soundcloud'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('foursquare'); ?>"><?php _e('Foursquare URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('foursquare'); ?>" value="<?php echo $foursquare; ?>" class="widefat" id="<?php echo $this->get_field_id('foursquare'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('github'); ?>"><?php _e('GitHub URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('github'); ?>" value="<?php echo $github; ?>" class="widefat" id="<?php echo $this->get_field_id('github'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $linkedin; ?>" class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('xing'); ?>"><?php _e('Xing URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('xing'); ?>" value="<?php echo $xing; ?>" class="widefat" id="<?php echo $this->get_field_id('xing'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('wordpress'); ?>"><?php _e('WordPress URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('wordpress'); ?>" value="<?php echo $wordpress; ?>" class="widefat" id="<?php echo $this->get_field_id('wordpress'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('tumblr'); ?>"><?php _e('Tumblr URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('tumblr'); ?>" value="<?php echo $tumblr; ?>" class="widefat" id="<?php echo $this->get_field_id('tumblr'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS-Feed URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('rss'); ?>" value="<?php echo $rss; ?>" class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('rsscomments'); ?>"><?php _e('RSS for Comments URL:','baylys'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('rsscomments'); ?>" value="<?php echo $rsscomments; ?>" class="widefat" id="<?php echo $this->get_field_id('rsscomments'); ?>" />
        </p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['target'], true ); ?> id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" <?php checked( $target, 'on' ); ?>> <?php _e('Open all links in a new browser tab', 'baylys'); ?></input>
		</p>

		<?php
	}
}

register_widget('baylys_sociallinks');