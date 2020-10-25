<?php
$obj_payment= new MJ_Gmgtpayment();
if($active_tab == 'incomelist')
{
	$invoice_id=0;
	if(isset($_REQUEST['income_id']))
		$invoice_id=$_REQUEST['income_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_payment->hmgt_get_invoice_data($invoice_id);
		}
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			jQuery('#tblincome').DataTable({
				"responsive": true,
				 "order": [[ 1, "Desc" ]],
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
							  {"bSortable": false}
						   ],
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
        <div class="panel-body"><!--PANEL BODY DIV START-->
        	<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
				<form name="wcwm_report" action="" method="post"><!--INCOME LIST FORM START-->
				<table id="tblincome" class="display" cellspacing="0" width="100%"><!--INCOME LIST TABLE START-->
					<thead>
						<tr>
						    <th><input type="checkbox" class="select_all"></th>
							<th> <?php _e( 'Member Name', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Income Name', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Invoice No', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Date', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th></th>
							<th> <?php _e( 'Member Name', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Income Name', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Invoice No', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
							<th> <?php _e( 'Date', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
					 <?php 
						//GET ALL INCOME DATA
						$paymentdata=$obj_payment->MJ_gmgt_get_all_income_data();
						/* var_dump($paymentdata);
						die; */
						foreach ($paymentdata as $retrieved_data)
						{	
						 
							if(empty($retrieved_data->invoice_no))
							{
								$invoice_no='-';
								if($retrieved_data->invoice_label=='Sell Product')
								{	
									$entry=json_decode($retrieved_data->entry);
								
									if(!empty($entry))
									{
										foreach($entry as $data)
										{
											 $amount=$data->amount;
										}
									}							
									
									$total_amount=$amount;
									$paid_amount=$amount;
									
									$due_amount='0';
									
								}
								else
								{
									$entry=json_decode($retrieved_data->entry);
									$amount_value='0';
									if(!empty($entry))
									{
										foreach($entry as $data)
										{
											 $amount_value+=$data->amount;											 
										}
									}								
									
									if($retrieved_data->payment_status=='Paid')
									{
										$total_amount=$amount_value;
										$paid_amount=$amount_value;
										$due_amount='0';
									}
									else
									{
										$total_amount=$amount_value;
										$paid_amount='0';
										$due_amount=$amount_value;
									}
								}	
							}
							else
							{								
								$invoice_no=$retrieved_data->invoice_no;
								$total_amount=$retrieved_data->total_amount;
								$paid_amount=$retrieved_data->paid_amount;
								$due_amount=abs($total_amount-$paid_amount);
							}
							if($retrieved_data->total_amount == '0')
							{
								$status='Fully Paid';
							}
							else
							{
								$status=$retrieved_data->payment_status;
							}
							?>
							<tr>
							<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->invoice_id; ?>"></td>
								<td class="member_name"><?php $user=get_userdata($retrieved_data->supplier_name);
									$memberid=get_user_meta($retrieved_data->supplier_name,'member_id',true);
									$display_label=$user->display_name;
									if($memberid)
										$display_label.=" (".$memberid.")";
									echo $display_label;?></td>
								<td class="income_amount"><?php echo $retrieved_data->invoice_label;?></td>
								<td class="income_amount">							
								<?php
								echo $invoice_no;	
								?>
								</td>
								<td class="income_amount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($total_amount,2);?></td>
								<td class="income_amount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($paid_amount,2);?></td>
								<td class="income_amount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($due_amount,2);?></td>
								<td class="status"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->invoice_date);?></td>
								<td class="paymentdate">
									<?php 
									echo "<span class='btn btn-success btn-xs'>";							
									echo  __("$status","gym_mgt");
									echo "</span>";
									?>
								</td>								
								<?php
								if (($retrieved_data->total_amount > '0' ) && ($retrieved_data->payment_status == 'Unpaid' || $retrieved_data->payment_status == 'Partially Paid' || $retrieved_data->payment_status == 'Part Paid' || $retrieved_data->payment_status == 'Not Paid') )
								{  
								?>
									<td class="action">							
									<a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->invoice_id; ?>" member_id="<?php echo $retrieved_data->supplier_name; ?>" due_amount="<?php echo str_replace(",","",number_format($due_amount,2)); ?>"
									view_type="income_payment" ><?php _e('Pay','gym_mgt');?></a>
									<a  href="#" class="show-invoice-popup btn btn-default" <?php if($retrieved_data->invoice_label=='Sell Product'){ ?> idtest="<?php $id=$obj_payment->MJ_gmgt_get_sell_id_by_income_id($retrieved_data->invoice_id);  echo $id;?>"  invoice_type="sell_invoice" <?php }elseif($retrieved_data->invoice_label=='Fees Payment'){ ?> idtest="<?php $id=$obj_payment->MJ_gmgt_get_fees_id_by_income_id($retrieved_data->invoice_id);  echo $id;?>"  invoice_type="membership_invoice" <?php }else{?> idtest="<?php echo $retrieved_data->invoice_id; ?>" invoice_type="income" <?php } ?> >
									<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
									<?php
									if(!empty($retrieved_data->invoice_no))
									{
										if(!($retrieved_data->invoice_label=='Fees Payment' || $retrieved_data->invoice_label=='Sell Product'))
										{
										?>	
											<a href="?page=gmgt_payment&tab=addincome&action=edit&income_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
										<?php
										}
									}
									?>
									<a href="?page=gmgt_payment&tab=incomelist&action=delete&income_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
									<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
									</td>
								<?php 
								}  
								if ($retrieved_data->total_amount == '0' || $retrieved_data->payment_status == 'Fully Paid' || $retrieved_data->payment_status == 'Paid') 
								{
								?>
								<td class="action">
									<a  href="#" class="show-invoice-popup btn btn-default" <?php if($retrieved_data->invoice_label=='Sell Product'){ ?> idtest="<?php $id=$obj_payment->MJ_gmgt_get_sell_id_by_income_id($retrieved_data->invoice_id);  echo $id;?>"  invoice_type="sell_invoice" <?php }elseif($retrieved_data->invoice_label=='Fees Payment'){ ?> idtest="<?php $id=$obj_payment->MJ_gmgt_get_fees_id_by_income_id($retrieved_data->invoice_id);  echo $id;?>"  invoice_type="membership_invoice" <?php }else{?> idtest="<?php echo $retrieved_data->invoice_id; ?>" invoice_type="income" <?php } ?> >
									<i class="fa fa-eye"></i> <?php _e('View Invoice', 'gym_mgt');?></a>
									<?php
									if(!empty($retrieved_data->invoice_no))
									{
										if(!($retrieved_data->invoice_label=='Fees Payment' || $retrieved_data->invoice_label=='Sell Product'))
										{
										?>	
										<a href="?page=gmgt_payment&tab=addincome&action=edit&income_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
										<?php
										}
									}
									?>
									<a href="?page=gmgt_payment&tab=incomelist&action=delete&income_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
									<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
								</td>
							</tr>
						<?php 
							} 
						}			
					?>     
					</tbody>        
				</table><!--INCOME LIST TABLE END-->
				<div class="print-button pull-left">
	                <input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected_income" class="btn btn-danger delete_selected "/>
                </div>
				</form><!--INCOME LIST FORM END-->
            </div><!--TABLE RESPONSIVE DIV END-->
        </div>	<!--PANEL BODY DIV END-->	
<?php 
} 
?>