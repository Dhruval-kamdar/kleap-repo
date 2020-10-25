<?php
	$thb_id              = get_the_ID();
	$post_gallery_photos = get_post_meta( $thb_id, 'post-gallery-photos', true );
	$article_style       = ot_get_option( 'article_style', 'style1' );
if ( $post_gallery_photos ) {
	$post_gallery_photos_arr = explode( ',', $post_gallery_photos );
	$count                   = sizeof( $post_gallery_photos_arr );
}

?>
<?php if ( 'style1' === $article_style ) { ?>
<div class="header-spacer-ignore"></div>
<?php } ?>
<div class="thb-carousel post-gallery post-gallery-detail center-arrows" data-pagination="true" data-navigation="true" data-columns="1">
<?php
if ( $post_gallery_photos ) {
	foreach ( $post_gallery_photos_arr as $image_id ) {
		$image_caption = get_post( $image_id )->post_excerpt;
		?>
	<figure class="thb-overlay-caption">
		<?php echo wp_get_attachment_image( $image_id, 'revolution-wide-3x' ); ?>
		<?php if ( $image_caption ) { ?>
			<figcaption><?php echo esc_attr( $image_caption ); ?></figcaption>
		<?php } ?>
	</figure>
		<?php
	}
}
?>
</div>
