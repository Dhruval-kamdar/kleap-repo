<?php if ( isset( $rebranding['primary_color'] ) && ! empty( $rebranding['primary_color'] ) ) { ?>
	
	.wcf-template-header .filter-links li .current{
		border-bottom: 2px solid <?php echo $rebranding['primary_color']; ?>;
	}
	#wcf-remote-filters .filter-links li a:focus, #wcf-remote-filters .filter-links li a:hover, #wcf-remote-filters .filter-links li .current{
		color: <?php echo $rebranding['primary_color']; ?>;
	}
	.no-elementor-notice span, .template .notice a, .wcf-embed-checkout-form .woocommerce .woocommerce-billing-fields [type="checkbox"]:checked:before,
	.wcf-embed-checkout-form .woocommerce #payment input[type=checkbox]:checked:before, .wcf-embed-checkout-form .woocommerce .woocommerce-shipping-fields [type="checkbox"]:checked:before, .wcf-embed-checkout-form .woocommerce .woocommerce-account-fields input[type=checkbox]:checked:before, .wcf-embed-checkout-form .woocommerce a, .wcf-embed-checkout-form .woocommerce-info::before, .wcf-embed-checkout-form .woocommerce-message::before, .et_pb_module #wcf-embed-checkout-form .woocommerce #payment input[type=checkbox]:checked:before, .et_pb_module #wcf-embed-checkout-form .woocommerce .woocommerce-shipping-fields [type="checkbox"]:checked:before, .et_pb_module #wcf-embed-checkout-form .woocommerce a,  {
		color: <?php echo $rebranding['primary_color']; ?>;
	}
	.wcf-tab.nav-tabs > li.active {
		border-bottom: 3px solid <?php echo $rebranding['primary_color']; ?>;
	}
	.wcf-embed-checkout-form .woocommerce .woocommerce-billing-fields [type="checkbox"]:focus, .wcf-embed-checkout-form .woocommerce #payment input[type=checkbox]:focus, .wcf-embed-checkout-form .woocommerce .woocommerce-shipping-fields [type="checkbox"]:focus, .wcf-embed-checkout-form .woocommerce #payment input[type=radio]:focus, .wcf-embed-checkout-form .woocommerce .woocommerce-account-fields input[type=checkbox]:focus, .wcf-embed-checkout-form .woocommerce #order_review button, .wcf-embed-checkout-form .woocommerce #order_review button.wcf-btn-small:hover, .wcf-embed-checkout-form .woocommerce #payment #place_order:hover, .et_pb_module #wcf-embed-checkout-form .woocommerce #payment input[type=checkbox]:focus, 
	.et_pb_module #wcf-embed-checkout-form .woocommerce .woocommerce-shipping-fields [type="checkbox"]:focus, .et_pb_module #wcf-embed-checkout-form .woocommerce #payment input[type=radio]:focus {
		border-color: <?php echo $rebranding['primary_color']; ?>;
	}
	.wcf-embed-checkout-form .woocommerce #payment input[type=radio]:checked:before, .wcf-embed-checkout-form .woocommerce form.woocommerce-form-login .form-row button, .wcf-embed-checkout-form form.checkout_coupon .button, .wcf-embed-checkout-form .woocommerce #order_review button, .wcf-embed-checkout-form .woocommerce #order_review button.wcf-btn-small:hover, .wcf-embed-checkout-form .woocommerce #payment #place_order:hover, .et_pb_module #wcf-embed-checkout-form .woocommerce #payment input[type=radio]:checked:before {
		background-color: <?php echo $rebranding['primary_color']; ?>;
	}
	.wcf-preview-mode {
		background: <?php echo $rebranding['primary_color']; ?>;
	}
	
<?php } ?>

<?php if ( isset( $rebranding['cartflows_logo'] ) && ! empty( $rebranding['cartflows_logo'] ) ) { ?>

	img.wcf-logo { 
		display: none;
	}

<?php } ?>

<?php if ( isset( $rebranding['cartflows_logo'] ) && ! empty( $rebranding['cartflows_logo'] ) ) { ?>

	#wcf-sandbox-settings h2 span { 
		display: none;
	}

<?php } ?>

<?php if ( isset( $rebranding['flows_remove_learn_how'] )  && 'on' == $rebranding['flows_remove_learn_how'] )  { ?>

	p.wcf-learn-how { 
		display: none;
	}

<?php } ?>

<?php if ( isset( $rebranding['flows_hide_gs_video'] )  && 'on' == $rebranding['flows_hide_gs_video'] )  { ?>
	
	.wcf-container .postbox.introduction {
		display: none;
	}

<?php } ?>

<?php if ( isset( $rebranding['flows_remove_pro_word'] )  && 'on' == $rebranding['flows_remove_pro_word'] )  { ?>
	
	span.wcf-flow-type.pro {
		display: none;
	}
	#cartflows-license-popup p.description {
		display: none;
	}

	#wcf-remote-flow-list .inner .template-actions a.button.button-primary {
		display: none;
	}

<?php } ?>

<?php if ( isset( $rebranding['flows_hide_sidebar'] )  && 'on' == $rebranding['flows_hide_sidebar'] )  { ?>
	
	.wcf-addon-wrap div#postbox-container-1 {
		display: none;
	}
	
<?php } ?>

<?php 

if ( isset( $rebranding['cartflows_small_logo'] ) && !empty( $rebranding['cartflows_small_logo'])  ) {
$image_attributes = wp_get_attachment_image_src( $rebranding['cartflows_small_logo'] );
$sm_logo = $image_attributes[0];
?>

	span.wcf-cartflows-logo-img {
		display: none;
	}

<?php } ?>


.wcf-button-wrap {
    margin-top: 0;
    position: absolute;
    top: 18px;
    left: calc(100% - 407px) !important;
}
.wrap h1.wp-heading-inline {
    width: calc(100% - 413px);
}
.wcf-template-logo-wrap > img {
    display: inline-block;
    vertical-align: middle;
}
.wcf-template-logo-wrap .wcf-cartflows-title {
    display: inline-block;
    vertical-align: middle;
}

/***media start***/
@media (max-width:850px){
	.wcf-button-wrap {
		left: auto;  !important;
		right: 0;
	}
	.wrap h1.wp-heading-inline {
		width: calc(100% - 114px);
	}	
}
