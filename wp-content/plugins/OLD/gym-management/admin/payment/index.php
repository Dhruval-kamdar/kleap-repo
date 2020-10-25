<?php 
$obj_class=new MJ_Gmgtclassschedule;
$obj_payment=new MJ_Gmgtpayment;
global $wpdb;

$active_tab = isset($_GET['tab'])?$_GET['tab']:'incomelist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		   <div class="invoice_data"> </div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV START-->	
	<div class="page-title">
			<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE PAYMENT DATA
	if(isset($_POST['save_product']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_product_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_payment->MJ_gmgt_add_payment($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_payment&tab=paymentlist&message=2');
				}				
					
			}
			else
			{
				$result=$obj_payment->MJ_gmgt_add_payment($_POST);
		
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_payment&tab=paymentlist&message=1');
				}			
			}
				
		}
	}
	//DELETE PAYMENT,INCOME AND EXPENSE DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['payment_id'])){
		$result=$obj_payment->MJ_gmgt_delete_payment($_REQUEST['payment_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_payment&tab=paymentlist&message=3');
		}
		}
		if(isset($_REQUEST['income_id']))
		{
		$result=$obj_payment->MJ_gmgt_delete_income($_REQUEST['income_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=incomelist&message=3');
			}
		}
		if(isset($_REQUEST['expense_id'])){
			$result=$obj_payment->MJ_gmgt_delete_expense($_REQUEST['expense_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=expenselist&message=7');
			}
		}
	}
	//Multi select delete income data//
	if(isset($_REQUEST['delete_selected_income']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_income=$obj_payment->MJ_gmgt_delete_income($id);
				
			}
			if($delete_income)
			{
					wp_redirect ( admin_url().'admin.php?page=gmgt_payment&tab=incomelist&message=3');
			}
		}
        else
		{

			echo '<script language="javascript">';
            echo 'alert("'.__('Please select at least one record.','gym_mgt').'")';
            echo '</script>';
		}
	}
	// multi select delete expanse data //
	if(isset($_REQUEST['delete_selected_expense']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_expense=$obj_payment->MJ_gmgt_delete_expense($id);
				
			}
			if($delete_expense)
			{
					wp_redirect ( admin_url().'admin.php?page=gmgt_payment&tab=expenselist&message=7');
			}
		}
        else
		{

			echo '<script language="javascript">';
            echo 'alert("'.__('Please select at least one record.','gym_mgt').'")';
            echo '</script>';
		}
	}
	
	//--------save income-------------
	if(isset($_POST['save_income']))
	{	
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_income_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{				
				$result=$obj_payment->MJ_gmgt_add_income($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=incomelist&message=2');
				}
			}
			else
			{
				$result=$obj_payment->MJ_gmgt_add_income($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=incomelist&message=1');
				}
			}
		}		
	}		
	//--------save Expense-------------
	if(isset($_POST['save_expense']))
	{	
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_expense_nonce' ) )
		{		
			if($_REQUEST['action']=='edit')
			{
					
				$result=$obj_payment->MJ_gmgt_add_expense($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=expenselist&message=6');
				}
			}
			else
			{
				$result=$obj_payment->MJ_gmgt_add_expense($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=expenselist&message=5');
				}
			}
		}		
	}
	//ADD INCOME PAYMENT DATA
	if(isset($_POST['add_fee_payment']))
	{			
		$result=$obj_payment->MJ_gmgt_add_income_payment($_POST);
			
		wp_redirect ( admin_url() . 'admin.php?page=gmgt_payment&tab=incomelist&message=4');			
	}
	
		if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					_e('Invoice added successfully.','gym_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Invoice updated successfully.",'gym_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Invoice deleted successfully.','gym_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Payment successfully.','gym_mgt');
		?></div></p><?php
				
		}
		if($message == 5)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					_e('Expense added successfully.','gym_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 6)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Expense updated successfully.",'gym_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 7) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Expense deleted successfully.','gym_mgt');
		?></div></p><?php
				
		}
	}
	?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->
		<div class="row"><!--ROW DIV START-->
			<div class="col-md-12"><!--COL 12 DIV START-->
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->
					<div class="panel-body"><!--PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU START-->						
							<a href="?page=gmgt_payment&tab=incomelist" class="nav-tab <?php echo $active_tab == 'incomelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Invoice List', 'gym_mgt'); ?></a>
							 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['income_id']))
							{?>
							<a href="?page=gmgt_payment&tab=addincome&action=edit&income_id=<?php echo $_REQUEST['income_id'];?>" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Invoice', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_payment&tab=addincome" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Invoice', 'gym_mgt'); ?></a>  
							<?php  }?>
							<a href="?page=gmgt_payment&tab=expenselist" class="nav-tab <?php echo $active_tab == 'expenselist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Expense List', 'gym_mgt'); ?></a>
							 <?php  
							 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
							{?>
							<a href="?page=gmgt_payment&tab=addexpense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Expense', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_payment&tab=addexpense" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Expense', 'gym_mgt'); ?></a>  
							<?php  
							}
							?>						   
						</h2><!--NAV TAB WRAPPER MENU END-->	
						<?php 						
						if($active_tab == 'paymentlist')
						{ 
							?>	
							<script type="text/javascript">
							$(document).ready(function() {
								jQuery('#payment_list').DataTable({
									"responsive": true,
									"order": [[ 0, "asc" ]],
									"aoColumns":[
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": false}],
										language:<?php echo MJ_gmgt_datatable_multi_language();?>			  
									});
							} );
							</script>
							<form name="wcwm_report" action="" method="post"><!--PAYMENT LIST FORM START-->	
								<div class="panel-body"><!--PANEL BODY DIV START-->	
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->	
										<table id="payment_list" class="display" cellspacing="0" width="100%"><!--PAYMENT LIST TABLE START-->	
											<thead>
												<tr>
													<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Total Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Payment Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Total Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Payment Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											 <?php 
												$paymentdata=$obj_payment->MJ_gmgt_get_all_payment();
												if(!empty($paymentdata))
												{
													foreach ($paymentdata as $retrieved_data)
													{
														?>
														<tr>
															<td class="productname"><a href="?page=gmgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $retrieved_data->payment_id;?>"><?php echo $retrieved_data->title;?></a></td>
															<td class="paymentby"><?php $user=get_userdata($retrieved_data->member_id);
																$memberid=get_user_meta($retrieved_data->member_id,'member_id',true);
																$display_label=$user->display_name;
																if($memberid)
																	$display_label.=" (".$memberid.")";
																echo $display_label;
																?></td>
															<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->total_amount;?></td>
															<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->payment_date);?></td>
															
															<td class="action">
															<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->payment_id; ?>" invoice_type="invoice">
															<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
															<a href="?page=gmgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $retrieved_data->payment_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
															<a href="?page=gmgt_payment&tab=paymentlist&action=delete&payment_id=<?php echo $retrieved_data->payment_id;?>" class="btn btn-danger" 
															onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
															<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
															
															</td>
														</tr>
														<?php 
													} 													
												}
												?>										 
											</tbody>										
										</table><!--PAYMENT LIST TABLE END-->
									</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY DIV END-->
							</form><!--PAYMENT LIST FORM END-->
							<?php 
						}
						if($active_tab == 'addpayment')
						{
							require_once GMS_PLUGIN_DIR. '/admin/payment/add_payment.php';
						}
						if($active_tab == 'incomelist')
						{
							require_once GMS_PLUGIN_DIR. '/admin/payment/income-list.php';
						}
						if($active_tab == 'addincome')
						{
							require_once GMS_PLUGIN_DIR. '/admin/payment/add_income.php';
						}
						if($active_tab == 'expenselist')
						{
							require_once GMS_PLUGIN_DIR. '/admin/payment/expense-list.php';
						}
						if($active_tab == 'addexpense')
						{
							require_once GMS_PLUGIN_DIR. '/admin/payment/add_expense.php';
						}
						?>
					</div><!--PANEL BODY DIV END-->	
				</div><!--PANEL WHITE DIV END-->	
		    </div><!--COL 12 DIV END-->	
		</div><!--ROW DIV END-->	
	</div><!--MAIN WRAPPER DIV END-->	
</div><!--PAGE INNER DIV END-->	