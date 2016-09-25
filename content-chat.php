<?php
/**
 * The template for displaying posts in the Chat Post Format
 *
 * @package Baylys
 * @since Baylys 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'baylys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<aside class="entry-details">
			<ul>
				<li><a href="<?php the_permalink(); ?>" class="entry-date"><?php echo get_the_date(); ?></a></li>
				<li class="entry-comments"><?php comments_popup_link( __( '0 comments', 'baylys' ), __( '1 comment', 'baylys' ), __( '% comments', 'baylys' ), 'comments-link', __( '', 'baylys' ) ); ?></li>
				<li class="entry-edit"><?php edit_post_link(__( 'Edit', 'baylys') ); ?></li>
			</ul>
		</aside><!--end .entry-details -->
	</header><!--end .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Read more', 'baylys' ) ); ?>
	</div><!-- end .entry-content -->

	<footer class="entry-meta">
		<ul>
			<li class="entry-cats"><?php the_category(''); ?></li>
			<?php // Include Share-Btns
				$options = get_option('baylys_theme_options');
				if( $options['share-posts'] ) : ?>
				<?php get_template_part( 'share'); ?>
			<?php endif; ?>
		</ul>
	</footer><!-- end .entry-meta -->

</article><!-- end post -<?php the_ID(); ?> -->