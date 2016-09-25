<?php
/**
  * Template Name: Portfolio
 * Description: A Page Template that showcases all Posts in the Category Portfolio
 *
 * @package Baylys
 * @since Baylys 1.0
 */

get_header(); ?>

		<div id="main-wrap">
		<div id="content" class="fullwidth">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'intro' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header><!-- .entry-header -->
			<?php	if ( '' != get_the_content() ); ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->	
			</article><!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; ?>

			<?php $options = get_option('baylys_theme_options');
				if( $options['portfolio-cat'] != '' ) : ?>
					<?php $portfolio = new WP_Query( 'category_name='.$options['portfolio-cat'].'&nopaging=true' ); 	?>
				<?php else : ?>
					<?php $portfolio = new WP_Query( 'category_name=portfolio&nopaging=true' ); ?>
				<?php endif  ?>

				<?php while ( $portfolio->have_posts() ) : $portfolio->the_post(); ?>
					<?php get_template_part( 'content', 'portfolio' ); ?>
				<?php endwhile; // end of the loop. ?>

		<?php wp_reset_postdata(); // reset the query ?>

		</div><!-- end #content .fullwidth -->
	</div><!-- end #main-wrap -->

<?php get_footer(); ?>