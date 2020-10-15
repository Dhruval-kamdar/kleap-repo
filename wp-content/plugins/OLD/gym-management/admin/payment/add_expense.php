<?php 
$obj_payment= new MJ_Gmgtpayment();?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#expense_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	var date = new Date();
	date.setDate(date.getDate()-0);
	$.fn.datepicker.defaults.format =" <?php  echo get_option('gmgt_datepicker_format'); ?>";
	 $('#invoice_date').datepicker({
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
} );
</script>
<?php 	
if($active_tab == 'addexpense')
{
	$expense_id=0;
	if(isset($_REQUEST['expense_id']))
		$expense_id=$_REQUEST['expense_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_payment->MJ_gmgt_get_income_data($expense_id);			
		}
		?>
        <div class="panel-body"><!--PANEL BODY DIV STRAT-->
			<form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form"><!--Expense FORM STRAT-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="expense_id" value="<?php echo $expense_id;?>">
				<input type="hidden" name="invoice_type" value="expense">
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="patient"><?php _e('Supplier Name','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="supplier_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->supplier_name;}elseif(isset($_POST['supplier_name'])) echo $_POST['supplier_name'];?>" name="supplier_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="payment_status" id="payment_status" class="form-control validate[required]">
							<option value="<?php echo __('Paid','gym_mgt');?>"
								<?php if($edit)selected('Paid',$result->payment_status);?> ><?php _e('Paid','gym_mgt');?></option>
							<option value="<?php echo __('Part Paid','gym_mgt');?>"
								<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php _e('Part Paid','gym_mgt');?></option>
								<option value="<?php echo __('Unpaid','gym_mgt');?>"
								<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php _e('Unpaid','gym_mgt');?></option>
						</select>
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_expense_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="invoice_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  class="form-control validate[required]" 
						type="text"  value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->invoice_date);}
						elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}
						else{ echo MJ_gmgt_getdate_in_input_box(date("Y-m-d"));}?>" name="invoice_date" readonly>
					</div>
				</div>
				<hr>
				<?php 					
					if($edit)
					{
						$all_entry=json_decode($result->entry);
					}
					else
					{
						if(isset($_POST['income_entry']))
						{
							
							$all_data=$obj_payment->MJ_gmgt_get_entry_records($_POST);
							$all_entry=json_decode($all_data);
						}					
					}
					if(!empty($all_entry))
					{
						foreach($all_entry as $entry)
						{
						?>								
							<div id="expense_entry">								
								<div class="form-group">
								<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','gym_mgt');?><span class="require-field">*</span> </label>
								<div class="col-sm-2">
									<input id="income_amount" class="form-control validate[required] text-input" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]" placeholder="<?php _e('Expense Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)">
								</div>									
								<div class="col-sm-4">
									<input id="income_entry" class="form-control validate[required,custom[popup_category_validation]] text-input onlyletter_space_validation1" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
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
									<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','gym_mgt');?><span class="require-field"> *</span> </label>
									<div class="col-sm-2">
										<input id="income_amount" class="form-control validate[required] text-input" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Expense Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)">
									</div>
									
									<div class="col-sm-4">
										<input id="income_entry" class="form-control validate[required,custom[popup_category_validation]] text-input onlyletter_space_validation1" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Expense Entry Label','gym_mgt');?>">
									</div>
									
									<div class="col-sm-2">
									<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
									<i class="entypo-trash"><?php _e('Delete','gym_mgt');?></i>
									</button>
									</div>
								</div>	
							</div>
				<?php }?>				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="expense_entry"></label>
					<div class="col-sm-3">
						<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Expense Entry','gym_mgt'); ?>
						</button>
					</div>
				</div>
				<hr>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save Expense','gym_mgt'); }else{ _e('Add Expense','gym_mgt');}?>" name="save_expense" class="btn btn-success"/>
				</div>
			</form><!--Expense FORM END-->
        </div><!--PANEL BODY DIV END-->
		<script>
			// CREATING BLANK INVOICE ENTRY
			var blank_income_entry ='';
			$(document).ready(function() 
			{ 
				blank_expense_entry = $('#expense_entry').html();			
			}); 

			function add_entry()
			{
				$("#expense_entry").append(blank_expense_entry);		
			}
			
			// REMOVING INVOICE ENTRY
			function deleteParentElement(n){
				 alert("<?php _e('Do you really want to delete this record','gym_mgt');?>");
				n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
			}
		</script> 
 <?php 
}
?>