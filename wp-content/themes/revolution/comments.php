<?php
/**
 *  Begin processing our comments
 *
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

if ( post_password_required() ) {
	return;
}

function thb_add_svg_to_reply( $args, $comment, $post ) {
	$args['reply_text'] = thb_load_template_part( 'assets/img/svg/reply.svg' ) . $args['reply_text'];
	return $args;
}
add_filter( 'comment_reply_link_args', 'thb_add_svg_to_reply', 420, 4 );

?>
<div class="row align-center">
	<div class="small-12 medium-10 large-7 columns">
<?php if ( have_comments() ) : ?>
<!-- Start #comments -->
<div class="comments-container">
	<h4 class="comments-title">
		<?php
			printf(
				esc_html( _nx( '%1$s Comment', '%1$s Comments', get_comments_number(), 'comments', 'revolution' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
		?>
	</h4>
	<ul class="commentlist cf">
		<?php
		wp_list_comments(
			array(
				'type'        => 'all',
				'style'       => 'ul',
				'short_ping'  => true,
				'avatar_size' => 80,
			)
		);
		?>
	</ul>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
		<div class="navigation">
			<div class="nav-previous"><?php previous_comments_link(); ?></div>
			<div class="nav-next"><?php next_comments_link(); ?></div>
		</div>
	<?php } ?>
</div>
	<?php
else :
	if ( ! comments_open() ) :
		?>
	<p class="nocomments"><?php esc_html_e( 'Comments are closed', 'revolution' ); ?></p>
<?php endif; // end ! comments_open(). ?>

<?php endif; // end have_comments(). ?>


<?php
	// Comment Form.
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? ' aria-required="true" data-required="true"' : '' );
	$defaults  = array(
		'fields'               => apply_filters(
			'comment_form_default_fields',
			array(
				'author' => '<div class="row"><div class="small-12 medium-6 columns"><label>' . esc_html__( 'Name', 'revolution' ) . ' <span class="required">*</span></label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' placeholder="' . esc_attr__( 'Name', 'revolution' ) . '" class="full"/></div>',
				'email'  => '<div class="small-12 medium-6 columns"><label>' . esc_html__( 'E-mail', 'revolution' ) . ' <span class="required">*</span></label><input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' placeholder="' . esc_attr__( 'E-mail', 'revolution' ) . '" class="full" /></div></div>',
				'url'    => '<div class="row"><div class="small-12 columns"><label>' . esc_html__( 'Website', 'revolution' ) . ' <span class="required">*</span></label><input name="url" size="30" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" type="text" placeholder="' . esc_attr__( 'Website', 'revolution' ) . '" class="full"/></div></div>',
			)
		),
		'comment_field'        => '<div class="row"><div class="small-12 columns"><textarea name="comment" id="comment"' . $aria_req . ' rows="3" cols="58" class="full" placeholder="' . esc_attr__( 'Your Comment', 'revolution' ) . '"></textarea></div></div>',
		'must_log_in'          => '<p class="must-log-in">' . sprintf(
			wp_kses(
				__( 'You must be <a href="%s">logged in</a> to post a comment.', 'revolution' ),
				array(
					'a' => array(
						'href'  => array(),
						'title' => array(),
					),
				)
			),
			wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
		) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf(
			wp_kses(
				__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'revolution' ),
				array(
					'a' => array(
						'href'  => array(),
						'title' => array(),
					),
				)
			),
			admin_url( 'profile.php' ),
			$user_identity,
			wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) )
		) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.', 'revolution' ) . '</p>',
		'comment_notes_after'  => '',
		'id_form'              => 'form-comment',
		'id_submit'            => 'submit',
		'title_reply'          => esc_html__( 'Leave a Reply', 'revolution' ),
		'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'revolution' ),
		'title_reply_before'   => '<h4 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h4>',
		'cancel_reply_link'    => esc_attr__( 'Cancel reply', 'revolution' ),
		'class_submit'         => 'submit btn full',
		'label_submit'         => esc_attr__( 'Submit Comment', 'revolution' ),
	);
	comment_form( $defaults );
	?>
	</div>
</div>
