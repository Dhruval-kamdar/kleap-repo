<?php

//var_dump($_POST);
//die;
$obj_membership_payment=new MJ_Gmgt_membership_payment;
$p 	= new Gmgt_paypal_class(); // paypal class
	//$p->admin_mail 	= GMS_EMAIL_ADD; // set notification email
//$action 		= $_REQUEST["fees_pay_id"];
if($_REQUEST["view_type"] == 'sale_payment') {
	
	$user_id  = $_REQUEST["member_id"];
	$custom_var=$_REQUEST["mp_id"];
}
elseif($_REQUEST["view_type"] == 'income')
{
	
	$user_id  = $_REQUEST["member_id"];
	$custom_var=$_REQUEST["mp_id"];
}
else
{

	if(isset($_REQUEST["mp_id"]))
	{
		$feepaydata = $obj_membership_payment->MJ_gmgt_get_single_membership_payment($_REQUEST["mp_id"]);
		$user_id  = $feepaydata->member_id;
		$membership_id=$feepaydata->membership_id;
		$custom_var=$_REQUEST["mp_id"];
	}
}


/*if(isset($_REQUEST["buy_membership"])){
	$user_id  = $_REQUEST["member_id"];
	$membership_id=$_REQUEST["membership_id"];
	$custom_var=$membership_id;
}*/

$user_info = get_userdata($user_id);
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$p->add_field('business', get_option('gmgt_paypal_email')); // Call the facilitator eaccount

$p->add_field('cmd', '_cart'); // cmd should be _cart for cart checkout
$p->add_field('upload', '1');

if($_REQUEST["view_type"] == 'sale_payment')
{
$p->add_field('return', home_url().'/?dashboard=user&page=store&half=yes'); // return URL after the transaction got over
$p->add_field('cancel_return', home_url().'/?dashboard=user&page=store&action=cancel'); // cancel URL if the trasaction was cancelled during half of the transaction
$p->add_field('notify_url', home_url().'/?dashboard=user&page=store&action=ipn'); // Notify URL which received IPN (Instant Payment Notification)
}
elseif($_REQUEST["view_type"] == 'income')
{
$p->add_field('return', home_url().'/?dashboard=user&page=payment&half=yes'); // return URL after the transaction got over
$p->add_field('cancel_return', home_url().'/?dashboard=user&page=payment&action=cancel'); // cancel URL if the trasaction was cancelled during half of the transaction
$p->add_field('notify_url', home_url().'/?dashboard=user&page=payment&action=ipn'); // Notify URL which received IPN (Instant Payment Notification)
}
elseif($_REQUEST["view_type"] == 'subscription_membership_payment')
{
$p->add_field('return', home_url().'/?dashboard=user&page=subscription_history&half=yes'); // return URL after the transaction got over
$p->add_field('cancel_return', home_url().'/?dashboard=user&page=subscription_history&action=cancel'); // cancel URL if the trasaction was cancelled during half of the transaction
$p->add_field('notify_url', home_url().'/?dashboard=user&page=subscription_history&action=ipn'); // Notify URL which received IPN (Instant Payment Notification)
}
else
{
$p->add_field('return', home_url().'/?dashboard=user&page=membership_payment&half=yes'); // return URL after the transaction got over
$p->add_field('cancel_return', home_url().'/?dashboard=user&page=membership_payment&action=cancel'); // cancel URL if the trasaction was cancelled during half of the transaction
$p->add_field('notify_url', home_url().'/?dashboard=user&page=membership_payment&action=ipn'); // Notify URL which received IPN (Instant Payment Notification)
}

$p->add_field('currency_code', get_option( 'gmgt_currency_code' ));
$p->add_field('invoice', date("His").rand(1234, 9632));
if($_REQUEST["view_type"] == 'sale_payment')
{
	$p->add_field('item_name_1', 'Sell Product');
}
elseif($_REQUEST["view_type"] == 'income')
{
	$p->add_field('item_name_1', 'income Payment');
}
else
{
 $p->add_field('item_name_1', MJ_gmgt_get_membership_name($membership_id));
}

$p->add_field('item_number_1', 4);
$p->add_field('quantity_1', 1);
//$p->add_field('amount_1', MJ_gmgt_get_membership_price(get_user_meta($user_id,'membership_id',true)));
$p->add_field('amount_1', $_POST['amount']);
//$p->add_field('amount_1', 1);//Test purpose
$p->add_field('first_name',$user_info->first_name);
$p->add_field('last_name', $user_info->last_name);
$p->add_field('address1',$user_info->address);
$p->add_field('city', $user_info->city_name);
$p->add_field('custom', $user_id."_".$custom_var);
$p->add_field('rm',2);
	
		
$p->add_field('state', get_user_meta($user_id,'state_name',true));
$p->add_field('country', get_option( 'gmgt_contry' ));
$p->add_field('zip', get_user_meta($user_id,'zip_code',true));
$p->add_field('email',$user_info->user_email);

$p->submit_paypal_post(); // POST it to paypal
//$p->dump_fields(); // Show the posted values for a reference, comment this line before app goes live
//echo "hello";
//exit;
?>