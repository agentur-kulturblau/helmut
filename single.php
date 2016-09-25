<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Baylys
 * @since Baylys 1.0
 */

get_header(); ?>

	<div id="main-wrap">
	<div id="content">

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

		<nav id="nav-single" class="clearfix">
			<div class="nav-previous"><?php next_post_link( '%link', __( 'Next Post &raquo;', 'baylys' ) ); ?></div>
			<div class="nav-next"><?php previous_post_link( '%link', __( '&laquo; Previous Post', 'baylys' ) ); ?></div>
		</nav><!-- #nav-below -->

		</div><!-- end #content -->

		<?php get_sidebar(); ?>
	</div><!-- end #main-wrap -->
<?php get_footer(); ?>