<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#payment_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		    var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format'); ?>";
            $('#due_date').datepicker({
	        startDate: date,
            autoclose: true
           });
		$(".display-members").select2();
} );
</script>
<?php 	
if($active_tab == 'addpayment')
{
	$payment_id=0;
	if(isset($_REQUEST['payment_id']))
		$payment_id=$_REQUEST['payment_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_payment->MJ_gmgt_get_single_payment($payment_id);
		}
		?>		
		<div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="payment_form" action="" method="post" class="form-horizontal" id="payment_form"><!--PAYMENT FORM START-->
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="payment_id" value="<?php echo $payment_id;?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
					<div class="col-sm-8">
						<?php if($edit){ $member_id=$result->member_id; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
						<select id="member_list" class="display-members member-select2" required="true" name="member_id">
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
				<!--nonce-->
				<?php wp_nonce_field( 'save_product_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="title"><?php _e('Title','gym_mgt');?></label>
					<div class="col-sm-8">
						<input id="payment_title" class="form-control validate[custom[onlyLetterSp]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->title;}elseif(isset($_POST['payment_title'])) echo $_POST['payment_title'];?>" name="payment_title">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="due_date"><?php _e('Due Date','gym_mgt');?></label>
					<div class="col-sm-8">
						<input id="due_date" class="form-control" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text"  name="due_date" 
						value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->due_date);}elseif(isset($_POST['due_date']))
						{ echo $_POST['due_date'];}else{ echo MJ_gmgt_getdate_in_input_box(date('Y-m-d')); }?>" readonly>
					</div>
				</div>		
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discount"><?php _e('Discount Amount','gym_mgt');?> </label>
					<div class="col-sm-8">
						<input id="discount" class="form-control" type="number" min="0" onkeypress="if(this.value.length==6) return false;"  value="<?php if($edit){ echo $result->discount;}?>" name="discount">
					</div>
					<div class="col-sm-1">
						<span style="font-size: 20px;"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="total_amount"><?php _e('Total Amount','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="total_amount" class="form-control validate[required]" type="number" min="0" onkeypress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->total_amount;}?>" name="total_amount">
					</div>
					<div class="col-sm-1">
						<span style="font-size: 20px;"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="payment_status" id="payment_status" class="form-control">
							<option value="<?php echo __('Paid','gym_mgt');?>"
							<?php if($edit)selected('Paid',$result->payment_status);?> class="validate[required]"><?php _e('Paid','gym_mgt');?></option>
							<option value="<?php echo __('Part Paid','gym_mgt');?>"
							<?php if($edit)selected('Part Paid',$result->payment_status);?> class="validate[required]"><?php _e('Part Paid','gym_mgt');?></option>
							<option value="<?php echo __('Unpaid','gym_mgt');?>"
							<?php if($edit)selected('Unpaid',$result->payment_status);?> class="validate[required]"><?php _e('Unpaid','gym_mgt');?></option>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="description"><?php _e('Description','gym_mgt');?></label>
					<div class="col-sm-8">
						<textarea name="description" id="description" class="form-control" maxlength="150"> <?php if($edit){ echo $result->description;}?></textarea>
					</div>
				</div>				
				<div class="col-sm-offset-2 col-sm-8">					
					<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_product" class="btn btn-success"/>
				</div>
			</form><!--PAYMENT FORM END-->
		</div><!--PANEL BODY DIV END-->
<?php 
}
?>