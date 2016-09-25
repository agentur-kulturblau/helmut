<?php
/**
 * The template for displaying 404 error pages.
 *
 * @package Baylys
 * @since Baylys 1.0
 */

get_header(); ?>

	<div id="main-wrap">
		<div id="content">

			<article id="post-0" class="post error404 no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Not Found', 'baylys' ); ?></h1>
				</header><!--end .entry-header -->

				<div class="entry-content">
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'baylys' ); ?></p>
				</div><!-- end .entry-content -->
			</article><!-- end #post-0 -->

		</div><!-- end #content -->
	</div><!-- end #main-wrap -->

<?php get_footer(); ?>