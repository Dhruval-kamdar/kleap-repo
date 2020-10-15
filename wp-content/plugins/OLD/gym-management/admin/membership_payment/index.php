<?php 
$obj_membership_payment=new MJ_Gmgt_membership_payment;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'paymentlist';
$result=0;
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
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV STRAT-->
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//ADD FEES PAYMENT DATA
	if(isset($_POST['add_fee_payment']))
	{
		//POP up data save in payment history
		$result=$obj_membership_payment->MJ_gmgt_add_feespayment_history($_POST);			
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=gmgt_fees_payment&tab=paymentlist&message=4');
		}
	}
	//SAVE MEMBERSHIP PAYMENT DATA
	if(isset($_POST['save_membership_payment']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_membership_payment_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_membership_payment->MJ_gmgt_add_membership_payment($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_fees_payment&tab=paymentlist&message=2');
				}		
					
			}
			else
			{
				$result=$obj_membership_payment->MJ_gmgt_add_membership_payment($_POST);
				if($result)
				{
					$user_info=get_userdata($_POST['member_id']);
					$to = $user_info->user_email;           
					$subject = get_option('subscription_template_title'); 
					$search=array('[GMGT_MEMBERNAME]','[GMGT_MEMBERID]','[GMGT_STARTDATE]','[GMGT_ENDDATE]','[GMGT_MEMBERSHIP]','[GMGT_MEMBERSHIP_AMOUNT]');
					$membership_name=MJ_gmgt_get_membership_name($_POST['membership_id']);
					
					$replace = array($user_info->display_name,$user_info->member_id,$_POST['start_date'],$_POST['end_date'],$membership_name,$_POST['membership_amount']);
					$message = str_replace($search, $replace,get_option('subcription_mailcontent'));	
					wp_mail($to, $subject, $message); 
					
					wp_redirect ( admin_url().'admin.php?page=gmgt_fees_payment&tab=paymentlist&message=1');
				}
			}
		}
	}
	//DELETE PAYMENT DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['mp_id'])){
		$result=$obj_membership_payment->MJ_gmgt_delete_payment($_REQUEST['mp_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_fees_payment&tab=paymentlist&message=3');
		}
		}
	}
	//Delete Selected PAYMENT DATA
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_membership_payment=$obj_membership_payment->MJ_gmgt_delete_payment($id);
				
			}
			if($delete_membership_payment)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_fees_payment&tab=paymentlist&message=3');
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
					_e('Membership Payment Invoice added successfully.','gym_mgt');
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
			_e('Membership Payment Invoice deleted  successfully.','gym_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Payment Successful','gym_mgt');
		?></div></p><?php
		}
	}
	?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV STRAT-->
		<div class="row"><!--ROW DIV STRAT-->
			<div class="col-md-12"><!--COL 12 DIV STRAT-->
				<div class="panel panel-white"><!--PANEL WHITE DIV STRAT-->
					<div class="panel-body"><!--PANEL BODY DIV STRAT-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU STRAT-->
							<a href="?page=gmgt_fees_payment&tab=paymentlist" class="nav-tab <?php echo $active_tab == 'paymentlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Membership Payment List', 'gym_mgt'); ?>
							</a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['mp_id']))
							{?>
								<a href="?page=gmgt_fees_payment&tab=addpayment&&action=edit&mp_id=<?php echo $_REQUEST['mp_id'];?>" class="nav-tab <?php echo $active_tab == 'addpayment' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Edit Membership Payment Invoice', 'gym_mgt'); ?></a>  
								<?php 
							}
							else
							{?>
								<a href="?page=gmgt_fees_payment&tab=addpayment" class="nav-tab <?php echo $active_tab == 'addpayment' ? 'nav-tab-active' : ''; ?>">
							    <?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Generate Membership Payment Invoice', 'gym_mgt'); ?></a>
					        <?php }?>
							
						</h2><!--NAV TAB WRAPPER MENU END-->
						<?php 						
						if($active_tab == 'paymentlist')
						{ 
						?>	
							<script type="text/javascript">
								$(document).ready(function()
								{
									jQuery('#payment_list').DataTable({
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
							<form name="wcwm_report" action="" method="post"><!--PAYMENT LIST FORM START-->
								<div class="panel-body"><!--PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="payment_list" class="display" cellspacing="0" width="100%"><!--PAYMENT LIST TABLE START-->
											<thead>
												<tr>
													<th><input type="checkbox" class="select_all"></th>
													<th><?php  _e( 'Invoice Number', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership <BR>Start Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership <BR>End Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th><?php  _e( 'Invoice Number', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership <BR>Start Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership <BR>End Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											<?php 
											$paymentdata=$obj_membership_payment->MJ_gmgt_get_all_membership_payment();
											if(!empty($paymentdata))
											{
												foreach ($paymentdata as $retrieved_data)
												{ ?>
												<tr>
													<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->mp_id; ?>"></td>
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
													<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->membership_amount;?></td>
													<td class="paid_amount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->paid_amount;?></td>
													<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->membership_amount-$retrieved_data->paid_amount;?></td>
													<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->start_date);?></td>
													<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->end_date);?></td>
													<td class="paymentdate">
													<?php 
													echo "<span class='btn btn-success btn-xs'>";echo  __(MJ_gmgt_get_membership_paymentstatus($retrieved_data->mp_id), 'gym_mgt' );
													echo "</span>";
													?>
													</td>
													<?php 												
													if(MJ_gmgt_get_membership_paymentstatus($retrieved_data->mp_id) == 'Fully Paid')
													{  ?>
														<td class="action">													
														<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->mp_id; ?>"  invoice_type="membership_invoice" >
														<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
														<?php
														if(!empty($retrieved_data->invoice_no))
														{
														?>
														<a href="?page=gmgt_fees_payment&tab=addpayment&action=edit&mp_id=<?php echo $retrieved_data->mp_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
														<?php
														}
														?>
														<a href="?page=gmgt_fees_payment&tab=paymentlist&action=delete&mp_id=<?php echo $retrieved_data->mp_id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
														<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
														</td>
													  
													   <?php
													} 
													else
													{
														$due_amount=$retrieved_data->membership_amount-$retrieved_data->paid_amount;
														?>
														<td class="action">
														<a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->mp_id; ?>" due_amount="<?php echo $due_amount; ?>"  view_type="payment" ><?php _e('Pay','gym_mgt');?></a>
														<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->mp_id; ?>"  invoice_type="membership_invoice" >
														<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
														<?php
														if(!empty($retrieved_data->invoice_no))
														{
														?>
														<a href="?page=gmgt_fees_payment&tab=addpayment&action=edit&mp_id=<?php echo $retrieved_data->mp_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
														<?php
														}
														?>
														<a href="?page=gmgt_fees_payment&tab=paymentlist&action=delete&mp_id=<?php echo $retrieved_data->mp_id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
														<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
														</td>
												</tr>
												<?php }  
												}
												
											}?>
										 
											</tbody>
										</table><!--PAYMENT LIST TABLE END-->
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY END-->
							</form><!--PAYMENT LIST FORM END-->
						<?php 
					    }
						if($active_tab == 'addpayment')
						{
						   require_once GMS_PLUGIN_DIR. '/admin/membership_payment/add_membership_payment.php';
						}
					?>
                    </div><!--PANEL BODY DIV END-->
	            </div><!--PANEL WHITE DIV END-->
	        </div><!--COL 12 DIV END-->
        </div><!--ROW DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNNER DIV END-->