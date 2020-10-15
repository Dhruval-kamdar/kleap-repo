<?php
/**
 * The right sidebar containing the main widget area.
 *
 * @package enova
 */
$enova_sidebar_pos = wt_get_option('woo_sidebar_position');
if( 'none' === $enova_sidebar_pos || 'right' != $enova_sidebar_pos || is_cart() ){
	return;
}
if ( ! is_active_sidebar( 'woo-right-sidebar' ) ) {
	return;
}
?>

<div class="col-lg-3 widget-area woo-sidebar" id="woo-right-sidebar" role="complementary">
	<div class="sidebar-inner">
    	<?php
        if ( ! is_active_sidebar( 'woo-right-sidebar' ) ) {
            return;
        }else{
            dynamic_sidebar( 'woo-right-sidebar' );
        } ?>
	</div>
</div><!-- #secondary -->
