<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package buildico
 */


if ( ! function_exists( 'buildico_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function buildico_posted_on() {
	$enable_author_page = wt_get_option( 'enable_author_page' );
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	if( !empty( wt_get_option('datelink_enable') ) && wt_get_option('datelink_enable') === true ){
		$posted_on = sprintf(
			__( '<i class="ti-calendar"></i> %s', 'buildico' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
	}else{
		$posted_on = sprintf(
			__( '<i class="ti-calendar"></i> %s', 'buildico' ), $time_string );
	}

	if( $enable_author_page === true ){
		$byline = sprintf(
			__( '<i class="ti-user"></i> %s', 'buildico' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
	} else{
		$byline = sprintf(
			__( '<i class="ti-user"></i> %s', 'buildico' ),
			'<span class="author vcard">' . esc_html( get_the_author() ) . '</span>'
		);
	}

	if( is_archive() ){

		$archive_author_name = wt_get_option( 'archive_author' );
		$archive_date = wt_get_option( 'archive_date' );

		if ( $archive_date === true) {
			echo '<span class="posted-on">' . $posted_on . '</span>';
		}

		if ( $archive_author_name === true) {
			echo '</span><span class="byline"> ' . $byline . '</span>';
		}

	} elseif ( is_single() ) {

		$single_post_date = wt_get_option( 'single_pub_date' );
		$single_post_author = wt_get_option( 'single_author' );

		if ( $single_post_date === true) {
			echo '<span class="posted-on">' . $posted_on . '</span>';
		}

		if ( $single_post_author === true) {
			echo '</span><span class="byline"> ' . $byline . '</span>';
		}
	} else{
		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'buildico_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function buildico_entry_footer() {
	$single_post_tags = wt_get_option( 'single_tags' );
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'buildico' ) );
		if ( $categories_list && buildico_categorized_blog() ) {
			printf( '<span class="cat-text">'. esc_html__('Posted in:', 'buildico') .'</span><span class="cat-links">' . esc_html__( '%1$s', 'buildico' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
		if( $single_post_tags === true ){
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html( ', ' ) );
			if ( $tags_list ) {
				printf( '<span class="cat-text">'. esc_html__('Tagged:', 'buildico') .'</span><span class="tags-links">' . esc_html__( '%1$s', 'buildico' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
	}
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'buildico' ), esc_html__( '1 Comment', 'buildico' ), esc_html__( '% Comments', 'buildico' ) );
		echo '</span>';
	}
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'buildico' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function buildico_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'buildico_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'buildico_categories', $all_the_cool_cats );
	}
	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so components_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so components_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in buildico_categorized_blog.
 */
function buildico_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'buildico_categories' );
}
add_action( 'edit_category', 'buildico_category_transient_flusher' );
add_action( 'save_post',     'buildico_category_transient_flusher' );
