<?php
	$thb_id       = get_queried_object_id();
	$header_color = thb_get_header_color( $thb_id );

	$header_class[] = 'header style7';
	$header_class[] = $header_color;
?>
<!-- Start Header -->
<header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
	<div class="row align-middle">
		<div class="small-5 medium-4 medium-offset-4 columns">
			<?php do_action( 'thb_logo', true ); ?>
		</div>
		<div class="small-7 medium-4 columns">
			<?php do_action( 'thb_secondary_area' ); ?>
		</div>
	</div>
</header>
<!-- End Header -->
