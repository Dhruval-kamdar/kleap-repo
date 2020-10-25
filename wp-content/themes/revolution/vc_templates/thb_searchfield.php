<?php function thb_searchfield( $atts, $content = null ) {

	$atts = vc_map_get_attributes( 'thb_searchfield', $atts );
	extract( $atts );

	$out        = $text = '';
	$element_id = 'thb-searchfield-' . wp_rand( 10, 999 );

	$class[] = 'thb-searchfield';
	$class[] = $extra_class;
	$class[] = $thb_border_radius;
	$class[] = $size;

	$placeholder = $placeholder ? $placeholder : esc_html__( 'Type here to search', 'revolution' );
	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
		<!-- Start SearchForm -->
		<form method="get" class="searchform" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<fieldset>
				<input name="s" type="text" class="s" placeholder="<?php echo esc_attr( $placeholder ); ?>">
				<button type="submit" class="submit"><?php get_template_part( 'assets/img/svg/search.svg' ); ?></button>
			</fieldset>
		</form>
		<!-- End SearchForm -->

		<style>
			<?php if ( $border_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .s {
				border-color: <?php echo esc_attr( $border_color ); ?>;
			}
			<?php } ?>
			<?php if ( $border_color_active ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .s:focus {
				border-color: <?php echo esc_attr( $border_color_active ); ?>;
			}
			<?php } ?>
			<?php if ( $background_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .s {
				background-color: <?php echo esc_attr( $background_color ); ?>;
			}
			<?php } ?>
			<?php if ( $background_color_active ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .s:focus {
				background-color: <?php echo esc_attr( $background_color_active ); ?>;
			}
			<?php } ?>
			<?php if ( $text_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .s {
				color: <?php echo esc_attr( $text_color ); ?>;
			}
			<?php } ?>
			<?php if ( $placeholder_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .s::placeholder,
			#<?php echo esc_attr( $element_id ); ?> .s::-moz-placeholder,
			#<?php echo esc_attr( $element_id ); ?> .s::-ms-input-placeholder {
				color: <?php echo esc_attr( $placeholder_color ); ?>;
			}
			#<?php echo esc_attr( $element_id ); ?> .s::-webkit-input-placeholder {
				color: <?php echo esc_attr( $placeholder_color ); ?>;
			}
			<?php } ?>
			<?php if ( $icon_color ) { ?>
			#<?php echo esc_attr( $element_id ); ?> .submit svg {
				fill: <?php echo esc_attr( $icon_color ); ?>;
			}
			<?php } ?>
		</style>
	</div>

	<?php
	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_searchfield', 'thb_searchfield' );
