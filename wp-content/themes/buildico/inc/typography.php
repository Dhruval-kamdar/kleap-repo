<?php
/**
 * buildico custom typography
 *
 * @package buildico
 */

if( ! function_exists('buildico_custom_typography') ){

    function buildico_custom_typography(){

		$custom_style = '';
		if(wt_get_option('body_custom_style') === true){
			$custom_style .= 'body, p{';
				if( ! empty( wt_get_option('body_font_family') ) ){
					$custom_style .= 'font-family: "'. esc_html( wt_get_option('body_font_family')['family'] ) .'", sans-serif;';
				}
				if( ! empty( wt_get_option('body_font_size') ) ){
					$custom_style .= 'font-size: '. esc_html( wt_get_option('body_font_size') ) .'px;';
				}
				if( ! empty( wt_get_option('body_font_color') ) ){
					$custom_style .= 'color: '. esc_html( wt_get_option('body_font_color') ) .';';
				}
				if( ! empty( wt_get_option('body_font_lh') ) ){
					$custom_style .= 'line-height: '. esc_html( wt_get_option('body_font_lh') ) .'px;';
				}
				if( ! empty( wt_get_option('body_font_ls') ) ){
					$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('body_font_ls') ) .'em;';
				}
			$custom_style .= '}';
		}
    	if(wt_get_option('heading_custom_style') === true){
			$custom_style .= 'h1{';
			if( ! empty( wt_get_option('h1_font_family') ) ){
				$custom_style .= 'font-family: "'. esc_html( wt_get_option('h1_font_family')['family'] ) .'", sans-serif;';
			}
			if( ! empty( wt_get_option('h1_font_family')['variant']) ){
				$custom_style .= 'font-weight: '. esc_html( wt_get_option('h1_font_family')['variant'] ) .';';
			}
			if( ! empty( wt_get_option('h1_font_size') ) ){
				$custom_style .= 'font-size: '. esc_html( wt_get_option('h1_font_size') ) .'px;';
			}
			if( ! empty( wt_get_option('h1_font_color') ) ){
				$custom_style .= 'color: '. esc_html( wt_get_option('h1_font_color') ) .';';
			}
			if( ! empty( wt_get_option('h1_font_lh') ) ){
				$custom_style .= 'line-height: '. esc_html( wt_get_option('h1_font_lh') ) .'px;';
			}
			if( ! empty( wt_get_option('h1_margin') ) ){
				$custom_style .= 'margin: '. esc_html( wt_get_option('h1_margin') ) .';';
			}
			if( ! empty( wt_get_option('h1_font_ls') ) ){
				$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('h1_font_ls') ) .'em;';
			}
			$custom_style .= '}';
			$custom_style .= 'h2{';
			if( ! empty( wt_get_option('h2_font_family') ) ){
				$custom_style .= 'font-family: "'. esc_html( wt_get_option('h2_font_family')['family'] ) .'", sans-serif;';
			}
			if( ! empty( wt_get_option('h2_font_family')['variant'] ) ){
				$custom_style .= 'font-weight: '. esc_html( wt_get_option('h2_font_family')['variant'] ) .';';
			}
			if( ! empty( wt_get_option('h2_font_size') ) ){
				$custom_style .= 'font-size: '. esc_html( wt_get_option('h2_font_size') ) .'px;';
			}
			if( ! empty( wt_get_option('h2_font_color') ) ){
				$custom_style .= 'color: '. esc_html( wt_get_option('h2_font_color') ) .';';
			}
			if( ! empty( wt_get_option('h2_font_lh') ) ){
				$custom_style .= 'line-height: '. esc_html( wt_get_option('h2_font_lh') ) .'px;';
			}
			if( ! empty( wt_get_option('h2_margin') ) ){
				$custom_style .= 'margin: '. esc_html( wt_get_option('h2_margin') ) .';';
			}
			if( ! empty( wt_get_option('h2_font_ls') ) ){
				$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('h2_font_ls') ) .'em;';
			}
			$custom_style .= '}';
			$custom_style .= 'h3{';
			if( ! empty( wt_get_option('h3_font_family') ) ){
				$custom_style .= 'font-family: "'. esc_html( wt_get_option('h3_font_family')['family'] ) .'", sans-serif;';
			}
			if( ! empty( wt_get_option('h3_font_family')['variant'] ) ){
				$custom_style .= 'font-weight: '. esc_html( wt_get_option('h3_font_family')['variant'] ) .';';
			}
			if( ! empty( wt_get_option('h3_font_size') ) ){
				$custom_style .= 'font-size: '. esc_html( wt_get_option('h3_font_size') ) .'px;';
			}
			if( ! empty( wt_get_option('h3_font_color') ) ){
				$custom_style .= 'color: '. esc_html( wt_get_option('h3_font_color') ) .';';
			}
			if( ! empty( wt_get_option('h3_font_lh') ) ){
				$custom_style .= 'line-height: '. esc_html( wt_get_option('h3_font_lh') ) .'px;';
			}
			if( ! empty( wt_get_option('h3_margin') ) ){
				$custom_style .= 'margin: '. esc_html( wt_get_option('h3_margin') ) .';';
			}
			if( ! empty( wt_get_option('h3_font_ls') ) ){
				$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('h3_font_ls') ) .'em;';
			}
			$custom_style .= '}';
			$custom_style .= 'h4{';
			if( ! empty( wt_get_option('h4_font_family') ) ){
				$custom_style .= 'font-family: "'. esc_html( wt_get_option('h4_font_family')['family'] ) .'", sans-serif;';
			}
			if( ! empty( wt_get_option('h4_font_family')['variant'] ) ){
				$custom_style .= 'font-weight: '. esc_html( wt_get_option('h4_font_family')['variant'] ) .';';
			}
			if( ! empty( wt_get_option('h4_font_size') ) ){
				$custom_style .= 'font-size: '. esc_html( wt_get_option('h4_font_size') ) .'px;';
			}
			if( ! empty( wt_get_option('h4_font_color') ) ){
				$custom_style .= 'color: '. esc_html( wt_get_option('h4_font_color') ) .';';
			}
			if( ! empty( wt_get_option('h4_font_lh') ) ){
				$custom_style .= 'line-height: '. esc_html( wt_get_option('h4_font_lh') ) .'px;';
			}
			if( ! empty( wt_get_option('h4_margin') ) ){
				$custom_style .= 'margin: '. esc_html( wt_get_option('h4_margin') ) .';';
			}
			if( ! empty( wt_get_option('h4_font_ls') ) ){
				$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('h4_font_ls') ) .'em;';
			}
			$custom_style .= '}';
			$custom_style .= 'h5{';
			if( ! empty( wt_get_option('h5_font_family') ) ){
				$custom_style .= 'font-family: "'. esc_html( wt_get_option('h5_font_family')['family'] ) .'", sans-serif;';
			}
			if( ! empty( wt_get_option('h5_font_family')['variant'] ) ){
				$custom_style .= 'font-weight: '. esc_html( wt_get_option('h5_font_family')['variant'] ) .';';
			}
			if( ! empty( wt_get_option('h5_font_size') ) ){
				$custom_style .= 'font-size: '. esc_html( wt_get_option('h5_font_size') ) .'px;';
			}
			if( ! empty( wt_get_option('h5_font_color') ) ){
				$custom_style .= 'color: '. esc_html( wt_get_option('h5_font_color') ) .';';
			}
			if( ! empty( wt_get_option('h5_font_lh') ) ){
				$custom_style .= 'line-height: '. esc_html( wt_get_option('h5_font_lh') ) .'px;';
			}
			if( ! empty( wt_get_option('h5_margin') ) ){
				$custom_style .= 'margin: '. esc_html( wt_get_option('h5_margin') ) .';';
			}
			if( ! empty( wt_get_option('h5_font_ls') ) ){
				$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('h5_font_ls') ) .'em;';
			}
			$custom_style .= '}';
			$custom_style .= 'h6{';
			if( ! empty( wt_get_option('h6_font_family') ) ){
				$custom_style .= 'font-family: "'. esc_html( wt_get_option('h6_font_family')['family'] ) .'", sans-serif;';
			}
			if( ! empty( wt_get_option('h6_font_family')['variant'] ) ){
				$custom_style .= 'font-weight: '. esc_html( wt_get_option('h6_font_family')['variant'] ) .';';
			}
			if( ! empty( wt_get_option('h6_font_size') ) ){
				$custom_style .= 'font-size: '. esc_html( wt_get_option('h6_font_size') ) .'px;';
			}
			if( ! empty( wt_get_option('h6_font_color') ) ){
				$custom_style .= 'color: '. esc_html( wt_get_option('h6_font_color') ) .';';
			}
			if( ! empty( wt_get_option('h6_font_lh') ) ){
				$custom_style .= 'line-height: '. esc_html( wt_get_option('h6_font_lh') ) .'px;';
			}
			if( ! empty( wt_get_option('h6_margin') ) ){
				$custom_style .= 'margin: '. esc_html( wt_get_option('h6_margin') ) .';';
			}
			if( ! empty( wt_get_option('h6_font_ls') ) ){
				$custom_style .= 'letter-spacing: '. esc_html( wt_get_option('h6_font_ls') ) .'em;';
			}
			$custom_style .= '}';
		}
		
		if(wt_get_option('primary_custom_style') === true){
			if( ! empty( wt_get_option('primary_color') ) ){
				$custom_style .= ' button, input[type="button"], input[type="submit"], .b-btn,.top-info li i, ul.top-social li a, .b-nav-btn, .b-nav-btn:hover, .mainmenu li ul li a:hover, .mid-info::before, .blog-box.sticky:before, .widget-title:before, .pagination-wrap li .page-link:hover, .pagination-wrap li.page-item.active .page-link, .author-socials li a, .author-bio .bio-inner ul li a, .carousel-control ol li.active, .widget .tagcloud a, .widget.widget_tag_cloud a, .wp_widget_tag_cloud a, .widget-box.widget_tag_cloud a, .widget-box .wp_widget_tag_cloud a, .woocommerce span.onsale, .add-to-cart-container .add_to_cart_button, .return-to-shop a, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .return-to-shop a:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woo-header-cart .cart-contents span, .woo-header-cart .cart-contents:hover span, .woocommerce nav.woocommerce-pagination ul li a, .section-heading h2:before, .service-box-3:hover, .service-box-5 a, .wt-callout-btn, .team-social li a, .testi-1-carousel .owl-dots div.active, .wt-cf7-form .wpcf7 input[type="submit"], .slider-text .slider-btn, .nivo-controlNav a.active, .buildico-btn, .slider-text .slider-btn, .woocommerce .add-to-cart-container a, .woocommerce nav.woocommerce-pagination ul li span.current{ background-color: '. esc_html( wt_get_option('primary_color') ) .'; } ';
				$custom_style .= '.woocommerce .add-to-cart-container a:active, .woocommerce .add-to-cart-container a:focus{ background-color: '. esc_html( wt_get_option('primary_color') ) .'!important; }';
				$custom_style .= '.buildico-cart:hover svg{ fill: '. esc_html( wt_get_option('primary_color') ) .';}';
				$custom_style .= 'table th a:hover, .entry-meta .byline i, .entry-meta span.posted-on i, .page-links a:hover, .error-404 i, .widget ul li a:hover, .widget-box ul li a:hover, .woocommerce div.product p.price ins .woocommerce-Price-amount.amount, .woocommerce div.product span.price ins .woocommerce-Price-amount.amount, .woocommerce ul.products li.product .price ins .woocommerce-Price-amount.amount, .service-box a:hover, .service-item .post-info a:hover, .service-box-4 a:hover, .wt-branding .site-title{ color: '. esc_html( wt_get_option('primary_color') ) .'; } ';
				$custom_style .= 'blockquote, .pagination-wrap li.page-item.active .page-link, .comment-form .form-control:hover, .comment-form .form-control:focus, .add-to-cart-container .add_to_cart_button, .service-box-5 a, .filter-menu li.active, .wt-cf7-form .wpcf7-form .wpcf7-form-control-wrap input:focus, .wt-cf7-form .wpcf7-form .wpcf7-form-control-wrap .wpcf7-textarea:focus, .slider-text .slider-btn, .nivo-controlNav a.active, .slider-text .slider-btn, .project-single-carousel .owl-dots div.active, input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="number"]:focus, input[type="tel"]:focus, input[type="range"]:focus, input[type="date"]:focus, input[type="month"]:focus, input[type="week"]:focus, input[type="time"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="color"]:focus, textarea:focus{ border-color: '. esc_html( wt_get_option('primary_color') ) .'; } ';
			}
		}
		
		if( wt_get_option('scrollbar_enable') == true ){
			if (! empty( wt_get_option('cursor_width') ) ) {
				$custom_style .= 'body.custom-scrollbar{ margin-right: '. esc_html( wt_get_option('cursor_width') ).'px;}';
				$custom_style .= '@media screen and (max-width: 767px) { body.custom-scrollbar{ margin: 0; } }';
			}
		}
		
		if(wt_get_option('gototop_custom_style') === true){
			if( ! empty( wt_get_option('gototop_btn_bg') || wt_get_option('gototop_btn_color') ) ){
				$custom_style .= '.scroll-to-top{ background-color: '. wt_get_option('gototop_btn_bg') .'; color: '. wt_get_option('gototop_btn_color') .'}';
			}
		}

		if(wt_get_option('ph_custom_style') === true){
			if( wt_get_option('ph_border_hide') === true ){
				if( ! empty( wt_get_option('ph_border_color') && wt_get_option('ph_border_width') && wt_get_option('ph_border_height') ) ){
					$custom_style .= '.author-posts-title:before, .page-header h1.page-title:before, .blog-single .entry-header:before{ background-color: '. esc_attr( wt_get_option('ph_border_color') ) .'; width: '. wt_get_option('ph_border_width') .'px; height: '. wt_get_option('ph_border_height') .'px; }';
				}
			}else{
				$custom_style .= '.author-posts-title:before, .page-header h1.page-title:before, .blog-single .entry-header:before{ display: none; }';
			}
		}
		
		if(wt_get_option('preloader_custom_style') === true){
			if( ! empty( wt_get_option('preloader_bg') || wt_get_option('preloader_color') ) ){
				if(wt_get_option('preloader_style') === 'style-1'){
					$custom_style .= '#preloader.style-1{ background-color: '. esc_attr( wt_get_option('preloader_bg') ) .' }';
				}else{
					$custom_style .= '#preloader{ background-color: '. esc_attr( wt_get_option('preloader_bg') ) .' } #preloader .spinner{ background-color: '. esc_attr( wt_get_option('preloader_color') ) .' }';
				}
			}
		}
		
		// Footer Top Styles
        if( get_post_meta( get_the_ID(), "custom_page_options", true ) ){
             $buildico_page_settings = get_post_meta( get_the_ID(), "custom_page_options", true );
        }else{
             $buildico_page_settings = array();
        }
        if( ! empty( $buildico_page_settings['footer_widget_bg_color'] ) ){
            $custom_style .= '.footer-widget-section{ background-color: '. esc_attr( $buildico_page_settings['footer_widget_bg_color'] ) .'; }';
        }else{
			if(wt_get_option('ft_custom_style') === true){
				if( ! empty( wt_get_option('footer_top_bg_color') ) ){
					$custom_style .= '.footer-widget-section{ background-color: '. esc_attr( wt_get_option('footer_top_bg_color') ) .'; }';
				}
			}
        }
        
		if( wt_get_option('footer_top_bg_hide') === true ){
			if( !empty( wt_get_option('footer_top_bg_img') ) ){
				$footer_bg_img = wp_get_attachment_image_src( wt_get_option('footer_top_bg_img'), 'full');
				$custom_style .= '.footer-widget-section:before{ background-image: url( '. esc_url( $footer_bg_img[0] ) .' ); opacity: '. esc_attr( wt_get_option('footer_top_bg_img_opacity') ) .' }';
			}
			if( !empty( wt_get_option('footer_top_bg_img_opacity') ) && wt_get_option('ft_custom_style') === true ){
				$custom_style .= '.footer-widget-section:before{ opacity: '. esc_attr( wt_get_option('footer_top_bg_img_opacity') ) .' }';
			}
		}else{
			$custom_style .= '.footer-widget-section:before{ display: none; }';
		}

		if(wt_get_option('fb_custom_style') === true){
			// Footer Bottom Styles
			$footer_bg_color = wt_get_option( 'footer_bg_color' );
			$footer_bd_color = wt_get_option( 'footer_bd_color' );
			$footer_text_color = wt_get_option( 'footer_text_color' );

			if( !empty( $footer_bg_color || $footer_bd_color || $footer_text_color ) ){
				$custom_style .= '.footer-wrap{ background-color: '. esc_attr( $footer_bg_color ) .'; border-top: 1px solid '. esc_attr( $footer_bd_color ) .'; } .footer-wrap .site-info{ color: '. esc_attr( $footer_text_color ) .'; }';
			}
		}

        wp_add_inline_style( 'buildico-stylesheet', $custom_style );
    }
}

add_action( 'wp_enqueue_scripts', 'buildico_custom_typography' );
