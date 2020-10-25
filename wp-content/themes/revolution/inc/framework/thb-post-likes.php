<?php

/** Get Post Likes */
function thb_get_post_likes( $post_id = null ) {
	$user_ip = thb_get_the_user_ip();
	$likes   = get_post_meta( $post_id, 'thb_post_likes', true );

	if ( ! is_array( $likes ) ) {
		$likes = array();
	}
	$output = array(
		'like'  => in_array( $user_ip, $likes, true ),
		'count' => count( $likes ),
	);

	return $output;
}

/** Like Feature */
function thb_update_like_count() {
	$post_id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
	$user_ip = thb_get_the_user_ip();

	$output = array(
		'like'  => false,
		'count' => 0,
	);

	if ( filter_var( $post_id, FILTER_VALIDATE_INT ) ) {
		if ( $post_id ) {
			$likes = get_post_meta( $post_id, 'thb_post_likes', true );
			$likes = is_array( $likes ) ? $likes : array();

			if ( ! in_array( $user_ip, $likes, true ) ) {
				/* Like Post */
				$output['like'] = true;

				$likes[]         = $user_ip;
				$output['count'] = count( $likes );

				update_post_meta( $post_id, 'thb_post_likes', $likes );
			} else {
				/* Unlike Post */
				$output['like'] = false;

				$key = array_search( $user_ip, $likes, true );

				if ( false !== $key ) {
					unset( $likes[ $key ] );
				}

				$output['count'] = count( $likes );

				update_post_meta( $post_id, 'thb_post_likes', $likes );
			}

			if ( function_exists( 'wp_cache_post_change' ) ) {
				wp_cache_post_change( $post_id );
			}
		}
	}

	echo wp_json_encode( $output );

	wp_die();
}

add_action( 'wp_ajax_thb_update_likes', 'thb_update_like_count' );
add_action( 'wp_ajax_nopriv_thb_update_likes', 'thb_update_like_count' );
