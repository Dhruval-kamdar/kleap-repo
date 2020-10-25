<?php function thb_counter( $atts, $content = null ) {
	$thousand_separator        = true;
	$thousand_separator_format = '(,ddd).dd';
	$atts                      = vc_map_get_attributes( 'thb_counter', $atts );
	extract( $atts );
	$speed       = '' === $speed ? 2000 : $speed;
	$out         = '';
	$element_id  = uniqid( 'thb-counter-' );
	$description = vc_value_from_safe( $description );

	$el_class[] = 'thb-counter';
	$el_class[] = $style;
	$el_class[] = $alignment;

	$image = '';
	if ( $icon_image ) {
		$img = wpb_getImageBySize(
			array(
				'attach_id'  => $icon_image,
				'thumb_size' => 'full',
				'class'      => 'thb_image',
			)
		);
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" id="<?php echo esc_attr( $element_id ); ?>" data-separator="<?php echo esc_attr( $thousand_separator ); ?>" data-format="<?php echo esc_attr( $thousand_separator_format ); ?>">
		<?php if ( 'counter-style3' === $style ) { ?>
			<?php if ( $icon || $icon_image ) { ?>
				<figure>
					<?php if ( $icon_image ) { ?>
						<div class="counter-image">
							<?php echo $img['thumbnail']; ?>
						</div>
					<?php } else { ?>
						<?php get_template_part( 'assets/svg/' . $icon ); ?>
					<?php } ?>
				</figure>
			<?php } ?>
			<div class="counter-container">
				<h6><?php echo esc_attr( $heading ); ?></h6>
				<?php if ( $description ) { ?>
				<div class="thb-description"><p><?php echo wp_kses_post( $description ); ?></p></div>
				<?php } ?>
				<div class="h1" data-count="<?php echo esc_attr( $counter ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">0</div>
			</div>
		<?php } elseif ( $style === 'counter-style4' ) { ?>
			<div class="counter-container">
				<?php
				if ( $prepend_counter_text ) {
					?>
					<div class="counter-text counter counter-text-prepend"><?php echo esc_html( $prepend_counter_text ); ?></div><?php } ?>
				<div class="counter" data-count="<?php echo esc_attr( $counter ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">0</div>
				<?php
				if ( $counter_text ) {
					?>
					<div class="counter-text counter"><?php echo esc_html( $counter_text ); ?></div><?php } ?>
			</div>
			<p class="thb-title"><?php echo esc_attr( $heading ); ?></p>
			<?php if ( $description ) { ?>
			<div class="thb-description"><p><?php echo wp_kses_post( $description ); ?></p></div>
			<?php } ?>
			<?php if ( $icon || $icon_image ) { ?>
				<figure>
					<?php if ( $icon_image ) { ?>
						<div class="counter-image">
							<?php echo $img['thumbnail']; ?>
						</div>
					<?php } else { ?>
						<?php get_template_part( 'assets/svg/' . $icon ); ?>
					<?php } ?>
				</figure>
			<?php } ?>
		<?php } elseif ( $style === 'counter-style5' ) { ?>
			<div class="counter-container">
				<?php
				if ( $prepend_counter_text ) {
					?>
					<div class="counter-text counter"><?php echo esc_html( $prepend_counter_text ); ?></div><?php } ?>
				<div class="h1 counter" data-count="<?php echo esc_attr( $counter ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">0</div>
				<?php
				if ( $counter_text ) {
					?>
					<div class="counter-text counter"><?php echo esc_html( $counter_text ); ?></div><?php } ?>
			</div>
			<h6 class="thb-title"><?php echo esc_attr( $heading ); ?></h6>
			<?php if ( $description ) { ?>
			<div class="thb-description"><p><?php echo wp_kses_post( $description ); ?></p></div>
			<?php } ?>
			<?php if ( $icon || $icon_image ) { ?>
				<figure>
					<?php if ( $icon_image ) { ?>
						<div class="counter-image">
							<?php echo $img['thumbnail']; ?>
						</div>
					<?php } else { ?>
						<?php get_template_part( 'assets/svg/' . $icon ); ?>
					<?php } ?>
				</figure>
			<?php } ?>
		<?php } elseif ( $style === 'counter-style1' ) { ?>
			<div class="counter-container">
				<?php
				if ( $prepend_counter_text ) {
					?>
					<div class="h1 counter-text counter counter-text-prepend"><?php echo esc_html( $prepend_counter_text ); ?></div><?php } ?>
				<div class="h1" data-count="<?php echo esc_attr( $counter ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">0</div>
				<?php
				if ( $counter_text ) {
					?>
					<div class="h1 counter-text counter"><?php echo esc_html( $counter_text ); ?></div><?php } ?>
			</div>
			<h6><?php echo esc_attr( $heading ); ?></h6>
			<?php if ( $description ) { ?>
			<div class="thb-description"><p><?php echo wp_kses_post( $description ); ?></p></div>
			<?php } ?>
			<?php if ( $icon || $icon_image ) { ?>
				<figure>
					<?php if ( $icon_image ) { ?>
						<div class="counter-image">
							<?php echo $img['thumbnail']; ?>
						</div>
					<?php } else { ?>
						<?php get_template_part( 'assets/svg/' . $icon ); ?>
					<?php } ?>
				</figure>
			<?php } ?>
		<?php } else { ?>
			<div class="counter-container">
				<div class="h1" data-count="<?php echo esc_attr( $counter ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">0</div>
				<h6><?php echo esc_attr( $heading ); ?></h6>
			</div>
			<?php if ( $description ) { ?>
			<div class="thb-description"><p><?php echo wp_kses_post( $description ); ?></p></div>
			<?php } ?>
			<?php if ( $icon || $icon_image ) { ?>
				<figure>
					<?php if ( $icon_image ) { ?>
						<div class="counter-image">
							<?php echo $img['thumbnail']; ?>
						</div>
					<?php } else { ?>
						<?php get_template_part( 'assets/svg/' . $icon ); ?>
					<?php } ?>
				</figure>
			<?php } ?>
		<?php } ?>
		<style>
			#<?php echo esc_attr( $element_id ); ?> .odometer.odometer-auto-theme.odometer-animating-up .odometer-ribbon-inner,
			#<?php echo esc_attr( $element_id ); ?> .odometer.odometer-theme-minimal.odometer-animating-up .odometer-ribbon-inner {
				transition: transform <?php echo esc_attr( $speed / 1000 ); ?>s;
			}
			<?php if ( $thb_counter_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-counter .h1,
				#<?php echo esc_attr( $element_id ); ?>.thb-counter .counter {
					color: <?php echo esc_attr( $thb_counter_color ); ?>;
				}
			<?php } ?>
			<?php if ( $thb_heading_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-counter h6,
				#<?php echo esc_attr( $element_id ); ?>.thb-counter .thb-title {
					color: <?php echo esc_attr( $thb_heading_color ); ?>;
				}
			<?php } ?>
			<?php if ( $thb_icon_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-counter figure svg path,
				#<?php echo esc_attr( $element_id ); ?>.thb-counter figure svg circle,
				#<?php echo esc_attr( $element_id ); ?>.thb-counter figure svg rect,
				#<?php echo esc_attr( $element_id ); ?>.thb-counter figure svg ellipse {
					stroke: <?php echo esc_attr( $thb_icon_color ); ?>;
				}
			<?php } ?>
			<?php if ( $icon_image_width && $icon_image ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-counter .counter-image img {
					width: <?php echo esc_attr( $icon_image_width ); ?>px;
					height: auto;
				}
			<?php } ?>
		</style>
	</div>
	<?php

	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_counter', 'thb_counter' );
