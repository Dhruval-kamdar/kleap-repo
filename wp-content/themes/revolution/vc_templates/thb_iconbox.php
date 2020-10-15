<?php function thb_iconbox( $atts, $content = null ) {
	$animation      = 'true';
	$thb_icon_color = '';
	$alignment      = 'text-center';
	$atts           = vc_map_get_attributes( 'thb_iconbox', $atts );
	extract( $atts );

	$out = '';
	ob_start();
	$element_id = uniqid( 'thb-iconbox-' );
	$link       = vc_build_link( $link );
	$link_to    = $link['url'];
	$a_title    = $link['title'] ? $link['title'] : esc_html__( 'Read More', 'revolution' );
	$a_target   = $link['target'] ? $link['target'] : '_self';
	$el         = 'div';
	if ( $link_to ) {
		$el         = 'a';
		$el_class[] = 'has-link';
	}
	$el_class[] = 'thb-iconbox';
	$el_class[] = $type;
	$el_class[] = $extra_class;
	$el_class[] = $icon_image ? 'has-img' : '';
	$el_class[] = $icon_image_hover ? 'has-hover-image' : '';
	$el_class[] = in_array( $type, array( 'top type1', 'top type2', 'top type3', 'top type4', 'top type5', 'top type6', 'top type8', 'top type9' ) ) ? $alignment : '';
	$el_class[] = $animation ? 'animation-' . $animation : 'animation-off';

	$description = vc_value_from_safe( $description );
	$description = nl2br( $description );

	if ( $icon_image ) {
		$img = wpb_getImageBySize(
			array(
				'attach_id'  => $icon_image,
				'thumb_size' => 'full',
				'class'      => 'thb_image ' . $retina,
			)
		);
	}
	if ( $icon_image_hover ) {
		$img_hover = wpb_getImageBySize(
			array(
				'attach_id'  => $icon_image_hover,
				'thumb_size' => 'full',
				'class'      => 'thb_image_hover ' . $retina,
			)
		);
	}
	?>
	<<?php echo esc_attr( $el ); ?> id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" href="<?php echo esc_url( $link_to ); ?>" target="<?php echo esc_attr( $a_target ); ?>" data-animation_speed="<?php echo esc_attr( $animation_speed ); ?>">
		<?php if ( $icon_image || $icon ) { ?>
		<figure>
			<?php if ( $icon_image ) { ?>
				<div class="iconbox-image">
					<?php echo $img['thumbnail']; ?>
					<?php
					if ( $icon_image_hover ) {
						echo $img_hover['thumbnail'];  }
					?>
				</div>
				<?php
			} else {
				get_template_part( 'assets/svg/' . $icon );
			}
			?>
			<?php if ( 'top type2' === $type ) { ?>
				<?php
				if ( $heading ) {
					?>
					<h5><?php echo wp_kses_post( $heading ); ?></h5><?php } ?>
			<?php } ?>
			<?php if ( in_array( $type, array( 'top type1', 'top type2' ), true ) ) { ?>
				<span class="thb-iconbox-line"></span>
			<?php } ?>
		</figure>
		<?php } ?>
		<div class="iconbox-content">
			<?php
			if ( $heading && 'top type2' !== $type ) {
				?>
				<h5><?php echo wp_kses_post( $heading ); ?></h5><?php } ?>
			<?php echo wp_kses_post( wpautop( $description ) ); ?>
			<?php if ( $link_to ) { ?>
				<span class="thb-read-more btn-text <?php echo esc_attr( $style ); ?>">
					<?php if ( 'style3' === $style ) { ?>
					<strong class="circle-btn"></strong><?php } ?><span><?php echo esc_attr( $a_title ); ?></span>
					<?php if ( 'style4' === $style ) { ?>
						<div class="arrow"><div><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?></div></div>
					<?php } ?>
					<?php if ( 'style5' === $style ) { ?>
						<div class="arrow"><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?></div>
					<?php } ?>
				</span>
			<?php } ?>
		</div>
		<style>
			<?php if ( $thb_border_color && $type == 'top type5' ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox {
				border-color: <?php echo esc_attr( $thb_border_color ); ?>;
			}
			<?php } ?>
			<?php if ( $heading_font_size ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox h5 {
				font-size: <?php echo esc_attr( $heading_font_size ); ?>;
			}
			<?php } ?>
			<?php if ( $description_font_size ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox p {
				font-size: <?php echo esc_attr( $description_font_size ); ?>;
			}
			<?php } ?>
			<?php if ( $thb_icon_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox figure svg path,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox figure svg circle,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox figure svg rect,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox figure svg ellipse {
				stroke: <?php echo esc_attr( $thb_icon_color ); ?>;
			}
			<?php } ?>
			<?php if ( $icon_image_width && $icon_image ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .iconbox-image img {
					width: <?php echo esc_attr( $icon_image_width ); ?>px;
					height: auto;
				}
			<?php } ?>
			<?php if ( $thb_heading_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .iconbox-content h5,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox figure h5 {
				color: <?php echo esc_attr( $thb_heading_color ); ?>;
			}
			<?php } ?>
			<?php if ( $thb_text_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .iconbox-content p,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .iconbox-content span {
				color: <?php echo esc_attr( $thb_text_color ); ?>;
			}

			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .iconbox-content svg,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .iconbox-content svg .bar {
				fill: <?php echo esc_attr( $thb_text_color ); ?>;
			}
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .btn-text.style2:before,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox .btn-text.style2:after {
				background: <?php echo esc_attr( $thb_text_color ); ?>;
			}
			<?php } ?>
			/* Hover */
			<?php if ( $thb_icon_color_hover ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover figure svg path,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover figure svg circle,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover figure svg rect,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover figure svg ellipse {
				stroke: <?php echo esc_attr( $thb_icon_color_hover ); ?>;
			}
			<?php } ?>
			<?php if ( $thb_heading_color_hover ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .iconbox-content h5,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox figure h5 {
				color: <?php echo esc_attr( $thb_heading_color_hover ); ?>;
			}
			<?php } ?>
			<?php if ( $thb_text_color_hover ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .iconbox-content p,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .iconbox-content span {
				color: <?php echo esc_attr( $thb_text_color_hover ); ?>;
			}
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .iconbox-content svg,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .iconbox-content svg .bar {
				fill: <?php echo esc_attr( $thb_text_color_hover ); ?>;
			}
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .btn-text.style2:before,
			#<?php echo esc_attr( $element_id ); ?>.thb-iconbox:hover .btn-text.style2:after {
				background: <?php echo esc_attr( $thb_text_color_hover ); ?>;
			}
			<?php } ?>
		</style>
	</<?php echo esc_attr( $el ); ?>>
	<?php

	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_iconbox', 'thb_iconbox' );
