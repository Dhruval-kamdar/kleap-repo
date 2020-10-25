<?php function thb_selection() {
	$id = get_queried_object_id();
	ob_start();
	?>
/* Options set in the admin page */

/* Font Families */
	<?php if ( $primary_font = ot_get_option( 'primary_font' ) ) { ?>
h1, .h1, .thb-countdown .thb-countdown-ul li .timestamp, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
		<?php thb_typeoutput( $primary_font ); ?>
}
<?php } ?>
	<?php if ( $secondary_font = ot_get_option( 'secondary_font' ) ) { ?>
body {
		<?php thb_typeoutput( $secondary_font ); ?>
}
<?php } ?>
	<?php if ( $fullmenu_font = ot_get_option( 'fullmenu_font' ) ) { ?>
.thb-full-menu {
		<?php thb_typeoutput( $fullmenu_font ); ?>
}
<?php } ?>
	<?php if ( $mobilemenu_font = ot_get_option( 'mobilemenu_font' ) ) { ?>
.thb-mobile-menu,
.thb-secondary-menu {
		<?php thb_typeoutput( $mobilemenu_font ); ?>
}
<?php } ?>
	<?php if ( $em_font = ot_get_option( 'em_font' ) ) { ?>
em {
		<?php thb_typeoutput( $em_font ); ?>
}
<?php } ?>
	<?php if ( $label_font = ot_get_option( 'label_font' ) ) { ?>
label {
		<?php thb_typeoutput( $label_font ); ?>
}
<?php } ?>
	<?php if ( $button_font = ot_get_option( 'button_font' ) ) { ?>
input[type="submit"],
submit,
.button,
.btn,
.btn-block,
.btn-text,
.vc_btn3 {
		<?php thb_typeoutput( $button_font ); ?>
}
<?php } ?>
/* Typography */
	<?php if ( $body_type = ot_get_option( 'body_type' ) ) { ?>
p,
.post-detail .post-content {
		<?php thb_typeoutput( $body_type ); ?>
}
<?php } ?>
	<?php if ( $fullmenu_type = ot_get_option( 'fullmenu_type' ) ) { ?>
.thb-full-menu>li>a,
.thb-header-menu>li>a {
		<?php thb_typeoutput( $fullmenu_type ); ?>
}
<?php } ?>
	<?php if ( $subfooter_fullmenu_type = ot_get_option( 'subfooter_fullmenu_type' ) ) { ?>
.subfooter .thb-full-menu>li>a {
		<?php thb_typeoutput( $subfooter_fullmenu_type ); ?>
}
<?php } ?>
	<?php if ( $fullmenu_sub_type = ot_get_option( 'fullmenu_sub_type' ) ) { ?>
.thb-full-menu li .sub-menu a,
.thb-dropdown-style2 .thb-full-menu .sub-menu>li a,
.thb-dropdown-style2 .thb-full-menu .sub-menu>li.title-item>a,
.thb-dropdown-style3 .thb-full-menu .sub-menu>li a,
.thb-dropdown-style3 .thb-full-menu .sub-menu>li.title-item>a,
.thb-header-menu li .sub-menu a {
		<?php thb_typeoutput( $fullmenu_sub_type ); ?>
}
<?php } ?>
	<?php if ( $fullmenu_social_type = ot_get_option( 'fullmenu_social_type' ) ) { ?>
.thb-full-menu>li>a.social {
		<?php thb_typeoutput( $fullmenu_social_type ); ?>
}
<?php } ?>
	<?php if ( $widget_title_type = ot_get_option( 'widget_title_type' ) ) { ?>
.widget>h6 {
		<?php thb_typeoutput( $widget_title_type ); ?>
}
<?php } ?>
	<?php if ( $footer_type = ot_get_option( 'footer_type' ) ) { ?>
.footer .widget,
.footer .widget p {
		<?php thb_typeoutput( $footer_type ); ?>
}
<?php } ?>
	<?php if ( $mobilemenu_type = ot_get_option( 'mobilemenu_type' ) ) { ?>
.thb-mobile-menu>li>a {
		<?php thb_typeoutput( $mobilemenu_type ); ?>
}
<?php } ?>
	<?php if ( $mobilemenu_sub_type = ot_get_option( 'mobilemenu_sub_type' ) ) { ?>
.thb-mobile-menu .sub-menu a {
		<?php thb_typeoutput( $mobilemenu_sub_type ); ?>
}
<?php } ?>
	<?php if ( $mobilemenu_secondary_type = ot_get_option( 'mobilemenu_secondary_type' ) ) { ?>
.thb-secondary-menu a {
		<?php thb_typeoutput( $mobilemenu_secondary_type ); ?>
}
<?php } ?>
	<?php if ( $mobilemenu_footer_type = ot_get_option( 'mobilemenu_footer_type' ) ) { ?>
#mobile-menu .menu-footer {
		<?php thb_typeoutput( $mobilemenu_footer_type ); ?>
}
<?php } ?>
	<?php if ( $mobilemenu_social_type = ot_get_option( 'mobilemenu_social_type' ) ) { ?>
#mobile-menu .socials a {
		<?php thb_typeoutput( $mobilemenu_social_type ); ?>
}
<?php } ?>
	<?php if ( $subfooter_social_type = ot_get_option( 'subfooter_social_type' ) ) { ?>
.subfooter .socials a {
		<?php thb_typeoutput( $subfooter_social_type ); ?>
}
<?php } ?>
	<?php if ( $shop_product_title = ot_get_option( 'shop_product_title' ) ) { ?>
.products .product.thb-listing-style2 h3,
.products .product.thb-listing-style1 h3 {
		<?php thb_typeoutput( $shop_product_title ); ?>
}
<?php } ?>
	<?php if ( $shop_product_detail_title = ot_get_option( 'shop_product_detail_title' ) ) { ?>
.thb-product-detail .product-information h1.product_title {
		<?php thb_typeoutput( $shop_product_detail_title ); ?>
}
<?php } ?>
	<?php if ( $shop_product_detail_excerpt = ot_get_option( 'shop_product_detail_excerpt' ) ) { ?>
.thb-product-detail .product-information .woocommerce-product-details__short-description,
.thb-product-detail .product-information .woocommerce-product-details__short-description p {
		<?php thb_typeoutput( $shop_product_detail_excerpt ); ?>
}
<?php } ?>
/* Heading Typography */
	<?php if ( $h1_type = ot_get_option( 'h1_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h1,
	.h1 {
		<?php thb_typeoutput( $h1_type, false, false, true ); ?>
	}
}
h1,
.h1 {
		<?php thb_typeoutput( $h1_type ); ?>
}
<?php } ?>
	<?php if ( $h2_type = ot_get_option( 'h2_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h2 {
		<?php thb_typeoutput( $h2_type, false, false, true ); ?>
	}
}
h2 {
		<?php thb_typeoutput( $h2_type ); ?>
}
<?php } ?>
	<?php if ( $h3_type = ot_get_option( 'h3_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h3 {
		<?php thb_typeoutput( $h3_type, false, false, true ); ?>
	}
}
h3 {
		<?php thb_typeoutput( $h3_type ); ?>
}
<?php } ?>
	<?php if ( $h4_type = ot_get_option( 'h4_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h4 {
		<?php thb_typeoutput( $h4_type, false, false, true ); ?>
	}
}
h4 {
		<?php thb_typeoutput( $h4_type ); ?>
}
<?php } ?>
	<?php if ( $h5_type = ot_get_option( 'h5_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h5 {
		<?php thb_typeoutput( $h5_type, false, false, true ); ?>
	}
}
h5 {
		<?php thb_typeoutput( $h5_type ); ?>
}
<?php } ?>
	<?php if ( $h6_type = ot_get_option( 'h6_type' ) ) { ?>
h6 {
		<?php thb_typeoutput( $h6_type ); ?>
}
<?php } ?>
	<?php if ( $secondarymenu_text_font_1 = ot_get_option( 'secondarymenu_text_font_1' ) ) { ?>
.header-secondary-text div p:not(.smaller) {
		<?php thb_typeoutput( $secondarymenu_text_font_1 ); ?>
}
<?php } ?>
	<?php if ( $secondarymenu_text_font_2 = ot_get_option( 'secondarymenu_text_font_2' ) ) { ?>
.header-secondary-text div p.smaller {
		<?php thb_typeoutput( $secondarymenu_text_font_2 ); ?>
}
<?php } ?>

/* Header */
	<?php if ( $logo_height = ot_get_option( 'logo_height' ) ) { ?>
.logolink .logoimg {
	max-height: <?php thb_measurementoutput( $logo_height ); ?>;
}
.logolink .logoimg[src$=".svg"] {
	max-height: 100%;
height: <?php thb_measurementoutput( $logo_height ); ?>;
}
<?php } ?>
	<?php if ( $logo_height_mobile = ot_get_option( 'logo_height_mobile' ) ) { ?>
@media screen and (max-width: 40.0625em) {
	.logolink .logoimg {
		max-height: <?php thb_measurementoutput( $logo_height_mobile ); ?>;
	}
	.logolink .logoimg[src$=".svg"] {
		max-height: 100%;
		height: <?php thb_measurementoutput( $logo_height_mobile ); ?>;
	}
}
<?php } ?>
	<?php if ( $header_border = ot_get_option( 'header_border' ) ) { ?>
.header:not(.fixed):not(.hide-header-items) {
	border-bottom: <?php thb_borderoutput( $header_border ); ?>;
}
<?php } ?>

/* Measurements */
	<?php if ( $header_padding = ot_get_option( 'header_padding' ) ) { ?>
@media only screen and (min-width: 40.0625em) {
	.header {
		<?php thb_paddingoutput( $header_padding ); ?>;
	}
}
<?php } ?>
	<?php if ( $header_padding_fixed = ot_get_option( 'header_padding_fixed' ) ) { ?>
@media only screen and (min-width: 40.0625em) {
	.header.fixed {
		<?php thb_paddingoutput( $header_padding_fixed ); ?>;
	}
}
<?php } ?>
	<?php if ( $header_padding_mobile = ot_get_option( 'header_padding_mobile' ) ) { ?>
@media only screen and (max-width: 40.0625em) {
	.header,
	.header.fixed {
		<?php thb_paddingoutput( $header_padding_mobile ); ?>;
	}
}
<?php } ?>
	<?php if ( $subheader_height = ot_get_option( 'subheader_height' ) ) { ?>
.subheader {
	height: <?php thb_measurementoutput( $subheader_height ); ?>;
}
<?php } ?>
	<?php if ( $secondarymenu_text_svg_height = ot_get_option( 'secondarymenu_text_svg_height' ) ) { ?>
.header-secondary-text svg {
	height: <?php thb_measurementoutput( $secondarymenu_text_svg_height ); ?>;
}
<?php } ?>
	<?php if ( $footer_padding = ot_get_option( 'footer_padding' ) ) { ?>
.footer {
		<?php thb_paddingoutput( $footer_padding ); ?>
}
<?php } ?>
	<?php if ( $subfooter_padding = ot_get_option( 'subfooter_padding' ) ) { ?>
.subfooter {
		<?php thb_paddingoutput( $subfooter_padding ); ?>
}
<?php } ?>
	<?php if ( $footerbar_padding = ot_get_option( 'footerbar_padding' ) ) { ?>
.footer-bar-container {
		<?php thb_paddingoutput( $footerbar_padding ); ?>
}
<?php } ?>
	<?php if ( $menu_margin = ot_get_option( 'menu_margin' ) ) { ?>
.thb-full-menu>li+li {
	margin-left: <?php thb_measurementoutput( $menu_margin ); ?>
}
<?php } ?>

/* Colors */
	<?php if ( $accent_color = ot_get_option( 'accent_color' ) ) { ?>
	a:hover,
	.thb-full-menu.thb-standard>li.current-menu-item:not(.has-hash)>a,
	.thb-full-menu>li a:not(.logolink)[data-filter].active,
	#mobile-menu.dark .thb-mobile-menu>li>a:hover,
	#mobile-menu.dark .sub-menu a:hover,
	#mobile-menu.dark .thb-secondary-menu a:hover,
	.thb-mobile-menu>li.menu-item-has-children>a:hover .thb-arrow div,
	.thb-secondary-menu a:hover,
	.authorpage .author-content .square-icon:hover,
	.authorpage .author-content .square-icon.email:hover,
	.commentlist .comment .reply a:hover,
	input[type="submit"].style3,.button.style3,.btn.style3,
	input[type="submit"].style4,.button.style4,.btn.style4,
	input[type="submit"].style4:hover,.button.style4:hover,.btn.style4:hover,
	.more-link,
	.thb-portfolio-filter.style1 ul li a:hover,.thb-portfolio-filter.style1 ul li a.active,
	.thb-portfolio-filter.style2 .select2.select2-container--default .select2-selection--single .select2-selection__rendered,
	.thb-portfolio-filter.style2 .select2-dropdown .select2-results__options .select2-results__option[aria-selected=true] span,
	.thb-portfolio-filter.style2 .select2-dropdown .select2-results__options .select2-results__option.select2-results__option--highlighted span,
	.thb-autotype .thb-autotype-entry,
	.thb-tabs.style3 .vc_tta-panel-heading h4 a:hover,
	.thb-tabs.style3 .vc_tta-panel-heading h4 a.active,
	.thb-tabs.style4 .vc_tta-panel-heading h4 a.active,
	.thb-tabs.style4 .vc_tta-panel-heading h4 a:hover,
	.thb_location_container.row .thb_location h5,
	.thb-portfolio-slider.thb-portfolio-slider-style3 .portfolio-slide .content-side .thb-categories,
	.thb-portfolio-slider.thb-portfolio-slider-style3 .portfolio-slide .content-side .thb-categories a,
	.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method.payment_method_paypal .about_paypal,
	input[type="submit"].style2, .button.style2, .btn.style2,
	.thb-header-menu > li.menu-item-has-children:hover > a,
	.thb-header-menu > li.menu-item-has-children.sfHover > a,
	.thb-header-menu > li.menu-item-has-children:hover>a span:after,
	.thb-header-menu > li.menu-item-has-children.sfHover > a span:after,
	.thb-pricing-table.style2 .pricing-container .thb_pricing_head .thb-price,
	.post.style8 .style8-meta .style8-link a,
	.thb-iconbox.top.type5 .iconbox-content .thb-read-more,
	.thb-testimonials.style7 .testimonial-author cite,
	.thb-testimonials.style7 .testimonial-author span,
	.post.style9.active .post-title a,
	.columns.thb-light-column .post.style9 .post-category a,
	.thb-page-header .thb-blog-categories li a.active,
	.has-thb-accent-color,
	.wp-block-button .wp-block-button__link.has-thb-accent-color,
	.thb-page-menu.style2 li:hover a,
	.thb-page-menu.style2 li.current_page_item a,
	.post.style10 .style10-readmore,
	.btn-text.style-accent-link,
	.thb-page-menu li.current_page_item a {
		color:<?php echo esc_attr( $accent_color ); ?>;
	}
	.thb-full-menu.thb-line-marker>li>a:before,
	.thb-page-header .thb-blog-categories li a:after,
	.select2-container .select2-dropdown .select2-results .select2-results__option[aria-selected=true],
	input[type="submit"],.button,.btn,
	input[type="submit"].black:hover,input[type="submit"].wc-forward.checkout:hover,.button.black:hover,
	.button.wc-forward.checkout:hover,.btn.black:not(.style4):hover,.btn.wc-forward.checkout:hover,
	input[type="submit"].style2:hover,.button.style2:hover,.btn.style2:hover,
	input[type="submit"].style3:before,.button.style3:before,.btn.style3:before,
	input[type="submit"].style4:after,.button.style4:after,.btn.style4:after,
	.btn-text.style3 .circle-btn,
	[class^="tag-cloud-link"]:hover,
	.thb-portfolio-filter.style1 ul li a:before,
	.thb-portfolio-filter.style2 .select2.select2-container--default .select2-selection--single .select2-selection__arrow:after,
	.thb-portfolio-filter.style2 .select2.select2-container--default .select2-selection--single .select2-selection__arrow:before,
	.thb-portfolio-filter.style2 .select2-dropdown .select2-results__options .select2-results__option span:before,
	.boxed-icon.email:hover,
	.thb-progressbar .thb-progress span,
	#scroll_to_top:hover .thb-animated-arrow.circular,
	.thb-tabs.style1 .vc_tta-panel-heading h4 a:before,
	.thb-tabs.style4 .vc_tta-panel-heading h4 a:before,
	.thb-client-row.thb-opacity.with-accent .thb-client:hover,
	.badge.onsale,
	.demo_store,
	.products .product .product_after_title .button:hover:after,
	.woocommerce-MyAccount-navigation ul li:hover a,.woocommerce-MyAccount-navigation ul li.is-active a,
	.footer_bar .socials .social.email:hover,
	.thb-header-menu > li.menu-item-has-children > a span:before,
	.thb-page-menu.style1 li:hover a, .thb-page-menu.style1 li.current_page_item a,
	.thb-client-row .style4 .accent-color,
	.preloader-style3-container:before,
	.preloader-style3-container:after,
	.has-thb-accent-background-color,
	.wp-block-button .wp-block-button__link.has-thb-accent-background-color,
	.thb-portfolio-slider.thb-portfolio-slider-style7 .portfolio-style7-dots-wrapper .thb-portfolio-slider-style7-bullets:before {
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
	input[type="submit"]:hover,
	.button:hover,
	.btn:hover {
		background-color: <?php echo esc_attr( thb_adjustColorLightenDarken( $accent_color, 7 ) ); ?>;
	}
	.share_container .product_copy form,
	input[type="text"]:focus,input[type="password"]:focus,input[type="date"]:focus,
	input[type="datetime"]:focus,input[type="email"]:focus,input[type="number"]:focus,
	input[type="search"]:focus,input[type="tel"]:focus,input[type="time"]:focus,input[type="url"]:focus,textarea:focus,
	.select2.select2-container--default.select2-container--open .select2-selection--single,
	.select2-container .select2-dropdown,
	.select2-container .select2-dropdown.select2-drop-active,
	input[type="submit"].style2,.button.style2,.btn.style2,
	input[type="submit"].style3,.button.style3,.btn.style3,
	input[type="submit"].style4,.button.style4,.btn.style4,
	[class^="tag-cloud-link"]:hover,
	.boxed-icon.email:hover,
	.wpb_text_column a:not(.btn):not(.button):after,
	.thb-client-row.has-border.thb-opacity.with-accent .thb-client:hover,
	.thb-pricing-table.style1 .thb-pricing-column.highlight-true .pricing-container,
	.woocommerce-MyAccount-navigation ul li:hover a,.woocommerce-MyAccount-navigation ul li.is-active a,
	.footer_bar .socials .social.email:hover,
	.thb-iconbox.top.type5,
	.thb-page-menu.style1 li:hover a, .thb-page-menu.style1 li.current_page_item a,
	.post.style9 .style9-title .style9-arrow:hover,
	.post.style9.active .style9-arrow,
	.thb-testimonials.style9 .slick-dots-wrapper .slick-dots li.slick-active .portrait_bullet,
	.thb-iconbox.type7:hover {
		border-color: <?php echo esc_attr( $accent_color ); ?>;
	}
	.select2-container .select2-dropdown.select2-drop-active.select2-drop-above,
	.woocommerce-MyAccount-navigation ul li:hover+li a,.woocommerce-MyAccount-navigation ul li.is-active+li a,
	.thb-page-menu.style1 li:hover+li a, .thb-page-menu.style1 li.current_page_item+li a,
	.thb-dropdown-style3 .thb-full-menu .sub-menu {
		border-top-color: <?php echo esc_attr( $accent_color ); ?>;
	}
	.thb-dropdown-style3 .thb-full-menu .sub-menu:after {
		border-bottom-color: <?php echo esc_attr( $accent_color ); ?>;
	}
	.commentlist .comment .reply a:hover svg path,
	.btn-text.style4 .arrow svg:first-child,
	.thb-iconbox.top.type5 .iconbox-content .thb-read-more svg,
	.thb-iconbox.top.type5 .iconbox-content .thb-read-more svg .bar,
	.post.style9.active .style9-arrow svg {
		fill: <?php echo esc_attr( $accent_color ); ?>;
	}
	.thb-tabs.style2 .vc_tta-panel-heading h4 a.active {
		-moz-box-shadow:inset 0 -3px 0 <?php echo esc_attr( $accent_color ); ?>,0 1px 0 <?php echo esc_attr( $accent_color ); ?>;
		-webkit-box-shadow:inset 0 -3px 0 <?php echo esc_attr( $accent_color ); ?>,0 1px 0 <?php echo esc_attr( $accent_color ); ?>;
		box-shadow:inset 0 -3px 0 <?php echo esc_attr( $accent_color ); ?>,0 1px 0 <?php echo esc_attr( $accent_color ); ?>;
	}
	.thb-fancy-box.fancy-style5:hover .thb-fancy-content {
		-moz-box-shadow:inset 0 -3px 0 <?php echo esc_attr( $accent_color ); ?>;
		-webkit-box-shadow:inset 0 -3px 0 <?php echo esc_attr( $accent_color ); ?>;
		box-shadow:inset 0 -3px 0 <?php echo esc_attr( $accent_color ); ?>;
	}
<?php } ?>
	<?php if ( $mobile_menu_icon_color = ot_get_option( 'mobile_menu_icon_color' ) ) { ?>
.mobile-toggle-holder .mobile-toggle span {
	background: <?php echo esc_attr( $mobile_menu_icon_color ); ?>;
}
.mobile-toggle-holder.style4 .mobile-toggle span:before,
.mobile-toggle-holder.style4 .mobile-toggle span:after {
	background: <?php echo esc_attr( $mobile_menu_icon_color ); ?>;
}
<?php } ?>
	<?php if ( $post_category_color = ot_get_option( 'post_category_color' ) ) { ?>
.post .post-category a,
.columns.thb-light-column .post .post-category a {
	color: <?php echo esc_attr( $post_category_color ); ?>;
}
<?php } ?>
	<?php if ( $general_text_color = ot_get_option( 'general_text_color' ) ) { ?>
body {
	color: <?php echo esc_attr( $general_text_color ); ?>;
}
<?php } ?>
	<?php if ( $widget_title_color = ot_get_option( 'widget_title_color' ) ) { ?>
.widget>h6 {
	color: <?php echo esc_attr( $widget_title_color ); ?>;
}
<?php } ?>
	<?php if ( $widget_text_color = ot_get_option( 'widget_text_color' ) ) { ?>
.widget,
.widget p {
	color: <?php echo esc_attr( $widget_text_color ); ?>;
}
<?php } ?>
	<?php if ( $footer_widget_title_color = ot_get_option( 'footer_widget_title_color' ) ) { ?>
.footer .widget>h6,
.footer.dark .widget>h6 {
	color: <?php echo esc_attr( $footer_widget_title_color ); ?>;
}
<?php } ?>
	<?php if ( $footer_text_color = ot_get_option( 'footer_text_color' ) ) { ?>
.footer p,
.footer.dark p {
	color: <?php echo esc_attr( $footer_text_color ); ?>;
}
<?php } ?>
	<?php if ( $subfooter_text_color = ot_get_option( 'subfooter_text_color' ) ) { ?>
.subfooter p,
.subfooter.dark p {
	opacity: 1;
	color: <?php echo esc_attr( $subfooter_text_color ); ?>;
}
<?php } ?>
	<?php if ( $subheader_text_color = ot_get_option( 'subheader_text_color' ) ) { ?>
.subheader p,
.subheader.dark p {
	color: <?php echo esc_attr( $subheader_text_color ); ?>;
}
<?php } ?>
	<?php if ( $preloader_color = ot_get_option( 'preloader_color' ) ) { ?>
.thb-page-preloader .material-spinner .material-path {
	animation: material-dash 1.4s ease-in-out infinite;
	stroke: <?php echo esc_attr( $preloader_color ); ?>;
}
.preloader-style3-container:before,
.preloader-style3-container:after {
	background: <?php echo esc_attr( $preloader_color ); ?>;
}
.preloader-style2-container .thb-dot.dot-1,
.preloader-style2-container .thb-dot.dot-3,
.preloader-style2-container .thb-dot.dot-2 {
	background: <?php echo esc_attr( $preloader_color ); ?>;
}
<?php } ?>
	<?php if ( $dropdown_style3_color = ot_get_option( 'dropdown_style3_color' ) ) { ?>
.thb-dropdown-style3 .thb-full-menu .sub-menu {
	border-top-color: <?php echo esc_attr( $dropdown_style3_color ); ?>;
}
.thb-dropdown-style3 .thb-full-menu .sub-menu:after {
	border-bottom-color: <?php echo esc_attr( $dropdown_style3_color ); ?>;
}
<?php } ?>
	<?php if ( $thb_underline_color = ot_get_option( 'thb_underline_color' ) ) { ?>
.thb-full-menu.thb-underline>li>a:before {
	background: <?php echo esc_attr( $thb_underline_color ); ?> !important;
}
<?php } ?>
/* Link Colors */
	<?php if ( $general_link_color = ot_get_option( 'general_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $general_link_color, '.post-content p' ); ?>
		<?php thb_linkcoloroutput( $general_link_color, '.wpb_text_column p' ); ?>
		<?php thb_linkcoloroutput( $general_link_color, '.wpb_text_column ul' ); ?>
		<?php thb_linkcoloroutput( $general_link_color, '.wpb_text_column ol' ); ?>
		<?php thb_linkcoloroutput( $general_link_color, '.widget p' ); ?>
<?php } ?>
	<?php if ( $fullmenu_link_color_dark = ot_get_option( 'fullmenu_link_color_dark' ) ) { ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_dark, '.header.dark-header .thb-full-menu>li>' ); ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_dark, '.thb-header-menu>li>' ); ?>
<?php } ?>
	<?php if ( $fullmenu_link_color_light = ot_get_option( 'fullmenu_link_color_light' ) ) { ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_light, '.header.light-header .thb-full-menu>li>', true ); ?>
<?php } ?>
	<?php if ( $submenu_link_color = ot_get_option( 'submenu_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $submenu_link_color, '.thb-full-menu .sub-menu li' ); ?>
		<?php thb_linkcoloroutput( $submenu_link_color, '.thb-dropdown-color-light .thb-full-menu .sub-menu li' ); ?>
		<?php thb_linkcoloroutput( $submenu_link_color, '.thb-header-menu li .sub-menu' ); ?>
<?php } ?>
	<?php if ( $widget_link_color = ot_get_option( 'widget_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $widget_link_color, '.widget' ); ?>
<?php } ?>
	<?php if ( $footer_link_color = ot_get_option( 'footer_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $footer_link_color, '.footer .widget' ); ?>
		<?php
		if ( 'dark' === ot_get_option( 'footer_color' ) ) {
			thb_linkcoloroutput( $footer_link_color, '.footer.dark .widget' ); }
		?>
<?php } ?>
	<?php if ( $subfooter_link_color = ot_get_option( 'subfooter_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $subfooter_link_color, '.subfooter' ); ?>
<?php } ?>
	<?php if ( $subheader_link_color = ot_get_option( 'subheader_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $subheader_link_color, '.subheader' ); ?>
<?php } ?>
	<?php if ( $mobilemenu_link_color = ot_get_option( 'mobilemenu_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $mobilemenu_link_color, '#mobile-menu .thb-mobile-menu>li>' ); ?>
		<?php thb_linkcoloroutput( $mobilemenu_link_color, '#mobile-menu.dark .thb-mobile-menu>li>' ); ?>
<?php } ?>
	<?php if ( $mobilemenu_submenu_link_color = ot_get_option( 'mobilemenu_submenu_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $mobilemenu_submenu_link_color, '#mobile-menu.dark .sub-menu' ); ?>
		<?php thb_linkcoloroutput( $mobilemenu_submenu_link_color, '#mobile-menu.light .sub-menu' ); ?>
<?php } ?>
	<?php if ( $mobilemenu_secondary_link_color = ot_get_option( 'mobilemenu_secondary_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $mobilemenu_secondary_link_color, '#mobile-menu .thb-secondary-menu' ); ?>
		<?php thb_linkcoloroutput( $mobilemenu_secondary_link_color, '#mobile-menu.dark .thb-secondary-menu' ); ?>
<?php } ?>

/* Backgrounds */
.page-id-<?php echo esc_attr( $id ); ?> #wrapper div[role="main"],
.postid-<?php echo esc_attr( $id ); ?> #wrapper div[role="main"] {
	<?php thb_bgoutput( get_post_meta( $id, 'page_bg', true ) ); ?>
}
	<?php if ( $mobilemenu_bg = ot_get_option( 'mobilemenu_bg' ) ) { ?>
#mobile-menu {
		<?php thb_bgoutput( $mobilemenu_bg ); ?>
}
<?php } ?>
	<?php if ( $subheader_bg = ot_get_option( 'subheader_bg' ) ) { ?>
.subheader {
		<?php thb_bgoutput( $subheader_bg ); ?>
}
<?php } ?>
	<?php if ( $header_bg = ot_get_option( 'header_bg' ) ) { ?>
.header:not(.fixed) {
		<?php thb_bgoutput( $header_bg ); ?>
}
<?php } ?>
	<?php if ( $fixed_header_bg = ot_get_option( 'fixed_header_bg' ) ) { ?>
.header.fixed {
		<?php thb_bgoutput( $fixed_header_bg ); ?>
}
<?php } ?>
	<?php if ( $header_style1_bg = ot_get_option( 'header_style1_bg' ) ) { ?>
.header.style1 .header_overlay_menu {
		<?php thb_bgoutput( $header_style1_bg ); ?>
}
<?php } ?>
	<?php if ( $search_bg = ot_get_option( 'search_bg' ) ) { ?>
.thb-search-popup {
		<?php thb_bgoutput( $search_bg ); ?>
}
<?php } ?>
	<?php if ( $full_menu_dropdown_bg = ot_get_option( 'full_menu_dropdown_bg' ) ) { ?>
.thb-full-menu .sub-menu,
.thb-dropdown-style2 .thb-full-menu .sub-menu:after {
		<?php thb_bgoutput( $full_menu_dropdown_bg ); ?>
}
<?php } ?>
	<?php if ( $footer_bg = ot_get_option( 'footer_bg' ) ) { ?>
.footer {
		<?php thb_bgoutput( $footer_bg ); ?>
}
<?php } ?>
	<?php if ( $subfooter_bg = ot_get_option( 'subfooter_bg' ) ) { ?>
.subfooter {
		<?php thb_bgoutput( $subfooter_bg ); ?>
}
<?php } ?>
	<?php if ( $footerbar_bg = ot_get_option( 'footerbar_bg' ) ) { ?>
.footer-bar-container {
		<?php thb_bgoutput( $footerbar_bg ); ?>
}
<?php } ?>
	<?php if ( $notfound_bg = ot_get_option( 'notfound_bg' ) ) { ?>
.error404 #wrapper [role="main"] {
		<?php thb_bgoutput( $notfound_bg ); ?>
}
<?php } ?>
	<?php if ( $preloader_bg = ot_get_option( 'preloader_bg' ) ) { ?>
.thb-page-preloader {
		<?php thb_bgoutput( $preloader_bg ); ?>
}
<?php } ?>
	<?php if ( $page_transition_overlay_bg = ot_get_option( 'page_transition_overlay_bg' ) ) { ?>
.thb-page-transition-overlay {
		<?php thb_bgoutput( $page_transition_overlay_bg ); ?>
}
<?php } ?>
	<?php if ( $newsletter_bg = ot_get_option( 'newsletter_bg' ) ) { ?>
.theme-popup.newsletter-popup {
		<?php thb_bgoutput( $newsletter_bg ); ?>
}
<?php } ?>
/* Footer */
	<?php if ( $footer_logo_height = ot_get_option( 'footer_logo_height' ) ) { ?>
.footer .footer-logo-holder .footer-logolink .logoimg {
	max-height: <?php thb_measurementoutput( $footer_logo_height ); ?>;
}
<?php } ?>

/* Sub-Footer */
	<?php if ( $subfooter_logo_height = ot_get_option( 'subfooter_logo_height' ) ) { ?>
.subfooter .footer-logo-holder .logoimg {
	max-height: <?php thb_measurementoutput( $subfooter_logo_height ); ?>;
}
<?php } ?>

/* Shop */
	<?php if ( thb_wc_supported() ) { ?>
		<?php if ( is_shop() || is_product_tag() ) { ?>
			<?php if ( $shop_header_bg = ot_get_option( 'shop_header_bg' ) ) { ?>
			.post-type-archive-product .thb-page-header,
			.tax-product_tag .thb-page-header {
				<?php thb_bgoutput( $shop_header_bg ); ?>
			}
		<?php } ?>
			<?php
		} elseif ( is_product_category() ) {
			$cat       = get_queried_object();
			$cat_id    = $cat->term_id;
			$header_id = get_term_meta( $cat_id, 'header_id', true );

			$image = wp_get_attachment_url( $header_id, 'full' );
			?>
		.tax-product_cat.term-<?php echo esc_attr( $cat_id ); ?> .thb-page-header.style2 {
			background-image: url(<?php echo esc_url( $image ); ?>);
		}
		<?php } ?>
	<?php } ?>
/* Site Borders */
	<?php if ( ot_get_option( 'site_borders' ) === 'on' ) { ?>
	.thb-borders {
		border-color: <?php echo esc_attr( ot_get_option( 'site_borders_color' ) ); ?>;
	}
		<?php if ( $site_borders_width = ot_get_option( 'site_borders_width' ) ) { ?>
	@media only screen and (min-width: 40.063em) {
.thb-borders {
border-width: <?php thb_measurementoutput( $site_borders_width ); ?>;
}
.thb-borders-on .header {
margin-top: <?php thb_measurementoutput( $site_borders_width ); ?>;
}
.thb-borders-on .subfooter {
margin-bottom: <?php thb_measurementoutput( $site_borders_width ); ?>;
}
.thb-borders-on #scroll_to_top {
margin-right: <?php thb_measurementoutput( $site_borders_width ); ?>;
margin-bottom: <?php thb_measurementoutput( $site_borders_width ); ?>;
}
}
	<?php } ?>
	<?php } ?>
/* Grid Size */
	<?php if ( $thb_grid_size = ot_get_option( 'thb_grid_size' ) ) { ?>
.row {
	max-width: <?php thb_measurementoutput( $thb_grid_size ); ?>;
}
.row.max_width {
	max-width: <?php thb_measurementoutput( $thb_grid_size ); ?> !important;
}
	<?php } ?>
/* Extra CSS */
	<?php echo ot_get_option( 'extra_css' ); ?>
	<?php
	$out = ob_get_clean();
	/* Remove comments */
	$out = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $out );
	/* Remove space after colons */
	$out = str_replace( ': ', ':', $out );
	/* Remove whitespace */
	$out = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $out );

	return $out;
}
