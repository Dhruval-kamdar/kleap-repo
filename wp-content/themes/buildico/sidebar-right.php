<?php
/**
 * The right sidebar containing the main widget area.
 *
 * @package buildico
 */
if( ! is_archive() && ! is_search() && ! is_author() ){
	if( get_post_meta( get_the_ID(), "custom_page_sidebar", true ) ){
		$buildico_sidebar_settings = get_post_meta( get_the_ID(), "custom_page_sidebar", true );
	}else{
		$buildico_sidebar_settings = array();
	}
}
?>

<div class="col-md-3 widget-area" id="right-sidebar" role="complementary">
	<?php
	if( ! is_archive() && ! is_search() && ! is_author() ){
		if(!empty( $buildico_sidebar_settings['select_sidebars'] ) ){
			if( $buildico_sidebar_settings['select_sidebars'] != 'default' && $buildico_sidebar_settings['select_sidebars'] != 'footerfull' ){
				if ( ! is_active_sidebar( esc_attr( $buildico_sidebar_settings['select_sidebars'] ) ) ) {
					echo '<aside id="text-2" class="widget widget_text">
			<h3 class="widget-title">Please Add Widgets</h3>
			<div class="textwidget">
				<p>Please add widgets from <a href="./wp-admin/widgets.php"><b>here</b></a>.</p>
			</div>
		</aside>';
				}else{
					dynamic_sidebar( esc_attr( $buildico_sidebar_settings['select_sidebars'] ) );
				}
			}else{
				if ( ! is_active_sidebar( 'right-sidebar' ) ) {
					echo '<aside id="text-2" class="widget widget_text">
			<h3 class="widget-title">Please Add Widgets</h3>
			<div class="textwidget">
				<p>Please add widgets from <a href="./wp-admin/widgets.php"><b>here</b></a>.</p>
			</div>
		</aside>';
				}else{
					dynamic_sidebar( 'right-sidebar' );
				}
			}
		}else{
			if ( ! is_active_sidebar( 'right-sidebar' ) ) {
				echo '<aside id="text-2" class="widget widget_text">
			<h3 class="widget-title">Please Add Widgets</h3>
			<div class="textwidget">
				<p>Please add widgets from <a href="./wp-admin/widgets.php"><b>here</b></a>.</p>
			</div>
		</aside>';
			}else{
				dynamic_sidebar( 'right-sidebar' );
			}
		}
	}else{
		if ( ! is_active_sidebar( 'right-sidebar' ) ) {
			echo '<aside id="text-2" class="widget widget_text">
			<h3 class="widget-title">Please Add Widgets</h3>
			<div class="textwidget">
				<p>Please add widgets from <a href="./wp-admin/widgets.php"><b>here</b></a>.</p>
			</div>
		</aside>';
		}else{
			dynamic_sidebar( 'right-sidebar' );
		}
	} ?>

</div><!-- #secondary -->
