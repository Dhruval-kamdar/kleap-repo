<?php function thb_button_text( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_button_text', $atts );
	extract( $atts );

	$link = ( '||' === $link ) ? '' : $link;
	$link = vc_build_link( $link );

	$link_to  = $link['url'];
	$a_title  = $link['title'];
	$a_target = $link['target'] ? $link['target'] : '_self';

	$class[] = 'btn-text';
	$class[] = $extra_class;
	$class[] = $animation;
	$class[] = $style;
	$out     = '';

	ob_start();
	?>
	<a class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" href="<?php echo esc_attr( $link_to ); ?>" target="<?php echo esc_attr( $a_target ); ?>" role="button" title="<?php echo esc_attr( $a_title ); ?>">
		<?php
		if ( 'style3' === $style ) {
			?>
		<strong class="circle-btn"></strong><?php } ?><span><?php echo esc_attr( $a_title ); ?></span>
		<?php
		if ( 'style4' === $style ) {
			?>
		<div class="arrow"><div><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?></div></div><?php } ?>
		<?php
		if ( 'style5' === $style ) {
			?>
	<div class="arrow"><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?></div><?php } ?></a>

	<?php
	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_button_text', 'thb_button_text' );
