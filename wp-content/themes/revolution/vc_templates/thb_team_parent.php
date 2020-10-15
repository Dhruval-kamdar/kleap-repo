<?php function thb_team_parent( $atts, $content = null ) {
	global $thb_columns, $thb_border_color, $thb_style, $thb_member_style, $thb_team_animation;
	$atts = vc_map_get_attributes( 'thb_team_parent', $atts );
	extract( $atts );

	$thb_team_animation = $animation;
	$element_id         = 'thb-team-' . wp_rand( 10, 999 );
	$el_class[]         = 'thb-team-row';
	$el_class[]         = $thb_text_color;

	$row_class[] = 'row';
	$row_class[] = $thb_style;
	$row_class[] = $thb_margins;
	$out         = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<div class="<?php echo esc_attr( implode( ' ', $row_class ) ); ?>" data-columns="<?php echo esc_attr( $thb_columns ); ?>" data-pagination="true" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>">
			<?php echo wpb_js_remove_wpautop( $content, false ); ?>
		</div>
	</div>
	<style>
		<?php if ( $bg_gradient1 && $bg_gradient2 ) { ?>
		#<?php echo esc_attr( $element_id ); ?> .thb-team-member:not(.member_style5) .team-information {
			<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
		}
		#<?php echo esc_attr( $element_id ); ?> .thb-team-member.member_style5 .team-information-hover {
			<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
		}
		<?php } ?>
		<?php if ( $box_shadow ) { ?>
		#<?php echo esc_attr( $element_id ); ?> .member_style3:hover .team-container {
			box-shadow: 0 10px 40px <?php echo esc_attr( $box_shadow ); ?>;
		}
		<?php } ?>
	</style>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_team_parent', 'thb_team_parent' );
