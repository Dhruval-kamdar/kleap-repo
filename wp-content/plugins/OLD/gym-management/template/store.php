<?php 
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_product=new MJ_Gmgtproduct;
$obj_store=new MJ_Gmgtstore;
$obj_class=new MJ_Gmgtclassschedule;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'store';
//access right
$user_access=MJ_gmgt_get_userrole_wise_page_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_gmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
	}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='success')	
{ ?>
		<div id="message" class="updated below-h2 "><p>	<?php _e('Payment successfully','gym_mgt');	?></p></div>
	<?php
}	
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='cancel')	
{ ?>
	<div id="message" class="updated below-h2 "><p>	<?php 	_e('Payment Cancel','gym_mgt');	?></p></div>
<?php
}
if(isset($_POST['payer_status']) && $_POST['payer_status'] == 'VERIFIED' && (isset($_POST['payment_status'])) && $_POST['payment_status']=='Completed' && isset($_REQUEST['half']) && $_REQUEST['half']=='yes' )
{	
	$saledata['member_id']=get_current_user_id();
	$custom_array = explode("_",$_POST['custom']);
	$saledata['sell_id']=$custom_array[1];
	$saledata['amount']=$_POST['mc_gross_1'];
	$saledata['payment_method']='paypal';
	$saledata['trasaction_id']=$_POST["txn_id"];
	$result = $obj_store->MJ_gmgt_add_sellpayment_history($saledata);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=store&action=success');
	}
}
if(isset($_REQUEST['amount']) && (isset($_REQUEST['pay_id'])) && isset($_REQUEST['payment_request_id']) )
{
	$saledata['member_id']=get_current_user_id();
	$saledata['sell_id']=$_REQUEST['pay_id'];
	$saledata['amount']=$_REQUEST['amount'];
	$saledata['payment_method']='Instamojo';
	$saledata['trasaction_id']=$_REQUEST['payment_request_id'];		
	$result = $obj_store->MJ_gmgt_add_sellpayment_history($saledata);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=store&action=success');
	}
}

if(isset($_REQUEST['skrill_mp_id']) && (isset($_REQUEST['amount'])))
{
	$saledata['member_id']=get_current_user_id();
	$saledata['sell_id']=$_REQUEST['skrill_mp_id'];
	$saledata['amount']=$_REQUEST['amount'];
	$saledata['payment_method']='Skrill';
	$saledata['trasaction_id']='';
	$result = $obj_store->MJ_gmgt_add_sellpayment_history($saledata);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=store&action=success');
	}
}

if(isset($_REQUEST['action']) && $_REQUEST['action']=="ideal_payments" && $_REQUEST['page']=="store" && isset($_REQUEST['ideal_pay_id']) && isset($_REQUEST['ideal_amt']))
{
	$saledata['member_id']=get_current_user_id();
	$saledata['sell_id']=$_REQUEST['ideal_pay_id'];
	$saledata['amount']=$_REQUEST['ideal_amt'];
	$saledata['payment_method']='iDeal';
	$saledata['trasaction_id']='';
	$result = $obj_store->MJ_gmgt_add_sellpayment_history($saledata);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=store&action=success');
	}
}
if(isset($_POST['add_fee_payment']))
{
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
		$result=$obj_store->MJ_gmgt_sell_payment($_POST);
	
		if($result)
		{
			wp_redirect ( home_url().'?dashboard=user&page=store&tab=store&message=5');
		}	
	}
}
//SAVE Sell Product DATA
if(isset($_POST['save_selling']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_selling_nonce' ) )
	{
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{				
		$result=$obj_store->MJ_gmgt_sell_product($_POST);
		if($result=='3')
		{
			?>
				<div id="message" class="updated below-h2 ">
				<p>
					<?php _e('Discount Amount Must Be Less Than Product Total Amount','gym_mgt');?>
				</p></div>				 
			<?php 
		}
		else
		{
			wp_redirect ( home_url().'?dashboard=user&page=store&tab=store&message=2');
		}
		
	}
	else
	{			
		$result=$obj_store->MJ_gmgt_sell_product($_POST);
			
		if($result=='3')
		{
			?>
				<div id="message" class="updated below-h2 ">
				<p>
					<?php _e('Discount Amount Must Be Less Than Product Total Amount','gym_mgt');?>
				</p></div>				 
			<?php 
		}
		else
		{	
			wp_redirect ( home_url().'?dashboard=user&page=store&tab=store&message=1');
		}			
	}
}
}
//DELETE SELL PRODUCT DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=$obj_store->MJ_gmgt_delete_selling($_REQUEST['sell_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=store&tab=store&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Sales Record added successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Sales Record updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Sales Record deleted successfully.','gym_mgt');
	?></div></p><?php
			
	}
	elseif($message == 4) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Out of Stock product.','gym_mgt');
	?></div></p><?php
			
	}
	elseif($message == 5) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Payment successfully.','gym_mgt');
	?></div></p><?php
			
	}
}
?>	
<script type="text/javascript">
$(document).ready(function()
{
	$('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','gym_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','gym_mgt'); ?>'
		 });
	var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format'); ?>";
            $('#sell_date').datepicker({
	        <?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
            autoclose: true
           });
	jQuery('#selling_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
					
	                  {"bSortable": false}
					
					],
				language:<?php echo MJ_gmgt_datatable_multi_language();?>		
		});
		$('#store_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$(".display-members").select2();
	
} );
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		    <div class="invoice_data"></div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white"><!--PANEL BODY DIV START -->
	<ul class="nav nav-tabs panel_tabs" role="tablist">	<!--NAV TABS MENU START -->	   
			<li class="<?php if($active_tab=='store'){?>active<?php }?>">
				<a href="?dashboard=user&page=store&tab=store" class="nav-tab <?php echo $active_tab == 'store' ? 'nav-tab-active' : ''; ?>">
			 <i class="fa fa-align-justify"></i> <?php _e('Sales Record', 'gym_mgt'); ?></a>
			</li>
		   <li class="<?php if($active_tab=='sellproduct'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['sell_id']))
				{?>
				<a href="?dashboard=user&page=store&tab=sellproduct&action=edit&sell_id=<?php echo $_REQUEST['sell_id'];?>" class="nav-tab <?php echo $active_tab == 'sellproduct' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Sell Product', 'gym_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{
					?>
						<a href="?dashboard=user&page=store&tab=sellproduct&&action=insert" class="nav-tab <?php 	echo $active_tab == 'sellproduct' ? 'nav-tab-active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Sell New Product', 'gym_mgt'); ?></a>
					<?php 
					}
				}
				?>
		    </li>		 
	</ul><!--NAV TABS MENU END -->	   
	<div class="tab-content"><!--TAB CONTENT DIV START -->	 
		<?php
		if($active_tab == 'store')
		{ ?>	
			<div class="panel-body"><!--PANEL BODY DIV START -->	   
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV START -->	   
					<table id="selling_list" class="display" cellspacing="0" width="100%"><!--TABLE SELL PRODUCT LIST START -->	   
						<thead>
						   <tr>
								<th><?php  _e( 'Invoice No', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>		
								<th><?php  _e( 'Product Name=>Product Quantity', 'gym_mgt' ) ;?></th>					
								<th><?php  _e( 'Total Amount', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
							
								 <th style="width:130px;"><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
								
							</tr>
						</thead>
						<tfoot>
							<tr>
							<th><?php  _e( 'Invoice No', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>		
							<th><?php  _e( 'Product Name=>Product Quantity', 'gym_mgt' ) ;?></th>					
							<th><?php  _e( 'Total Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
							
							   <th style="width:130px;"><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
							
							</tr>
						</tfoot>
						<tbody>
							<?php 
							if($obj_gym->role == 'member')
							{	
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();
									$storedata=$obj_store->MJ_gmgt_get_all_selling_by_member($user_id);		
								}
								else
								{
									$storedata=$obj_store->MJ_gmgt_get_all_selling();
								}	
							}
							else
							{	
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();							
									$storedata=$obj_store->MJ_gmgt_get_all_selling_by_sell_by($user_id);
								}
								else
								{
									$storedata=$obj_store->MJ_gmgt_get_all_selling();
								}	
							}
							
							if(!empty($storedata))
							{
							foreach ($storedata as $retrieved_data)
							{							
								if(empty($retrieved_data->invoice_no))
								{
									$obj_product=new MJ_Gmgtproduct;
									$product = $obj_product->MJ_gmgt_get_single_product($retrieved_data->product_id); 				
								
									$price=$product->price;	
									$quentity=$retrieved_data->quentity;	
								
									$invoice_no='-';					
									$total_amount=$price*$quentity;
									$paid_amount=$price*$quentity;
									
									$due_amount='0';
								}
								else
								{
									$invoice_no=$retrieved_data->invoice_no;
									$total_amount=$retrieved_data->total_amount;
									$paid_amount=$retrieved_data->paid_amount;
									$due_amount=$total_amount-$paid_amount;
								}		
							?>
								<tr><td class="productquentity">
								<?php echo $invoice_no; ?>
								</td>	
									<td class="membername"><?php $userdata=get_userdata($retrieved_data->member_id);
									echo $userdata->display_name;?></td>				
									<td class="productname">
									<?php 
									$entry_valuea=json_decode($retrieved_data->entry);
									if(!empty($entry_valuea))
									{
										foreach($entry_valuea as $entry_valueb)
										{
											$product = $obj_product->MJ_gmgt_get_single_product($entry_valueb->entry);
															
											$product_name=$product->product_name;					
											$quentity=$entry_valueb->quentity;										
											
											$product_quantity=$product_name . " => " . $quentity . ",";
											echo rtrim($product_quantity,',');
											?>
											<br>
											<?php
										}
									}
									else
									{
										$obj_product=new MJ_Gmgtproduct;
										$product = $obj_product->MJ_gmgt_get_single_product($retrieved_data->product_id); 
										
										$product_name=$product->product_name;					
										$quentity=$retrieved_data->quentity;	
										echo  $product_name . " => " . $quentity;
									}									
									?>
									</td>
									<td class="productquentity"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($total_amount,2);?></td>
									<td class="productquentity"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($paid_amount,2);?></td>
									<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); 
									?> <?php echo number_format($due_amount,2);?></td>
									<td class="paymentdate">
									<?php 
									if(!empty($retrieved_data->payment_status))
									{
										echo "<span class='btn btn-success btn-xs'>";									
										echo  __("$retrieved_data->payment_status","gym_mgt");
										echo "</span>";
									}								
									else
									{
										echo "<span class='btn btn-success btn-xs'>";				
										echo  __("Fully Paid","gym_mgt");
										echo "</span>";
									}	
									?>
									</td>
									<?php 
									if($obj_gym->role == 'member')
									{
										if ($retrieved_data->payment_status == 'Unpaid' || $retrieved_data->payment_status == 'Partially Paid')
										{ $due_amount=$retrieved_data->total_amount-$retrieved_data->paid_amount;?>
										<td class="action" style="width:130px;">
									   <a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" member_id="<?php echo $retrieved_data->member_id; ?>" due_amount="<?php echo str_replace(",","",number_format($due_amount,2)); ?>"  view_type="sale_payment" ><?php _e('Pay','gym_mgt');?></a>				 
										<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
										<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
										<?php
										if(!empty($retrieved_data->invoice_no))
										{									
											if($user_access['edit']=='1')
											{
											?>
									
											<a href="?dashboard=user&page=store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
										<?php
											}
										}
										if($user_access['delete']=='1')
										{
										?>
											<a href="?dashboard=user&page=store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
											onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
											<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
										<?php
										}
										?>
										</td>
										<?php 
										} elseif($retrieved_data->payment_status == 'Fully Paid' || $retrieved_data->payment_status == '' )
										{ ?>
											<td class="action" style="width:130px;">
											<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
											<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
											<?php
												if(!empty($retrieved_data->invoice_no))
												{
													if($user_access['edit']=='1')
													{
												?>
													<a href="?dashboard=user&page=store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
												<?php
													}
												}
												if($user_access['delete']=='1')
												{												
												?>
													<a href="?dashboard=user&page=store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
												<?php
												}
												?>
										</td>
												
										<?php 
										} 
									} 
									if($obj_gym->role == 'accountant')
									{ 
										if ($retrieved_data->payment_status == 'Unpaid' || $retrieved_data->payment_status == 'Partially Paid')
										{ 
											$due_amount=$retrieved_data->total_amount-$retrieved_data->paid_amount;
											?>
											<td class="action" style="width:130px;">
												<a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" member_id="<?php echo $retrieved_data->member_id; ?>" due_amount="<?php echo str_replace(",","",number_format($due_amount,2)); ?>"  view_type="sale_payment" ><?php _e('Pay','gym_mgt');?></a>				 
												<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
												<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
												<?php
												if(!empty($retrieved_data->invoice_no))
												{
													if($user_access['edit']=='1')
													{
												?>
													<a href="?dashboard=user&page=store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
												<?php
													}
												}
												if($user_access['delete']=='1')
												{											
												?>
													<a href="?dashboard=user&page=store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
												<?php
												}
												?>
											</td>
										<?php
										}
										else
										{	
											
											?>
											<td class="action" style="width:130px;"> 
												<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
												<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
												<?php
												if(!empty($retrieved_data->invoice_no))
												{
													if($user_access['edit']=='1')
													{
													?>
													<a href="?dashboard=user&page=store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
													<?php
													}
												}
												if($user_access['delete']=='1')
												{											
												?>
													<a href=	"?dashboard=user&page=store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
												<?php
												}
												?>
											</td>
										<?php 
										}
									}
									if($obj_gym->role == 'staff_member')
									{ 
									?>
										<td class="action" style="width:130px;"> 
												<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
												<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
												<?php
												if(!empty($retrieved_data->invoice_no))
												{
													if($user_access['edit']=='1')
													{
													?>
													<a href="?dashboard=user&page=store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
													<?php
													}
												}
												if($user_access['delete']=='1')
												{											
												?>
													<a href=	"?dashboard=user&page=store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
												<?php
												}
												?>
											</td>
											<?php
									}
									?>
								</tr>
								<?php
							} 
							}?>
						</tbody>
					</table><!--TABLE SELL PRODUCT LIST END -->	   
				</div><!--TABLE RESPONSIVE DIV END -->	   
			</div><!--PANEL BODY DIV END -->	   
		<?php 
		}
		if($active_tab == 'sellproduct')
		{
			$sell_id=0;
			if(isset($_REQUEST['sell_id']))
				$sell_id=$_REQUEST['sell_id'];
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{					
					$edit=1;
					$result = $obj_store->MJ_gmgt_get_single_selling($sell_id);					
				}
				?>		
			<div class="panel-body"><!--PANEL BODY DIV START -->	 
				<form name="store_form" action="" method="post" class="form-horizontal" id="store_form"><!--STORE FORM START -->	 
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="invoice_number" value="<?php if($edit){ echo $result->invoice_no; } ?>">
					<input type="hidden" name="sell_id" value="<?php if($edit){ echo $sell_id; }?>"  />
					<input type="hidden" name="paid_amount" value="<?php  if($edit){ echo $result->paid_amount; } ?>"  />	
					
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
						<div class="col-sm-8">
							<?php if($edit){ $member_id=$result->member_id; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
							<select id="member_list" class="display-members" required="true" name="member_id">
							
								<?php $get_members = array('role' => 'member');
								$membersdata=get_users($get_members);
								 if(!empty($membersdata))
								 {
									foreach ($membersdata as $member){?>
										<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
									<?php }
								 }?>
						</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="sell_date"><?php _e('Date','gym_mgt');?></label>
						<div class="col-sm-8">
							<input id="sell_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="sell_date" 
							value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->sell_date);}elseif(isset($_POST['sell_date'])){ echo $_POST['sell_date'];}else{ echo MJ_gmgt_getdate_in_input_box(date("Y-m-d")); }?>" readonly>
						</div>
					</div>
					<hr>		
					<?php 
						if($edit)
						{
							$all_entry=json_decode($result->entry);
						}
						
						if(!empty($all_entry))
						{
								foreach($all_entry as $entry)
								{
								?>
								<!--old product data-->
								<div style="display:none">								
									<select id="product_id" class="form-control validate[required]"  name="old_product_id[]">
									<option value=""><?php _e('Select Product','gym_mgt');?></option>
										<?php 
										$productdata=$obj_product->MJ_gmgt_get_all_product();
										if(!empty($productdata))
										{
											foreach ($productdata as $product)
											{	
											?>
												<option value="<?php echo $product->id;?>" <?php selected($entry->entry,$product->id);  ?>><?php echo $product->product_name; ?> </option>
											<?php 	
											} 
										} 
									?>
								</select>
							  </div>				  
							 <div style="display:none">
								 <input id="group_name" class="form-control validate[required] text-input decimal_number quantity" maxlength="6" placeholder="<?php _e('Product Quantity','gym_mgt');?>" type="text" 
								 value="<?php echo $entry->quentity;?>" name="old_quentity[]" >
							 </div>
								<!--end old product data-->	 
								<div id="expense_entry">
									<div class="form-group">
									<label class="col-sm-2 control-label" for="income_entry"><?php _e('Product Entry','gym_mgt');?><span class="require-field"> *</span></label>
									<div class="col-sm-4">								
											<select id="product_id" class="form-control validate[required] product_id<?php echo $i; ?>" row="<?php echo $i; ?>" name="product_id[]">
											<option value=""><?php _e('Select Product','gym_mgt');?></option>
												<?php 
												$productdata=$obj_product->MJ_gmgt_get_all_product();
												if(!empty($productdata))
												{
													foreach ($productdata as $product)
													{	
													?>
														<option value="<?php echo $product->id;?>" <?php selected($entry->entry,$product->id);  ?>><?php echo $product->product_name; ?> </option>
													<?php 	
													} 
												} 
											?>
										</select>
									  </div>
									 <div class="col-sm-2">
										 <input id="group_name" class="form-control validate[required] text-input decimal_number quantity quantity<?php echo $i; ?>" row="<?php echo $i; ?>" onkeypress="if(this.value.length==4) return false;" placeholder="Product Quantity" type="number" min="1" 
										 value="<?php echo $entry->quentity;?>" name="quentity[]" >
									 </div>
									<div class="col-sm-2">
									<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
									<i class="entypo-trash"><?php _e('Delete','gym_mgt');?></i>
									</button>
									</div>
									</div>	
								</div>
								<?php
								}				
						}
						else
						{?>
								<div id="expense_entry">
									<div class="form-group">
									<label class="col-sm-2 control-label" for="income_entry"><?php _e('Product Entry','gym_mgt');?><span class="require-field"> *</span></label>
									<div class="col-sm-4">								
											<select id="product_id" class="form-control validate[required] product_id1" row="1" value="<?php echo $entry->product_id;?>" name="product_id[]">
											<option value=""><?php _e('Select Product','gym_mgt');?></option>
											<?php 
												$productdata=$obj_product->MJ_gmgt_get_all_product();
												 if(!empty($productdata))
												 {
													foreach ($productdata as $product)
													{?>
													<option value="<?php echo $product->id;?>"><?php echo $product->product_name; ?> </option>
												<?php
													} 
												} 
											?>
										</select>
									  </div>
									 <div class="col-sm-2">
										 <input id="group_name" class="form-control validate[required] text-input decimal_number quantity quantity1" row="1" onkeypress="if(this.value.length==4) return false;" placeholder="<?php _e('Product Quantity','gym_mgt');?>" type="number" min="1" 
										 value="" name="quentity[]" >
									 </div>
									<div class="col-sm-2">
									<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
									<i class="entypo-trash"><?php _e('Delete','gym_mgt');?></i>
									</button>
									</div>
									</div>	
								</div>
								
					 <?php 
						} ?>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="expense_entry"></label>
						<div class="col-sm-3">				
							<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Product Entry','gym_mgt'); ?>
							</button>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="quentity"><?php _e('Discount Amount ','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field"></span></label>
						<div class="col-sm-8">
							<input id="group_name" min="0" class="form-control text-input decimal_number discount_amount" min="0" step="0.01" type="number" onKeyPress="if(this.value.length==6) return false;"  value="<?php if($edit){ echo $result->discount;}elseif(isset($_POST['discount'])) echo $_POST['discount'];?>"  placeholder="<?php _e('Discount must be Amount Like 100','gym_mgt');?> <?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?>"  name="discount">
						</div>
					
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for=""><?php _e('Tax','gym_mgt');?>(%)</label>
						<div class="col-sm-3">
							<select  class="form-control tax_charge" name="tax[]" multiple="multiple">					
								<?php					
								if($edit)
								{
									$tax_id=explode(',',$result->tax_id);
								}
								else
								{	
									$tax_id[]='';
								}
								$obj_tax=new MJ_Gmgttax;
								$gmgt_taxs=$obj_tax->MJ_gmgt_get_all_taxes();	
								
								if(!empty($gmgt_taxs))
								{
									foreach($gmgt_taxs as $data)
									{
										$selected = "";
										if(in_array($data->tax_id,$tax_id))
											$selected = "selected";
										?>
										<option value="<?php echo $data->tax_id; ?>" <?php echo $selected; ?> ><?php echo $data->tax_title;?>-<?php echo $data->tax_value;?></option>
									<?php 
									}
								}
								?>
							</select>		
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_selling_nonce' ); ?>
					<!--nonce-->					
					<div class="col-sm-offset-2 col-sm-8">
						
						<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Sell Product','gym_mgt');}?>" name="save_selling" class="btn btn-success"/>
					</div>
				</form><!--STORE FORM END -->
			</div><!--PANEL BODY DIV END -->
			 <?php 
		}
		?>
	</div><!--TAB CONTENT DIV END -->	 
</div><!--PANEL BODY DIV END -->
<script>
	var value = 1;
   	function add_entry()
   	{
   		value++;
   		$("#expense_entry").append('<div id="expense_entry"><div class="form-group"><label class="col-sm-2 control-label" for="income_entry"><?php _e('Product Entry','gym_mgt');?><span class="require-field"> *</span></label><div class="col-sm-4"><select id="product_id" class="form-control validate[required] product_id'+value+'" row="'+value+'" name="product_id[]"><option value=""><?php _e('Select Product','gym_mgt');?></option><?php $productdata=$obj_product->MJ_gmgt_get_all_product();if(!empty($productdata)){foreach ($productdata as $product){?><option value="<?php echo $product->id;?>"><?php echo $product->product_name; ?> </option>	<?php } } ?>  </select></div><div class="col-sm-2"><input id="group_name" class="form-control validate[required] text-input decimal_number quantity quantity'+value+'" row="'+value+'" onkeypress="if(this.value.length==4) return false;" placeholder="<?php _e('Product Quantity','gym_mgt');?>" type="number" value="" min="1" name="quentity[]" ></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','gym_mgt');?></i></button></div></div></div>');
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
		alert("<?php _e('Do you really want to delete this record','gym_mgt');?>");
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
</script> 