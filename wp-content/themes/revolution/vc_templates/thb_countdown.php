<?php function thb_countdown( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_countdown', $atts );
	extract( $atts );

	$el_class[] = 'thb-countdown';
	$element_id = uniqid('thb-countdown-');
	$out ='';
	ob_start();


	?>
	<div id="<?php echo esc_attr($element_id); ?>" class="thb-countdown" data-offset="<?php echo esc_attr($offset); ?>" data-date="<?php echo esc_attr($date); ?>">
    <ul class="thb-countdown-ul">
      <li>
        <span class="days timestamp">00</span>
        <span class="timelabel"><?php esc_html_e( 'days', 'revolution' ); ?></span>
      </li>
      <li>
        <span class="hours timestamp">00</span>
        <span class="timelabel"><?php esc_html_e( 'hours', 'revolution' ); ?></span>
      </li>
      <li>
        <span class="minutes timestamp">00</span>
        <span class="timelabel"><?php esc_html_e( 'minutes', 'revolution' ); ?></span>
      </li>
      <li>
        <span class="seconds timestamp">00</span>
        <span class="timelabel"><?php esc_html_e( 'seconds', 'revolution' ); ?></span>
      </li>
    </ul>
	</div>
	<?php if ($countdown_color_number || $countdown_color_text) { ?>
	<style>
		<?php if ($countdown_color_number) { ?>
			#<?php echo esc_attr($element_id); ?> span.timestamp {
				color: <?php echo esc_attr($countdown_color_number); ?>;
			}
		<?php } ?>
			<?php if ($countdown_color_text) { ?>
			#<?php echo esc_attr($element_id); ?> span.timelabel {
				color: <?php echo esc_attr($countdown_color_text); ?>;
			}
		<?php } ?>
	</style>
	<?php } ?>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_countdown', 'thb_countdown');
