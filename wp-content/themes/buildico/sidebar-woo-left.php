<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package enova
 */
$enova_sidebar_pos = wt_get_option('woo_sidebar_position');
if( get_post_meta( get_the_ID(), "custom_page_sidebar", true ) ){
	$enova_sidebar_settings = get_post_meta( get_the_ID(), "custom_page_sidebar", true );
}else{
	$enova_sidebar_settings = array();
}
if( 'none' === $enova_sidebar_pos || 'left' != $enova_sidebar_pos || is_cart() ){
	return;
}
if ( ! is_active_sidebar( 'woo-left-sidebar' ) ) {
	return;
}
?>

<div class="col-lg-3 widget-area woo-sidebar" id="woo-left-sidebar" role="complementary">
	<div class="sidebar-inner">
	<?php
	if(!empty( $enova_sidebar_settings['select_sidebars'] ) ){
		if( $enova_sidebar_settings['select_sidebars'] != 'default' && $enova_sidebar_settings['select_sidebars'] != 'footerfull' ){
			if ( ! is_active_sidebar( esc_attr( $enova_sidebar_settings['select_sidebars'] ) ) ) {
				return;
			}else{
				dynamic_sidebar( esc_attr( $enova_sidebar_settings['select_sidebars'] ) );
			}
		}else{
			if ( ! is_active_sidebar( 'woo-left-sidebar' ) ) {
				return;
			}else{
				dynamic_sidebar( 'woo-left-sidebar' );
			}
		}
	}else{
		if ( ! is_active_sidebar( 'woo-left-sidebar' ) ) {
			return;
		}else{
			dynamic_sidebar( 'woo-left-sidebar' );
		}
	}
	 ?>
	</div>
</div><!-- #secondary -->
