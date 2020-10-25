<?php
/**
 * Comment layout.
 *
 * @package buildico
 */

// Comments form.
add_filter( 'comment_form_default_fields', 'buildico_bootstrap_comment_form_fields' );

/**
 * Creates the comments form.
 *
 * @param string $fields Form fields.
 *
 * @return array
 */
function buildico_bootstrap_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html5     = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
	$fields    = array(
		'author' => '<div class="form-group row"><div class="col comment-form-author"><label for="author">' . __( 'Name',
				'buildico' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . '></div>',
		'email'  => '<div class="col comment-form-email"><label for="email">' . __( 'Email',
				'buildico' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input class="form-control" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . '></div></div>',
		'url'    => '<div class="form-group comment-form-url"><label for="url">' . __( 'Website',
				'buildico' ) . '</label> ' .
		            '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"></div>',
	);

	return $fields;
}

add_filter( 'comment_form_defaults', 'buildico_bootstrap_comment_form' );

/**
 * Builds the form.
 *
 * @param string $args Arguments for form's fields.
 *
 * @return mixed
 */
function buildico_bootstrap_comment_form( $args ) {
	$args['comment_field'] = '<div class="form-group comment-form-comment">
    <label for="comment">' . _x( 'Comment', 'noun', 'buildico' ) . ( ' <span class="required">*</span>' ) . '</label>
    <textarea class="form-control" id="comment" name="comment" aria-required="true" cols="45" rows="4"></textarea>
    </div>';
	$args['class_submit']  = 'b-btn'; // since WP 4.1.
	return $args;
}
