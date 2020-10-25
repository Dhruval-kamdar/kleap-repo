<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package buildico
 */

// Removes tag class from the body_class array to avoid Bootstrap markup styling issues.
add_filter( 'body_class', 'buildico_adjust_body_class' );

if ( ! function_exists( 'buildico_adjust_body_class' ) ) {
	/**
	 * Setup body classes.
	 *
	 * @param string $classes CSS classes.
	 *
	 * @return mixed
	 */
	function buildico_adjust_body_class( $classes ) {

		foreach ( $classes as $key => $value ) {
			if ( 'tag' == $value ) {
				unset( $classes[ $key ] );
			}
		}

		return $classes;

	}
}

// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'buildico_change_logo_class' );

if ( ! function_exists( 'buildico_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return mixed
	 */
	function buildico_change_logo_class( $html ) {

		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
		$html = str_replace( 'alt=""', 'title="'. esc_attr__('Logo', 'buildico') .'" alt="'. esc_attr__('Logo', 'buildico') .'"' , $html );

		return $html;
	}
}

/**
 * Display navigation to next/previous post when applicable.
 */
if ( ! function_exists( 'buildico_post_nav' ) ) :

	function buildico_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		} ?>
		<nav class="navigation post-navigation">
			<h2 class="sr-only"><?php _e( 'Post navigation', 'buildico' ); ?></h2>
			<div class="row">
				<div class="col-12 col-md-6 text-center text-md-left">
				<?php
					if ( get_previous_post_link() ) {
						previous_post_link( '<span class="nav-previous">%link</span>', _x( '<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'buildico' ) );
					}
				?>
				</div>
				<div class="col-12 col-md-6 text-center text-md-right">
				<?php
					if ( get_next_post_link() ) {
						next_post_link( '<span class="nav-next">%link</span>',     _x( '%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'buildico' ) );
					}
				?>
				</div>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
endif;

/**
 * Author Profile
*/
if ( ! function_exists( 'buildico_author_profile' ) ) {

	function buildico_author_profile(  ) {
		global $post;
		$author_socail_acc = wt_get_option( 'author_socail_acc' );
    	if ( is_single() && isset( $post->post_author ) ) {

			// Get author's display name
			$display_name = get_the_author_meta( 'display_name', $post->post_author );

			// Get author avatar
			 $author_avatar = get_avatar( get_the_author_meta('user_email') , 90 );

			// If display name is not available then use nickname as display name
			if ( empty( $display_name ) ){
				$display_name = get_the_author_meta( 'nickname', $post->post_author );
			 }

			// Get author's biographical information or description
			$user_description = get_the_author_meta( 'user_description', $post->post_author );

			// Get author's social URL
			$fb_url = get_the_author_meta('facebook_profile', $post->post_author);
			$tt_url = get_the_author_meta('twitter_profile', $post->post_author);
			$gp_url = get_the_author_meta('google_profile', $post->post_author);
			$ld_url = get_the_author_meta('linkedin_profile', $post->post_author);
			$pin_url = get_the_author_meta('pinterest_profile', $post->post_author);
			$tlr_url = get_the_author_meta('tumblr_profile', $post->post_author);

			// Get link to the author archive page
			$user_posts = get_author_posts_url( get_the_author_meta( 'ID' , $post->post_author));

			$author_details = '<div class="author-bio"><div class="bio-inner">';

			if( ! empty( $author_avatar ) ){
				$author_details .= $author_avatar;
			}

			if ( ! empty( $display_name ) ){
				$author_details .= '<h3 class="author_name">About ' . $display_name . '</h3>';
			 }

			if ( ! empty( $user_description ) ){
				$author_details .= '<p>' . esc_html( $user_description ) . '</p>';
			}

			$author_details .= '<a href="'. esc_url ( $user_posts ) .'">View my all posts</a>';

			if( $author_socail_acc === true ) {

				$author_details .= '<ul>';
				if ( ! empty( $tt_url ) ){
					$author_details .= '<li><a href="' . esc_url( $tt_url ) . '"><i class="fa fa-twitter"></i></a></li>';
				}
				if ( ! empty( $fb_url ) ){
					$author_details .= '<li><a href="' . esc_url( $fb_url ) . '"><i class="fa fa-facebook"></i></a></li>';
				}
				if ( ! empty( $gp_url ) ){
					$author_details .= '<li><a href="' . esc_url( $gp_url ) . '"><i class="fa fa-google-plus"></i></a></li>';
				}
				if ( ! empty( $ld_url ) ){
					$author_details .= '<li><a href="' . esc_url( $ld_url ) . '"><i class="fa fa-linkedin"></i></a></li>';
				}
				if ( ! empty( $pin_url ) ){
					$author_details .= '<li><a href="' . esc_url( $pin_url ) . '"><i class="fa fa-pinterest"></i></a></li>';
				}
				if ( ! empty( $tlr_url ) ){
					$author_details .= '<li><a href="' . esc_url( $tlr_url ) . '"><i class="fa fa-tumblr"></i></a></li>';
				}
				$author_details .= '</ul>';

			}
			$author_details .= '</div></div>';

			return $author_details;
		}
	}
}

/**
 * Hide Page from Search Results
*/
$search_page_exclude = wt_get_option( 'search_page_exclude' );
if( $search_page_exclude === true ){

	if ( ! function_exists( 'buildico_remove_pages_from_search' ) ) {
		function buildico_remove_pages_from_search() {

		    global $wp_post_types;
		    $wp_post_types['page']->exclude_from_search = true;

		}
	}
	add_action('init', 'buildico_remove_pages_from_search');
}

/**
 * Custom login logo
 */
if ( !function_exists( 'buildico_custom_login_logo' ) ) {
	function buildico_custom_login_logo() {
		$login_logo = wt_get_option('admin_logo');
		$login_bg_img = wt_get_option('login_bg_img');
		$login_bg_overlay = wt_get_option('login_bg_overlay');
		$logo_width = wt_get_option('logo_width');
		$logo_height = wt_get_option('logo_height');
		$login_logo_url = wp_get_attachment_image_src( $login_logo, 'full');
		$login_bg_url = wp_get_attachment_image_src( $login_bg_img, 'full');

		if ( $login_logo != '' ) {
			echo '
		    <style type="text/css">
		        .login h1 a { background-image:url('. esc_url( $login_logo_url[0] ) .') !important; height: '. esc_html( $logo_height ) .' !important; width: '. esc_html( $logo_width ) .' !important; background-size: inherit !important; background-position: center center!important; }
		    </style>';
		} else {
			echo '
		    <style type="text/css">
		        .login h1 a { background-image:url('. get_template_directory_uri() .'/assets/img/admin-logo.png) !important; width: 100% !important; background-size: inherit !important; background-position: center center!important; }
		    </style>';
		}
		if ( $login_bg_img ) {
			echo '
		    <style type="text/css">
		        body.login { background-image:url('. esc_url( $login_bg_url[0] ) .') !important;  background-size: cover; background-position: center center; position: relative; z-index: 1; }
		        body.login:before{ background-color: '. esc_html( $login_bg_overlay ) .'; content: ""; width: 100%; height: 100%; position: absolute; left: 0; top: 0; z-index: -1; }
		    </style>';
		}
	}
}
add_action('login_enqueue_scripts', 'buildico_custom_login_logo');

if ( !function_exists( 'buildico_wp_login_url' ) ) {
	function buildico_wp_login_url() {
		return home_url();
	}
}
add_filter('login_headerurl', 'buildico_wp_login_url');

if ( !function_exists( 'buildico_wp_login_title' ) ) {
	function buildico_wp_login_title() {
		if( ! empty( wt_get_option('login_header_title') )){
			$login_title = wt_get_option('login_header_title');
		}else{
			$login_title = get_bloginfo('name');
		}
		return $login_title;
	}
}
add_filter('login_headertext', 'buildico_wp_login_title');

if( ! function_exists('buildico_preloader') ){
	function buildico_preloader(){
		$preloader_style = wt_get_option('preloader_style');
		$class = ($preloader_style == 'style-1' ? 'style-1' : 'style-2');
		$preloader_img = wp_get_attachment_image_src( wt_get_option('preloader_img'), 'full');
		?>
		<div id="preloader" class="<?php echo esc_attr($class);?>">
	        <div class="loader">
				<?php if( wt_get_option('preloader_select') != 'custom_preloader' ) : ?>
					<?php if($preloader_style === 'style-1') :
						$style1_img = get_template_directory_uri() . '/assets/img/preloader.gif';
					?>
					<img src="<?php echo esc_url( $style1_img ); ?>">
					<?php else : ?>
					<div class="spinner"></div>
					<?php endif; ?>
	        <?php else : ?>
				<img src="<?php echo esc_url( $preloader_img[0] ); ?>">
	        <?php endif; ?>
	        </div>
	    </div><!-- Preloader -->
		<?php
	}
}

/**
 * ============================================================================
 * Adds custom classes to the array of body classes.
 * ============================================================================
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */

function buildico_body_classes( $classes ) {
	$detect = new Mobile_Detect;
	$classes[] = 'buildico';

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if( get_post_meta( get_the_ID(), "custom_page_options", true ) ){
		$buildico_header_options = get_post_meta( get_the_ID(), "custom_page_options", true );
	}else{
		$buildico_header_options = array();
	}
	$buildico_headers = wt_get_option('select_header');
	$transparent_header = wt_get_option('transparent_header');
	if( ! empty($buildico_header_options) ){
		if(! empty( $buildico_header_options['header_select'] || $buildico_header_options['transparent_header'] ) ){
			if( 'header-1' === $buildico_header_options['header_select'] && 'enable' === $buildico_header_options['transparent_header'] ){
				$classes[] = 'transparent-header';
			}elseif( 'disable' === $buildico_header_options['transparent_header'] ){
				$classes[] = '';
			}else{
				if( ! empty($buildico_headers && $transparent_header ) && 'header-1' === $buildico_headers && $transparent_header === true ){
					$classes[] = 'transparent-header';
				}else{
					$classes[] = '';
				}
			}
		}else{
			if( ! empty($buildico_headers && $transparent_header ) && 'header-1' === $buildico_headers && $transparent_header === true ){
				$classes[] = 'transparent-header';
			}else{
				$classes[] = '';
			}
		}
	}else{
		if( ! empty($buildico_headers && $transparent_header ) && 'header-1' === $buildico_headers && $transparent_header === true ){
			$classes[] = 'transparent-header';
		}else{
			$classes[] = '';
		}
	}

	if( wt_get_option('featured_img_post') == 'in_body' ){
		if( is_single() ){
			$classes[] = 'fetured-img-in-body';
		}
	}

	if( wt_get_option('scrollbar_enable') == 'true' ){
		if( wt_get_option( 'mobile_disable_scrollbar' ) === true ){
			$classes[] = 'custom-scrollbar';
		}else{
			if( !$detect->isMobile() ){
				$classes[] = '';
			}
		}
	}

	if( wt_get_option('select_theme_layout') == 'container-fluid' ){
		$classes[] = 'full-width';
	}

	return $classes;
}

add_filter( 'body_class', 'buildico_body_classes' );

// Menu Choices
function buildico_get_navbar_menu_choices() {
	$menus = wp_get_nav_menus();
	$items = array();
	$i     = 0;
	if( ! empty( $menus ) ){
		foreach ( $menus as $menu ) {
			if ( $i == 0 ) {
				$default = $menu->slug;
				$i ++;
			}
			$items[ 'default' ] = __('Default', 'buildico');
			$items[ $menu->slug ] = $menu->name;
		}
	}else{
		$items[ '' ] = __('No Menu Found', 'buildico');
	}


	return $items;
}

if ( ! function_exists( 'wp_body_open' ) ) {
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support WordPress versions prior to 5.2.0.
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}