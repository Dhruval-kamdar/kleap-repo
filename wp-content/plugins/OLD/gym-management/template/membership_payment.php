<?php 
$obj_membership_payment=new MJ_Gmgt_membership_payment;
$obj_membership=new MJ_Gmgtmembership;
$obj_member=new MJ_Gmgtmember;
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'paymentlist';
//access right
$user_access=MJ_gmgt_get_userrole_wise_page_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_gmgt_access_right_page_not_access_message();
		die;
	}
}
//ADD FEES PAYMENT DATA
if(isset($_POST['add_fee_payment']))
{
	//POP up data save in payment history
	if($_POST['payment_method'] == 'Paypal'){				
		require_once GMS_PLUGIN_DIR. '/lib/paypal/paypal_process.php';				
	}
	elseif($_POST['payment_method'] == 'Stripe'){
		require_once PM_PLUGIN_DIR. '/lib/stripe/index.php';			
	}
	elseif($_POST['payment_method'] == 'Skrill'){			
		require_once PM_PLUGIN_DIR. '/lib/skrill/skrill.php';
	}
	elseif($_POST['payment_method'] == 'Instamojo'){			
		require_once PM_PLUGIN_DIR. '/lib/instamojo/instamojo.php';
	}
	elseif($_POST['payment_method'] == 'PayUMony'){
		require_once PM_PLUGIN_DIR. '/lib/OpenPayU/payuform.php';			
	}
	elseif($_REQUEST['payment_method'] == '2CheckOut'){				
		require_once PM_PLUGIN_DIR. '/lib/2checkout/index.php';
	}
	elseif($_POST['payment_method'] == 'iDeal'){		
		require_once PM_PLUGIN_DIR. '/lib/ideal/ideal.php';
	}
	else
	{			
	$result=$obj_membership_payment->MJ_gmgt_add_feespayment_history($_POST);		
		if($result)	{
			wp_redirect ( home_url() . '?dashboard=user&page=membership_payment&message=1');
		}
	}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='success')
{ ?>
	<div id="message" class="updated below-h2 "><p>	<?php _e('Payment successfully','gym_mgt');	?></p></div>
<?php
}	
if(isset($_POST['payer_status']) && $_POST['payer_status'] == 'VERIFIED' && (isset($_POST['payment_status'])) && $_POST['payment_status']=='Completed' && isset($_REQUEST['half']) && $_REQUEST['half']=='yes' )
{
	$trasaction_id  = $_POST["txn_id"];
	$custom_array = explode("_",$_POST['custom']);
	$feedata['mp_id']=$custom_array[1];
	$feedata['amount']=$_POST['mc_gross_1'];
	$feedata['payment_method']='paypal';	
	$feedata['trasaction_id']=$trasaction_id ;
	$feedata['created_by']=$custom_array[0];
	$result = $obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);		
	if($result){
		wp_redirect ( home_url() . '?dashboard=user&page=membership_payment&action=success');
	}
}

if(isset($_REQUEST['action']) && $_REQUEST['action']=="ideal_payments" && $_REQUEST['page']=="membership_payment" && isset($_REQUEST['ideal_pay_id']) && isset($_REQUEST['ideal_amt']))
{			
	$feedata['mp_id']=$_REQUEST['ideal_pay_id'];
	$feedata['amount']=$_REQUEST['ideal_amt'];
	$feedata['payment_method']='iDeal';	
	$feedata['trasaction_id']="";
	$feedata['created_by']=get_current_user_id();
	
	$result = $obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);		
	if($result){ 
		wp_redirect ( home_url() . '?dashboard=user&page=membership_payment&action=success');
	}
	
}

if(isset($_REQUEST['skrill_mp_id']) && (isset($_REQUEST['amount'])))
{
	$feedata['mp_id']=$_REQUEST['skrill_mp_id'];
	$feedata['amount']=$_REQUEST['amount'];
	$feedata['payment_method']='Skrill';	
	$feedata['trasaction_id']="";
	$feedata['created_by']=get_current_user_id();	
	$result = $obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);		
	if($result){ 
		wp_redirect ( home_url() . '?dashboard=user&page=membership_payment&action=success');
	}
}

if(isset($_REQUEST['amount'])   && (isset($_REQUEST['pay_id'])) && isset($_REQUEST['payment_request_id']) )
{
	$feedata['mp_id']=$_REQUEST['pay_id'];
	$feedata['amount']=$_REQUEST['amount'];
	$feedata['payment_method']='Instamojo';	
	$feedata['trasaction_id']=$_REQUEST['payment_request_id'];
	$feedata['created_by']=get_current_user_id();	
	$result = $obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);		
	if($result){ 
		wp_redirect ( home_url() . '?dashboard=user&page=membership_payment&action=success');
	}	
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='cancel')
{ ?>
	<div id="message" class="updated below-h2 "><p>	<?php 	_e('Payment Cancel','gym_mgt');	?></p></div>
<?php
}	
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Membership Payment Invoice added successfully','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Membership Payment Invoice updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Membership Payment Invoice deleted successfully','gym_mgt');
	?></div></p><?php
			
	}
}	
	?>

<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		  <div class="invoice_data"></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<script>
$(document).ready(function() {
    $('#payment_list').DataTable({
        "responsive": true,
		language:<?php echo MJ_gmgt_datatable_multi_language();?>	
    });
} );
</script>
<div class="panel-body panel-white"><!--PANEL BODY DIV START-->
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV TABS MENU START-->
		  <li class="<?php if($active_tab=='paymentlist'){?>active<?php }?>">
			  <a href="?dashboard=user&page=membership_payment&tab=paymentlist"  class="tab <?php echo $active_tab == 'paymentlist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Membership Payment List', 'gym_mgt'); ?></a>
			  </a>
		  </li>
	</ul><!--NAV TABS MENU END-->
	<div class="tab-content"><!--TAB CONTENT DIV START-->
		<div class="panel-body"><!--PANEL BODY DIV START-->
			<table id="payment_list" class="display" cellspacing="0" width="100%"><!--TABLE PAYMENT LIST START-->
				<thead>
					<tr>
						<th><?php  _e( 'Invoice Number', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>Start Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>End Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
						<th style="width: 126px;"><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
			  		</tr>
			    </thead>
				<tfoot>
					<tr>
						<th><?php  _e( 'Invoice Number', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>Start Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>End Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
						<th style="width: 126px;"><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
					</tr>
				</tfoot>
				<tbody>
				 <?php
					if($obj_gym->role == 'member')
					{
						if($user_access['own_data']=='1')
						{
							$user_id=get_current_user_id();	
							$paymentdata=$obj_membership_payment->MJ_gmgt_get_all_membership_payment_byuserid($user_id);
						}
						else
						{
							$paymentdata=$obj_membership_payment->MJ_gmgt_get_all_membership_payment();
						}						
					}
					else
					{						
						$paymentdata=$obj_membership_payment->MJ_gmgt_get_all_membership_payment();
					}
					
				 if(!empty($paymentdata))
				 {
					foreach ($paymentdata as $retrieved_data)
					{
						?>
						<tr>
							<td class="productname">
							<?php
							if(!empty($retrieved_data->invoice_no))
							{
								echo $retrieved_data->invoice_no;
							}
							else
							{
								echo '-';
							}		
							?>						
							</td>
							<td class="productname"><?php echo MJ_gmgt_get_membership_name($retrieved_data->membership_id);?></td>
							<td class="paymentby"><?php $user=get_userdata($retrieved_data->member_id);
								$memberid=get_user_meta($retrieved_data->member_id,'member_id',true);
								$display_label=$user->display_name;
								if($memberid)
									$display_label.=" (".$memberid.")";
								echo $display_label;
								?></td>
							<td class="totalamount"><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->membership_amount;?></td>
							<td class="pay_amount"><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->paid_amount;?></td>
							<td class="totalamount"><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->membership_amount-$retrieved_data->paid_amount;?></td>
							<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->start_date);?></td>
							<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->end_date);?></td>
							<td class="paymentdate" style="padding-left: 5px;padding-right: 5px;">
							<?php 
							echo "<span class='btn btn-success btn-xs'>";
							
							echo __(MJ_gmgt_get_membership_paymentstatus($retrieved_data->mp_id), 'gym_mgt' );
							echo "</span>";
							?>
							</td>
							<?php 
							if(MJ_gmgt_get_membership_paymentstatus($retrieved_data->mp_id) =='Fully Paid')
							{
							?>
							<td class="action">				
										  
							<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->mp_id; ?>"  invoice_type="membership_invoice" >
							<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
							</td>               
						</tr>
						<?php 
						}			
						else
						{
							$due_amount=$retrieved_data->membership_amount-$retrieved_data->paid_amount;
						?>
						   <td class="action">
						   <?php 
							if($obj_gym->role=='member' || $obj_gym->role=='accountant')
							{ ?>
								<a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->mp_id; ?>" due_amount="<?php echo $due_amount; ?>"  view_type="payment" ><?php _e('Pay','gym_mgt');?></a>
							<?php
							}
							?>							                
							<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->mp_id; ?>"  invoice_type="membership_invoice" >
						<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
							</td>
						<?php 
						}			
					}
				 }
				 ?>
				</tbody>
			</table><!--TABLE PAYMENT LIST END-->
        </div><!--PANEL BODY DIV END-->
    </div><!--TAB CONTENT DIV END-->
</div><!--PANEL BODY DIV END-->