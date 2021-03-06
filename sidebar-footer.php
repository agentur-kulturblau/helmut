<?php
/**
 * The Footer widget areas.
 *
 * @package Baylys
 * @since Baylys 1.0
 */
?>

<?php
	/* Check if any of the footer widget areas have widgets.
	 *
	 * If none of the footer widget areas have widgets, let's bail early.
	 */
	if (   ! is_active_sidebar( 'sidebar-2' )
		&& ! is_active_sidebar( 'sidebar-3' )
		&& ! is_active_sidebar( 'sidebar-4' )
		&& ! is_active_sidebar( 'sidebar-5' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="footerwidget-wrap" class="clearfix">
	<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<div id="footer-sidebar-1" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div><!-- .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
	<div id="footer-sidebar-2" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
	</div><!-- .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
	<div id="footer-sidebar-3" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-4' ); ?>
	</div><!-- .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
	<div id="footer-sidebar-4" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-5' ); ?>
	</div><!-- .widget-area -->
	<?php endif; ?>

</div><!-- #footer-widget-wrap -->