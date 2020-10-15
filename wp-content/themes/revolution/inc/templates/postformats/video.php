<?php
	$thb_id    = get_the_ID();
	$video_url = get_post_meta( $thb_id, 'post_video', true );
?>
<figure class="post-gallery post-gallery-detail">
	<?php if ( '' !== $video_url && wp_oembed_get( $video_url ) ) { ?>
		<?php echo '<div class="flex-video widescreen">' . wp_oembed_get( $video_url ) . '</div>'; ?>
	<?php } ?>
</figure>
