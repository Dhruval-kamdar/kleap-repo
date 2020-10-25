<?php function thb_fancybox( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_fancybox', $atts );
	extract( $atts );

	$element_id = uniqid( 'thb-fancy-box-' );

	$el_class[] = 'thb-fancy-box';
	$el_class[] = $box_shadow;
	$el_class[] = $thb_text_color;
	$el_class[] = $style;
	$el_class[] = $extra_class;
	$el_class[] = $thb_text_alignment;
	$el_class[] = $animation;
	$el_class[] = in_array( $style, array( 'fancy-style2' ), true ) ? 'thb_3dimg' : '';
	$el_class[] = $icon ? 'has-icon' : false;

	$btn_class[] = 'fancy-style6' === $style ? 'button small style2' : 'fancy-text-link';
	$btn_class[] = 'fancy-light' === $thb_text_color ? 'white' : 'black';
	$btn_class[] = 'pill-radius';
	$link        = ( '||' === $link ) ? '' : $link;
	$link        = vc_build_link( $link );

	$link_to  = $link['url'];
	$a_title  = $link['title'];
	$a_target = $link['target'] ? $link['target'] : '_self';

	$el_class[] = $link['url'] ? 'has-link' : '';
	$out        = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<?php if ( $link && $link_to ) { ?>
		<a href="<?php echo esc_attr( $link_to ); ?>" class="thb-fancy-link 
							<?php
							if ( in_array( $style, array( 'fancy-style2' ), true ) ) {
								?>
			atvImg-layer<?php } ?>" target="<?php echo sanitize_text_field( $a_target ); ?>">
			<?php if ( 'fancy-style1' === $style ) { ?>
				<div class="thb-animated-arrow circular arrow-right"><?php get_template_part( 'assets/img/svg/prev_arrow.svg' ); ?></div>
			<?php } elseif ( in_array( $style, array( 'fancy-style6', 'fancy-style7', 'fancy-style8' ), true ) ) { ?>
				<div class="<?php echo esc_attr( implode( ' ', $btn_class ) ); ?>"><span><?php echo esc_attr( $a_title ); ?></span></div>
			<?php } ?>
		</a>
		<?php } ?>
		<div class="thb-fancy-image-container 
		<?php
		if ( in_array( $style, array( 'fancy-style2' ), true ) ) {
			?>
			atvImg-layer<?php } ?>">
			<div class="thb-fancy-image">
				<?php echo wp_get_attachment_image( $image, 'revolution-tall-x3' ); ?>
			</div>
		</div>
		<?php if ( in_array( $style, array( 'fancy-style1', 'fancy-style4' ), true ) ) { ?>
		<div class="thb-fancy-hover"></div>
		<?php } ?>
		<div class="thb-fancy-content 
		<?php
		if ( in_array( $style, array( 'fancy-style2' ), true ) ) {
			?>
			atvImg-layer<?php } ?>">
			<?php
			if ( 'fancy-style5' !== $icon && $style ) {
				get_template_part( 'assets/svg/' . $icon ); }
			?>
			<div class="thb-fancy-text-content">
				<?php echo wp_kses_post( force_balance_tags( $content ) ); ?>
			</div>
		</div>
		<style>
			#<?php echo esc_attr( $element_id ); ?>,
			#<?php echo esc_attr( $element_id ); ?>.fancy-style5 .thb-fancy-image-container,
			#<?php echo esc_attr( $element_id ); ?> .atvImg-container,
			#<?php echo esc_attr( $element_id ); ?> .atvImg-layers {
				min-height: <?php echo esc_attr( $height ); ?>;
			}
			<?php if ( $border_radius ) { ?>
				#<?php echo esc_attr( $element_id ); ?>,
				#<?php echo esc_attr( $element_id ); ?> .thb-fancy-image-container,
				#<?php echo esc_attr( $element_id ); ?> .thb-fancy-image,
				#<?php echo esc_attr( $element_id ); ?> .thb-fancy-image img,
				#<?php echo esc_attr( $element_id ); ?> .thb-fancy-content,
				#<?php echo esc_attr( $element_id ); ?> .thb-fancy-hover {
					border-radius: <?php echo esc_attr( $border_radius ); ?>;
				}
			<?php } ?>
			<?php if ( 'fancy-style1' === $style || 'fancy-style4' === $style ) { ?>
				<?php if ( $bg_gradient1 && $bg_gradient2 ) { ?>
					#<?php echo esc_attr( $element_id ); ?> .thb-fancy-hover {
						<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
					}
				<?php } ?>
			<?php } ?>
			<?php if ( 'fancy-style8' === $style ) { ?>
				<?php if ( $bg_gradient1 && $bg_gradient2 ) { ?>
					#<?php echo esc_attr( $element_id ); ?>.fancy-style8:after {
						<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
					}
				<?php } elseif ( $bg_gradient1 && ! $bg_gradient2 ) { ?>
					#<?php echo esc_attr( $element_id ); ?>.fancy-style8:after {
						background: <?php echo esc_attr( $bg_gradient1 ); ?>
					}
				<?php } ?>
			<?php } ?>
		</style>
	</div>

	<?php

	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_fancybox', 'thb_fancybox' );
