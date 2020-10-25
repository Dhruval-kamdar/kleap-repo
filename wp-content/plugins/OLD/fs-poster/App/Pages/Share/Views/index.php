<?php

namespace FSPoster\App\Pages\Share\Views;

use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo fsp__( 'Direct Share' ); ?>
		</div>
		<div class="fsp-title-button"></div>
	</div>
	<div class="fsp-col-12 fsp-col-lg-6 fsp-share-leftcol">
		<div class="fsp-card">
			<div class="fsp-card-body">
				<div class="fsp-form-group">
					<div id="wpMediaBtn" class="fsp-form-image <?php echo $fsp_params[ 'imageId' ] > 0 ? 'fsp-hide' : ''; ?>">
						<i class="fas fa-camera-retro"></i>
					</div>
					<div id="imageShow" class="fsp-form-image-preview <?php echo $fsp_params[ 'imageId' ] > 0 ? '' : 'fsp-hide'; ?>" data-id="<?php echo $fsp_params[ 'imageId' ]; ?>">
						<img src="<?php echo esc_html( $fsp_params[ 'imageURL' ] ); ?>">
						<i class="fas fa-times" id="closeImg"></i>
					</div>
				</div>
				<div id="fspShareURL" class="fsp-form-group <?php echo $fsp_params[ 'imageId' ] > 0 ? 'fsp-hide' : ''; ?>">
					<label><?php echo fsp__( 'Link' ); ?></label>
					<input autocomplete="off" type="text" class="fsp-form-input link_url" placeholder="<?php echo fsp__( 'Example: https://example.com' ); ?> " value="<?php echo esc_html( $fsp_params[ 'link' ] ); ?>">
				</div>
				<div class="fsp-form-group">
					<label class="fsp-is-jb">
						<?php echo fsp__( 'Custom post message' ); ?>
						<span><?php echo fsp__( 'Characters count:' ); ?> <span id="fspShareCharCount">0</span></span>
					</label>
					<textarea class="fsp-form-input message_box" placeholder="<?php echo fsp__( 'Enter the custom post message' ); ?>" maxlength="2000"><?php echo esc_html( $fsp_params[ 'message' ] ); ?></textarea>
				</div>
			</div>
			<div class="fsp-card-footer">
				<button type="button" class="fsp-button shareNowBtn"><?php echo fsp__( 'SHARE NOW' ); ?></button>
				<button type="button" class="fsp-button fsp-is-info scheduleBtn"><?php echo fsp__( 'SCHEDULE' ); ?></button>
				<button type="button" class="fsp-button fsp-is-gray saveBtn"><?php echo fsp__( 'SAVE THE POST' ); ?></button>
			</div>
		</div>
	</div>
	<div class="fsp-col-12 fsp-col-lg-6 fsp-share-rightcol">
		<?php Pages::controller( 'Base', 'MetaBox', 'post_meta_box', [
			'post_id' => $fsp_params[ 'post_id' ]
		] ); ?>
	</div>
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo fsp__( 'Saved posts' ); ?>
			<span class="fsp-title-count"><?php echo count( $fsp_params[ 'posts' ] ); ?></span>
		</div>
		<div class="fsp-title-button">
			<button id="fspClearSavedPosts" class="fsp-button">
				<i class="far fa-trash-alt"></i> <span><?php echo fsp__( 'CLEAR ALL' ); ?></span>
			</button>
		</div>
	</div>
	<div class="fsp-col-12">
		<?php foreach ( $fsp_params[ 'posts' ] as $post ) { ?>
			<div class="fsp-share-post" data-id="<?php echo (int) $post[ 'ID' ]; ?>">
				<div class="fsp-share-post-id">
					<?php echo (int) $post[ 'ID' ]; ?>
				</div>
				<div class="fsp-share-post-title">
					<a href="?page=fs-poster-share&post_id=<?php echo (int) $post[ 'ID' ]; ?>">{<?php echo htmlspecialchars( Helper::cutText( $post[ 'post_content' ] ) ); ?>}</a>
				</div>
				<div class="fsp-share-post-date">
					<?php echo Date::dateTime( $post[ 'post_date' ] ); ?>
				</div>
				<div class="fsp-share-post-controls">
					<i class="fas fa-trash fsp-tooltip fsp-icon-button delete_post_btn" data-title="<?php echo fsp__( 'Delete the post' ); ?>"></i>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<script>
	FSPObject.saveID = <?php echo (int) $fsp_params[ 'post_id' ]; ?>;
</script>