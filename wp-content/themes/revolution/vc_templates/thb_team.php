<?php function thb_team( $atts, $content = null ) {
	global $thb_columns, $thb_member_style, $thb_team_animation;
	$atts = vc_map_get_attributes( 'thb_team', $atts );
	extract( $atts );

	if ( ! $image && in_array( $thb_member_style, array( 'member_style2', 'member_style3', 'member_style5' ) ) ) {
		return;
	}
	if ( $image ) {
		$image = wpb_getImageBySize(
			array(
				'attach_id'  => $image,
				'thumb_size' => 'full',
			)
		);
	}
	$description = vc_value_from_safe( $description );
	$description = nl2br( $description );

	$link = ( $link == '||' ) ? '' : $link;
	$link = vc_build_link( $link );

	$url_to     = $link['url'];
	$url_title  = $link['title'];
	$url_target = $link['target'] ? $link['target'] : '_self';

	$el_class[] = 'thb-team-member';
	$el_class[] = 'small-12';
	$el_class[] = $thb_columns;
	$el_class[] = $thb_team_animation;
	$el_class[] = 'columns';
	$el_class[] = $thb_member_style;
	$el_class[] = $url_to ? 'team-has-link' : false;
	$out        = '';
	ob_start();

	?>
	<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<div class="team-container 
		<?php
		if ( $thb_member_style == 'member_style2' ) {
			?>
			thb_3dimg<?php } ?>">
			<?php
			if ( $image ) {
				echo $image['thumbnail']; }
			?>
			<?php if ( in_array( $thb_member_style, array( 'member_style2' ) ) && $image ) { ?>
				<div class="atvImg-layer"><?php echo $image['thumbnail']; ?></div>
			<?php } ?>
			<div class="team-information 
			<?php
			if ( $thb_member_style == 'member_style2' ) {
				?>
				atvImg-layer<?php } ?>">
				<?php if ( in_array( $thb_member_style, array( 'member_style2', 'member_style3', 'member_style5' ) ) ) { ?>
					<div class="team-title">
						<?php if ( in_array( $thb_member_style, array( 'member_style5' ) ) ) { ?>
						<p class="member-title"><?php echo esc_html( $name ); ?></p>
						<p class="job-title"><?php echo esc_html( $sub_title ); ?></p>
						<?php } else { ?>
						<h6 class="member-title"><?php echo esc_html( $name ); ?></h6>
						<p class="job-title"><?php echo esc_html( $sub_title ); ?></p>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( in_array( $thb_member_style, array( 'member_style2', 'member_style3', 'member_style5' ) ) ) { ?>
				<div class="info-container">
				<?php } ?>
				<?php if ( $description ) { ?>
				<div class="thb-description"><?php echo wp_kses_post( $description ); ?></div>
				<?php } ?>
				<?php if ( $thb_member_style !== 'member_style4' ) { ?>
					<?php if ( $facebook || $instagram || $twitter || $linkedin ) { ?>
					  <div class="thb-icons">
						<?php if ( $facebook ) { ?>
							  <a href="<?php echo esc_url( $facebook ); ?>" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a>
						<?php } ?>
						<?php if ( $twitter ) { ?>
							  <a href="<?php echo esc_url( $twitter ); ?>" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a>
						<?php } ?>
						<?php if ( $linkedin ) { ?>
							  <a href="<?php echo esc_url( $linkedin ); ?>" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i></a>
						<?php } ?>
						<?php if ( $instagram ) { ?>
							  <a href="<?php echo esc_url( $instagram ); ?>" class="instagram" target="_blank"><i class="fa fa-instagram"></i></a>
						<?php } ?>
					  </div>
				<?php } ?>
				<?php } ?>
				<?php if ( in_array( $thb_member_style, array( 'member_style2', 'member_style3', 'member_style5' ) ) ) { ?>
				</div>
				<?php } ?>
				<?php if ( $url_to ) { ?>
					<a href="<?php echo esc_url( $url_to ); ?>" target="<?php echo esc_attr( $url_target ); ?>" title="<?php echo esc_attr( $url_title ); ?>" class="team-link 
										<?php
										if ( $thb_member_style == 'member_style2' ) {
											?>
						atvImg-layer<?php } ?> <?php echo esc_attr( $extra_link_class ); ?>"></a>
				<?php } ?>
			</div><?php // .team-information ?>

			<?php if ( in_array( $thb_member_style, array( 'member_style5' ) ) ) { ?>
			<div class="team-information-hover"></div>
			<?php } ?>
		</div>
		<?php if ( in_array( $thb_member_style, array( 'member_style1', 'member_style4' ) ) ) { ?>
			<h6 class="member-title"><?php echo esc_html( $name ); ?></h6>
			<p class="job-title"><?php echo esc_html( $sub_title ); ?></p>
		<?php } ?>
		<?php if ( $thb_member_style == 'member_style4' ) { ?>
			<?php if ( $facebook || $instagram || $twitter || $linkedin ) { ?>
				<div class="thb-icons">
					<?php if ( $facebook ) { ?>
						<a href="<?php echo esc_url( $facebook ); ?>" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a>
					<?php } ?>
					<?php if ( $twitter ) { ?>
						<a href="<?php echo esc_url( $twitter ); ?>" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a>
					<?php } ?>
					<?php if ( $linkedin ) { ?>
						<a href="<?php echo esc_url( $linkedin ); ?>" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i></a>
					<?php } ?>
					<?php if ( $instagram ) { ?>
						<a href="<?php echo esc_url( $instagram ); ?>" class="instagram" target="_blank"><i class="fa fa-instagram"></i></a>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_team', 'thb_team' );
