<?php function thb_page_menu( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_page_menu', $atts );
	extract( $atts );

	$out = '';
	ob_start();

	$args = array(
		'menu'       => $menu,
		'depth'      => 1,
		'container'  => false,
		'menu_class' => 'thb-page-menu ' . $style,
	);

	if ( 'style2' === $style ) {
		$args['link_before'] = '<span>';
		$args['link_after']  = '</span><i class="fa fa-angle-right"></i>';
	}
	?>
	<div class="thb-page-menu-container">
		<?php wp_nav_menu( $args ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_page_menu', 'thb_page_menu' );
