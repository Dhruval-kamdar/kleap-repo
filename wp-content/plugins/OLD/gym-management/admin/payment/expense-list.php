<?php 
$obj_payment= new MJ_Gmgtpayment();
if($active_tab == 'expenselist')
{
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		jQuery('#tblexpence').DataTable({
			"responsive": true,
			 "order": [[ 2, "Desc" ]],
			 "aoColumns":[
						  {"bSortable": false},
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},                                   
						  {"bSortable": false}
					   ],
				language:<?php echo MJ_gmgt_datatable_multi_language();?>		   
			});
			// check unchecked all check box //
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
		   // check unchecked all check box //
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
		  <form name="wcwm_report" action="" method="post"><!--EXPENSE LIST FORM START-->
			<table id="tblexpence" class="display" cellspacing="0" width="100%"><!--EXPENSE LIST TABLE START-->
				<thead>
					<tr>
					   <th><input type="checkbox" class="select_all"></th>
						<th> <?php _e( 'Supplier Name', 'gym_mgt' ) ;?></th>
						<th> <?php _e( 'Amount', 'gym_mgt' ) ;?></th>
						<th> <?php _e( 'Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th> </th>
						<th> <?php _e( 'Supplier Name', 'gym_mgt' ) ;?></th>
						<th> <?php _e( 'Amount', 'gym_mgt' ) ;?></th>
						<th> <?php _e( 'Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
					</tr>
				</tfoot>
				<tbody>
				 <?php 
					foreach ($obj_payment->MJ_gmgt_get_all_expense_data() as $retrieved_data)
					{ 
						$all_entry=json_decode($retrieved_data->entry);
						$total_amount=0;
						foreach($all_entry as $entry)
						{
							$total_amount+=$entry->amount;
						}
						 ?>
						<tr>
						  <td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->invoice_id; ?>"></td>
							<td class="party_name"><?php echo $retrieved_data->supplier_name;?></td>
							<td class="income_amount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($total_amount,2);?></td>
							<td class="status"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->invoice_date);?></td>
							<td class="action">
							<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->invoice_id; ?>" invoice_type="expense">
							<i class="fa fa-eye"></i> <?php _e('View Expense', 'gym_mgt');?></a>
							<a href="?page=gmgt_payment&tab=addexpense&action=edit&expense_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
							<a href="?page=gmgt_payment&tab=expenselist&action=delete&expense_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-danger" 
							onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
							<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
							</td>
						</tr>
						<?php
					} 
				   ?>
				</tbody>
			</table><!--EXPENSE LIST TABLE END-->
			<div class="print-button pull-left">
				<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected_expense" class="btn btn-danger delete_selected "/>
			</div>
			</form><!--EXPENSE LIST FORM END-->
		</div><!--TABLE RESPONSIVE DIV END-->
	</div><!--PANEL BODY DIV END-->
<?php  
} 
?>