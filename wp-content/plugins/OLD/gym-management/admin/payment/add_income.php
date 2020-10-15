<?php 
$obj_payment= new MJ_Gmgtpayment();
?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#income_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','gym_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','gym_mgt'); ?>'
		 });
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
	$(".display-members").select2();
} );
</script>
<?php 	
if($active_tab == 'addincome')
{
	$income_id=0;
	if(isset($_REQUEST['income_id']))
		$income_id=$_REQUEST['income_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
			
			$edit=1;
			$result = $obj_payment->MJ_gmgt_get_income_data($income_id);
			
		}
		?>
        <div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="income_form" action="" method="post" class="form-horizontal" id="income_form"><!--INCOME FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="income_id" value="<?php if($edit){ echo $income_id; }?>">
				<input type="hidden" name="invoice_type" value="income">
				<input type="hidden" name="invoice_no" value="<?php if($edit){ echo $result->invoice_no; }?>">
				<input type="hidden" name="paid_amount" value="<?php if($edit){ echo $result->paid_amount; } ?>">
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
					<div class="col-sm-8">
						<?php if($edit){ $member_id=$result->supplier_name; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
						<select id="member_list" class="display-members" required="true" name="supplier_name">
						<option value=""><?php _e('Select Member','gym_mgt');?></option>
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
					<label class="col-sm-2 control-label" for="invoice_label"><?php _e('Income label','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="invoice_label" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->invoice_label;}elseif(isset($_POST['invoice_label'])) echo $_POST['payment_title'];?>" name="invoice_label">
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_income_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="invoice_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" class="form-control " type="text"  
						value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->invoice_date);}
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
						if(isset($_POST['income_entry'])){
							
							$all_data=$obj_invoice->MJ_gmgt_get_entry_records($_POST);
							$all_entry=json_decode($all_data);
						}
						
							
					}
					if(!empty($all_entry))
					{
						foreach($all_entry as $entry)
						{
						?>
						<div class="income_entry_div">								
							<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','gym_mgt');?><span class="require-field"> * </span></label>
							<div class="col-sm-2">
								<input id="income_amount" class="form-control validate[required] text-input" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]" placeholder="<?php _e('Income Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)">
							</div>
							<div class="col-sm-4">
								<input id="income_entry" class="form-control validate[required,custom[popup_category_validation]] text-input onlyletter_space_validation1" type="text" maxlength="50" value="<?php echo $entry->entry;?>" name="income_entry[]">
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
						<div class="income_entry_div">								
							<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','gym_mgt');?><span class="require-field">* </span> </label>
							<div class="col-sm-2">
								<input id="income_amount" class="form-control validate[required] text-input" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Income Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)" >
							</div>
							<div class="col-sm-4">
								<input id="income_entry" class="form-control validate[required,custom[popup_category_validation]] text-input onlyletter_space_validation1"   maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Income Entry Label','gym_mgt');?>">
							</div>						
							<div class="col-sm-2">
							<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
							<i class="entypo-trash"><?php _e('Delete','gym_mgt');?></i>
							</button>
							</div>
							</div>	
						</div>
				<?php } ?>				
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="income_entry"></label>
					<div class="col-sm-3">					
						<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Income Entry','gym_mgt'); ?>
						</button>
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
				<div class="form-group">
					<label class="col-sm-2 control-label" for="quentity"><?php _e('Discount Amount ','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field"></span></label>
					<div class="col-sm-8">
						<input id="group_name" class="form-control text-input decimal_number" step="0.01" type="number" onKeyPress="if(this.value.length==8) return false;"  min="0" value="<?php if($edit){ echo $result->discount;}elseif(isset($_POST['discount'])) echo $_POST['discount'];?>" name="discount">
					</div>
				</div>		
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save Income','gym_mgt'); }else{ _e('Add Income','gym_mgt');}?>" name="save_income" class="btn btn-success"/>
				</div>
			</form><!--INCOME FORM END-->
        </div><!--PANEL BODY DIV END-->
		<script>
			// CREATING BLANK INVOICE ENTRY
			var blank_income_entry ='';
			$(document).ready(function() { 
				blank_income_entry = $('.income_entry_div').html();			
			}); 
			//ADD INCOME ENTRY
			function add_entry()
			{
				$(".income_entry_div").append(blank_income_entry);			
			}
			
			// REMOVING INVOICE ENTRY
			function deleteParentElement(n)
			{
				 alert("<?php _e('Do you really want to delete this record','gym_mgt');?>");
					n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
			}
		 </script> 
<?php 
}
?>