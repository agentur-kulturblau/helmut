<?php
/**
 * The template for displaying Comments.
 *
 * @package Baylys
 * @since Baylys 1.0
 */
?>

	<div id="comments">
		<?php if ( post_password_required() ) : ?>
		<div class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'baylys' ); ?></div>
	</div><!-- end #comments -->
	
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h3 id="comments-title">
			<?php
				printf( _n( '1 comment', '%1$s comments', get_comments_number(), 'baylys' ),
					number_format_i18n( get_comments_number() ) );
			?>
			<span><a href="#reply-title"><?php _e( 'Write a comment', 'baylys' ); ?></a></span>
		</h3>

		<ol class="commentlist">
				<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use baylys_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define baylys_comment() and that will be used instead.
				 */
				wp_list_comments( array( 'callback' => 'baylys_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr;  Older Comments', 'baylys' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments  &rarr;', 'baylys' ) ); ?></div>
		</nav><!-- end #comment-nav -->
		<?php endif; // check for comment navigation ?>

		<?php else : // this is displayed if there are no comments so far ?>

		<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php comment_form (
		array(
		'fields' => apply_filters(
		'comment_form_default_fields', array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name (required)', 'baylys' ) . '</label> ' . ( $req ? ' ' : '' ) .
	            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email (required)', 'baylys' ) . '</label> ' . ( $req ? ' ' : '' ) .
	            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'baylys' ) . '</label>' .
	            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'
		)
	),
			'comment_notes_before' => '',
			'comment_field'  => '<p class="comment-form-comment"><label for="comment">' . _x( 'Message <span class="required">(required)</span>', 'noun', 'baylys' ) . 			'</label><br/><textarea id="comment" name="comment" rows="8"></textarea></p>',
			'label_submit'	=> __( 'Send', 'baylys' ))
		); 
	?>

	</div><!-- end #comments -->