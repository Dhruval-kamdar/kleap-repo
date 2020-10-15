<?php
/** Thb Tweets */
function thb_gettweets( $count = 5 ) {

	$cache = get_transient( 'thb_twitter_' . ot_get_option( 'twitter_bar_accesstoken' ) . '_' . $count );

	switch ( ot_get_option( 'twitter_cache', '1' ) ) {
		case '1h':
			$cache_time = 3600;
			break;
		case '1':
			$cache_time = DAY_IN_SECONDS;
			break;
		case '7':
			$cache_time = WEEK_IN_SECONDS;
			break;
		case '30':
			$cache_time = DAY_IN_SECONDS * 30;
			break;
	}

	if ( '' === ot_get_option( 'twitter_bar_accesstoken' )
	|| '' === ot_get_option( 'twitter_bar_accesstokensecret' )
	|| '' === ot_get_option( 'twitter_bar_consumerkey' )
	|| '' === ot_get_option( 'twitter_bar_consumersecret' )
	) {
		return new WP_Error( 'twitter_param_incomplete', 'Make sure you are passing in the correct parameters' );
	}
	if ( empty( $cache ) ) {
		$settings = array(
			'oauth_access_token'        => ot_get_option( 'twitter_bar_accesstoken' ),
			'oauth_access_token_secret' => ot_get_option( 'twitter_bar_accesstokensecret' ),
			'consumer_key'              => ot_get_option( 'twitter_bar_consumerkey' ),
			'consumer_secret'           => ot_get_option( 'twitter_bar_consumersecret' ),
		);

		$connection     = new ThbTwitterAPIExchange( $settings );
		$url            = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield       = '?screen_name=' . ot_get_option( 'twitter_bar_username' ) . '&count=' . $count;
		$request_method = 'GET';

		$response = $connection
			->set_get_field( $getfield )
			->build_oauth( $url, $request_method )
			->process_request();

		$response = json_decode( $response, true );

		if ( isset( $response['errors'] ) ) {
			foreach ( $response['errors'] as $error ) {
				echo esc_html( $error['message'] );
			}
		} else {
			foreach ( $response as $tweet ) {
				$tweets[] = array(
					'tweet' => $connection->get_helper()->thb_get_tweet_text( $tweet ),
					'url'   => $connection->get_helper()->thb_get_tweet_url( $tweet ),
					'time'  => $connection->get_helper()->thb_get_tweet_time( $tweet ),
				);
			}
			set_transient( 'thb_twitter_' . ot_get_option( 'twitter_bar_accesstoken' ) . '_' . $count, $tweets, $cache_time );
			return $tweets;
		}
	} else {
		return $cache;
	}
}

class ThbTwitterHelper {
	public function thb_get_tweet_text( $tweet ) {

		if ( ! empty( $tweet['text'] ) ) {

			$tweet_text = $tweet['text'];

			/* link links */
			$tweet_text = preg_replace( '/(https?)\:\/\/([a-z0-9\/\.\&\#\?\-\+\~\_\,]+)/i', '<a target="_blank" href="' . ( '$1://$2' ) . '">$1://$2</a>', $tweet_text );

			/* mention links */
			$tweet_text = preg_replace( '/\@([a-aA-Z0-9\.\_\-]+)/i', '<a target="_blank" href="https://twitter.com/$1">@$1</a>', $tweet_text );

			/* hashtags links */
			$tweet_text = preg_replace( '/\#([a-aA-Z0-9\.\_\-]+)/i', '<a target="_blank" href="https://twitter.com/search?q\=$1">#$1</a>', $tweet_text );

			return $tweet_text;
		} else {
			return '';
		}
	}
	public function thb_get_tweet_time( $tweet ) {
		if ( ! empty( $tweet['created_at'] ) ) {
			return human_time_diff( strtotime( $tweet['created_at'] ), current_time( 'timestamp' ) ) . ' ' . esc_html__( 'ago', 'revolution' );
		} else {
			return '';
		}
	}
	public function thb_get_tweet_url( $tweet ) {
		if ( ! empty( $tweet['id_str'] ) && $tweet['user']['screen_name'] ) {
			return 'https://twitter.com/' . $tweet['user']['screen_name'] . '/statuses/' . $tweet['id_str'];
		} else {
			return '#';
		}
	}
	public function thb_get_tweet_user_screen_name( $tweet ) {
		if ( ! empty( $tweet['id_str'] ) && $tweet['user']['screen_name'] ) {
			return $tweet['user']['screen_name'];
		} else {
			return '#';
		}
	}
	public function thb_get_tweet_username( $tweet ) {
		if ( ! empty( $tweet['id_str'] ) && $tweet['user']['name'] ) {
			return $tweet['user']['name'];
		} else {
			return '#';
		}
	}
}
