<?php function thb_video_lightbox( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_video_lightbox', $atts );
	extract( $atts );

	$element_id = uniqid( 'thb-video-lightbox-' );
	$el_class[] = 'thb-video-lightbox';
	$el_class[] = $style;
	$el_class[] = $hover_style;
	$el_class[] = 'thb-video-lightbox-icon-style-' . $icon_style;
	$el_class[] = $box_shadow;
	$el_class[] = $icon_size;
	$el_class[] = $animation;
	$out        = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<a href="<?php echo esc_url( $video ); ?>" class="thb-video-link mfp-video"></a>
		<?php
		if ( 'lightbox-style2' === $style ) {
			$img = wpb_getImageBySize(
				array(
					'attach_id'  => $image,
					'thumb_size' => 'full',
				)
			);
			if ( null === $img ) {
				$img['thumbnail'] = '<img src="http://placekitten.com/g/400/300" />';
			}
			echo $img['thumbnail'];
		}
		?>
		<?php
		if ( 'style1' === $icon_style ) {
			get_template_part( 'assets/img/svg/play.svg' );
		} elseif ( in_array( $icon_style, array( 'style2', 'style4' ), true ) ) {
			get_template_part( 'assets/img/svg/play_pause.svg' );
		} else {
			get_template_part( 'assets/img/svg/play_2.svg' );
		}
		?>
		<?php if ( 'lightbox-style3' === $style ) { ?>
		<span class="thb-video-text"><?php echo wp_kses_post( $video_text ); ?></span>
		<?php } ?>
		<style>
			<?php if ( $icon_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?> svg {
					fill: <?php echo esc_attr( $icon_color ); ?>;
				}
				#<?php echo esc_attr( $element_id ); ?> svg.thb-play-02 .circle1 {
					stroke: <?php echo esc_attr( $icon_color ); ?>;
				}
				#<?php echo esc_attr( $element_id ); ?>.thb-video-lightbox-icon-style-style4 .thb-video-text {
					color: <?php echo esc_attr( $icon_color ); ?>;
				}
		}
		<?php } ?>
			<?php if ( $border_radius ) { ?>
				#<?php echo esc_attr( $element_id ); ?>,
				#<?php echo esc_attr( $element_id ); ?> img {
					border-radius: <?php echo esc_attr( $border_radius ); ?>;
				}
			<?php } ?>
		</style>
	</div>
	<?php

	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_video_lightbox', 'thb_video_lightbox' );
