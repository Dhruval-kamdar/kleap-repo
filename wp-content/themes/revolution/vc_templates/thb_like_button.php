<?php function thb_like_button( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_like_button', $atts );
	extract( $atts );
	$thb_id = get_queried_object_id();

	$likes = thb_get_post_likes( $thb_id );

	$class[] = 'thb-like-button';
	$class[] = $alignment;
	$class[] = $likes['like'] ? 'active' : '';

	$count = $likes['count'];

	ob_start(); ?>
	
	<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" data-id="<?php echo esc_attr( $thb_id ); ?>">
		<div class="heart-wrapper">
		  <div class="heart"></div>
		  <div class="ring"></div>
		  <div class="circles"></div>
		</div>
		<span class="counter like-count" data-count="<?php echo esc_html( $count ); ?>" data-speed="1000">0</span>
	</div>
	
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_like_button', 'thb_like_button' );
