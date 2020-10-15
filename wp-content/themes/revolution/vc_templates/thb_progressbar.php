<?php function thb_progressbar( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_progressbar', $atts );
	extract( $atts );
	$element_id = 'thb-progressbar-' . wp_rand( 10, 999 );
	$out        = '';
	ob_start();

	?>
	<div class="thb-progressbar" id="<?php echo esc_attr( $element_id ); ?>">
		<div class="thb-progress-title">
			<span><?php echo esc_html( $title ); ?></span>
			<span><?php echo esc_html( $progress ); ?>%</span>
		</div>
	<span class="thb-progress" data-progress="<?php echo esc_attr( $progress ); ?>"><span></span></span>
	<?php if ( $thb_bar_color ) { ?>
	<style>
		#<?php echo esc_attr( $element_id ); ?> .thb-progress span {
			background: <?php echo esc_attr( $thb_bar_color ); ?>;
		}
			<?php if ( $thb_bar_color && $thb_bar_color_2 ) { ?>
				#<?php echo esc_attr( $element_id ); ?> .thb-progress span {
				<?php echo thb_css_gradient( $thb_bar_color, $thb_bar_color_2, '-90', true ); ?>;
			}

			<?php } ?>
	</style>
	<?php } ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_progressbar', 'thb_progressbar' );

