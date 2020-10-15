<?php $obj_class=new MJ_Gmgtclassschedule;
$obj_product=new MJ_Gmgtproduct;
$obj_store=new MJ_Gmgtstore;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'store';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="invoice_data">
     </div>
    </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV STRAT-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php	
	//ADD SELL Payment DATA
	if(isset($_POST['add_fee_payment']))
	{
		$result=$obj_store->MJ_gmgt_sell_payment($_POST);
	
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_store&tab=store&message=5');
		}	
	}
	//SAVE SELL PRODUCT DATA
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
					wp_redirect ( admin_url().'admin.php?page=gmgt_store&tab=store&message=2');
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
					wp_redirect ( admin_url().'admin.php?page=gmgt_store&tab=store&message=1');
				}
			}		
		}
	}
	//Delete SELL Product DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_store->MJ_gmgt_delete_selling($_REQUEST['sell_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_store&tab=store&message=3');
		}
	}
	//Delete Selected SELL Product DATA
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_store=$obj_store->MJ_gmgt_delete_selling($id);
				
			}
			if($delete_store)
			{
					wp_redirect ( admin_url().'admin.php?page=gmgt_store&tab=store&message=3');
			}
		}
        else
		{
			echo '<script language="javascript">';
            echo 'alert("'.__('Please select at least one record.','gym_mgt').'")';
            echo '</script>';
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
	<div id="main-wrapper"><!--MAIN WRAPPER DIV STRAT-->
		<div class="row"><!--ROW DIV STRAT-->
			<div class="col-md-12"><!--COL 12 DIV STRAT-->
				<div class="panel panel-white"><!--PANEL WHITE DIV STRAT-->
					<div class="panel-body"><!--PANEL BODY DIV STRAT-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER DIV STRAT-->
							<a href="?page=gmgt_store&tab=store" class="nav-tab <?php echo $active_tab == 'store' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Sales Record', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=gmgt_store&tab=sellproduct&action=edit&sell_id=<?php echo $_REQUEST['sell_id'];?>" class="nav-tab <?php echo $active_tab == 'sellproduct' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Sell Product', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_store&tab=sellproduct" class="nav-tab <?php echo $active_tab == 'sellproduct' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Sell New Product ', 'gym_mgt'); ?></a>
								
							<?php  
							}
							?>       
						</h2><!--NAV TAB WRAPPER DIV END-->
						<?php 	
						if($active_tab == 'store')
						{ 						
							?>	
							<script type="text/javascript">
								$(document).ready(function() {
								jQuery('#selling_list').DataTable({
									"responsive": true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[
												  {"bSortable": false},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": false}],
											language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
									});
									$('.select_all').on('click', function(e)
									{
										 if($(this).is(':checked',true))  
										 {
											$(".sub_chk").prop('checked', true);  
										 }  
										 else  
										 {  
											$(".sub_chk").prop('checked',false);  
										 } 
									});
								
									$('.sub_chk').change(function()
									{ 
										if(false == $(this).prop("checked"))
										{ 
											$(".select_all").prop('checked', false); 
										}
										if ($('.sub_chk:checked').length == $('.sub_chk').length )
										{
											$(".select_all").prop('checked', true);
										}
								  });
							} );
							</script>
							<form name="wcwm_report" action="" method="post"><!--SELL Product LIST FORM START-->	
								<div class="panel-body"><!--PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="selling_list" class="display" cellspacing="0" width="100%"><!--SELL Product LIST TABLE START-->
											 <thead>
												<tr>
												<th><input type="checkbox" class="select_all"></th>
												<th><?php  _e( 'Invoice No', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>					
												<th><?php  _e( 'Product Name=>Product Quantity', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Total Amount', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
									 
											<tfoot>
												<tr>
												<th></th>
												<th><?php  _e( 'Invoice No', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>		
												<th><?php  _e( 'Product Name=>Product Quantity', 'gym_mgt' ) ;?></th>					
												<th><?php  _e( 'Total Amount', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
												   <th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
									 
											<tbody>
											 <?php 		
											//GET SELL PRODUCT DATA
											$storedata=$obj_store->MJ_gmgt_get_all_selling();
												
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
												<tr>
													<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->id; ?>"></td>
													<td class="productquentity">
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
															
													<td class="productquentity"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> 
													<?php echo number_format($total_amount,2); ?></td>
													<td class="productquentity"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php
													echo number_format($paid_amount,2); ?></td>
													<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php
													echo number_format($due_amount,2); ?></td>
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
													if ($retrieved_data->payment_status == 'Unpaid' || $retrieved_data->payment_status == 'Partially Paid')
													{ 
														$due_amount=$retrieved_data->total_amount-$retrieved_data->paid_amount;
														?>
														
													<td class="action">
													
													<a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" member_id="<?php echo $retrieved_data->member_id; ?>" due_amount="<?php echo str_replace(",","",number_format($due_amount,2)); ?>"  view_type="sale_payment" ><?php _e('Pay','gym_mgt');?></a>
													<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
													<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
													<?php
													if(!empty($retrieved_data->invoice_no))
													{
													?>
														<a href="?page=gmgt_store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
													<?php
													}
													?>
													<a href="?page=gmgt_store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
													
													</td>
													<?php }  if ($retrieved_data->payment_status == 'Fully Paid'  || $retrieved_data->payment_status == '' ) {
														?>
													 <td class="action">
													<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="sell_invoice">
													<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
													<?php
													if(!empty($retrieved_data->invoice_no))
													{
													?>
														<a href="?page=gmgt_store&tab=sellproduct&action=edit&sell_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
												   <?php
													}				
													?>
													<a href="?page=gmgt_store&tab=store&action=delete&sell_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
													 </td>
												</tr>
												<?php } 
												}
											 }
												
											?>
											</tbody>
										</table><!--SELL Product LIST TABLE END-->	
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!--TABLE RESPONSIVE DIV END-->	
								</div>	<!--PANEL BODY END-->			   
							</form><!--SELL Product LIST FORM END-->	
							 <?php 
						}						
						if($active_tab == 'sellproduct')
						{
							require_once GMS_PLUGIN_DIR. '/admin/store/sell_product.php';
						}			
						?>
					</div><!--PANEL BODY DIV END-->					
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
	</div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->