<?php

namespace FSPoster\App\Pages\Base\Views;

defined( 'MODAL' ) or exit;
?>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-users"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo fsp__( 'Select accounts' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-modal-step">
		<div class="fsp-form-group">
			<label><?php echo fsp__( 'Search account' ); ?></label>
			<div class="fsp-form-input-has-icon">
				<i class="fas fa-search"></i>
				<input autocomplete="off" class="fsp-form-input fsp-search-account" placeholder="<?php echo fsp__( 'Search' ); ?>">
			</div>
		</div>
		<div class="fsp-metabox-modal-accounts">
			<?php echo $fsp_params[ 'metabox_accounts' ]; ?>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button" data-modal-close="true"><?php echo fsp__( 'CLOSE' ); ?></button>
</div>