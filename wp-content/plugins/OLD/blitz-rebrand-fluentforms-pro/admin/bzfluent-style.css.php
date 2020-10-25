<?php

 $adminUrl = admin_url();

 if ( isset( $rebranding['primary_color'] ) && ! empty( $rebranding['primary_color'] ) ) { ?>
	 
	.ff_form_wrap button.el-button--primary, .ff_form_wrap a.el-button--primary, .ff_settings_body .el-checkbox__input.is-checked .el-checkbox__inner, .ff_settings_body .el-checkbox__input.is-indeterminate .el-checkbox__inner, .el-form.el-form--label-left .el-checkbox__input.is-checked .el-checkbox__inner, .el-form.el-form--label-left .el-checkbox__input.is-indeterminate .el-checkbox__inner { 
		background-color: <?php echo $rebranding['primary_color'];?>;
		border-color: <?php echo $rebranding['primary_color'];?>;
	}

	.form_internal_menu ul.ff_setting_menu li.active a {
		border-bottom: 2px solid <?php echo $rebranding['primary_color'];?>;
		color: <?php echo $rebranding['primary_color'];?>;
	}
	
	.form-editor--body .ff-btn.ff-btn-md.default {
		background-color: <?php echo $rebranding['primary_color'];?>;
	}
	
	.el-loading-mask .el-loading-spinner .el-loading-text, .el-form .el-text-info , .setting_header .el-text-info {
		color: <?php echo $rebranding['primary_color'];?>;
	}
	.el-form .el-radio__input.is-checked .el-radio__inner {
		background: <?php echo $rebranding['primary_color'];?>;
		border-color: <?php echo $rebranding['primary_color'];?>;
	}
	.el-form .el-radio.is-bordered.is-checked {
		border-color: <?php echo $rebranding['primary_color'];?>;
	}
	.el-form .el-radio__input.is-checked+.el-radio__label {
		color: <?php echo $rebranding['primary_color'];?>;
	}
	.ff_settings_body .el-checkbox__input.is-checked+.el-checkbox__label, .el-form.el-form--label-left .el-checkbox__input.is-checked+.el-checkbox__label, #ff_form_entries_app  .el-checkbox__input.is-checked+.el-checkbox__label {
		color: <?php echo $rebranding['primary_color'];?>;	
	}
	#ff_form_entries_app .el-checkbox__input.is-checked .el-checkbox__inner, #ff_form_entries_app .el-checkbox__input.is-indeterminate .el-checkbox__inner {
		background-color: <?php echo $rebranding['primary_color'];?>;
		border-color: <?php echo $rebranding['primary_color'];?>;
	}
	.ff_form_group .ff-el-banner:hover {
		background: <?php echo $rebranding['primary_color'];?> !important;
	}
	.ff_form_group .item_has_image:hover .ff-el-banner-text-inside-hoverable {
		background: <?php echo $rebranding['primary_color'];?> !important;
	}
	.el-loading-mask .el-loading-spinner .path{
		stroke : <?php echo $rebranding['primary_color'];?>;
	}
	
<?php } ?>

<?php if ( isset( $rebranding['fluent_hide_pro_menu'] ) && 'on' == $rebranding['fluent_hide_pro_menu'] ) { ?>

	.ff_form_main_nav a.ninja-tab.buy_pro_tab {
		display: none;
	}

<?php } ?>

<?php if ( isset( $rebranding['fluent_hide_help_menu'] ) && 'on' == $rebranding['fluent_hide_help_menu'] ) { ?>

	#adminmenu .toplevel_page_fluent_forms li a[href="admin.php?page=fluent_forms_docs"], .ff_form_main_nav a[href="<?php echo $adminUrl; ?>admin.php?page=fluent_forms_docs"] {
		display: none;
	} 
	
<?php } ?>

<?php if ( isset( $rebranding['fluent_hide_modules_menu'] ) && 'on' == $rebranding['fluent_hide_modules_menu'] ) { ?>

	#adminmenu .toplevel_page_fluent_forms li a[href="admin.php?page=fluent_form_add_ons"], .ff_form_main_nav a[href="<?php echo $adminUrl; ?>admin.php?page=fluent_form_add_ons"] {
		display: none;
	} 
	
<?php } ?>

<?php if ( isset( $rebranding['fluent_hide_sign_tab'] ) && 'on' == $rebranding['fluent_hide_sign_tab'] ) { ?>
	
	.ff_add_on_navigation li.ff_add_on_item.ff_add_on_item_fluentform-signature  {
		display: none;
	} 
	
<?php } ?>

<?php if ( isset( $rebranding['fluent_hide_license_tab'] ) && 'on' == $rebranding['fluent_hide_license_tab'] ) { ?>
	
	.ff_add_on_navigation li.ff_add_on_item.ff_add_on_item_fluentform-pro-add-on  {
		display: none;
	} 
	
<?php } ?>


<?php if ( isset( $rebranding['fluent_hide_tools_menu'] ) && 'on' == $rebranding['fluent_hide_tools_menu'] ) { ?>

	#adminmenu .toplevel_page_fluent_forms li a[href="admin.php?page=fluent_forms_transfer"] {
		display: none;
	} 
	
<?php } ?>


