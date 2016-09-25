<?php
/**
 * The template for displaying posts in the Link Post Format
 *
 * @package Baylys
 * @since Baylys 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<aside class="entry-details">
			<ul>
				<li><a href="<?php the_permalink(); ?>" class="entry-date"><?php echo get_the_date(); ?></a></li>
				<li class="entry-comments"><?php comments_popup_link( __( '0 comments', 'baylys' ), __( '1 comment', 'baylys' ), __( '% comments', 'baylys' ), 'comments-link', __( '', 'baylys' ) ); ?></li>
				<li class="entry-edit"><?php edit_post_link(__( 'Edit', 'baylys') ); ?></li>
			</ul>
		</aside><!--end .entry-details -->
	
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