<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{	
	$('#payment_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		    var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php  echo get_option('gmgt_datepicker_format'); ?>";
             $('#begin_date').datepicker({
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
	$('.sl').select2(
	{
        placeholder:'Select'   
    })
} );
</script>
<?php 	
if($active_tab == 'addpayment')
{
  $mp_id=0;
  if(isset($_REQUEST['mp_id']))
		$mp_id=$_REQUEST['mp_id'];
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
	  $edit=1;
	  $result = $obj_membership_payment->MJ_gmgt_get_single_membership_payment($mp_id);
	} 
	?>
    <div class="panel-body"><!--PANEL BODY DIV START-->
		<form name="payment_form" action="" method="post" class="form-horizontal" id="payment_form"><!--PAYMENT FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="mp_id" value="<?php if($edit){ echo $mp_id; }?>"  />
			<input type="hidden" name="created_by" value="<?php echo get_current_user_id();?>"  />
			<input type="hidden" name="paid_amount" value="<?php if($edit){ echo $result->paid_amount; }?>"  />
			<input type="hidden" name="invoice_no" value="<?php if($edit){ echo $result->invoice_no; }?>"  />
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
			<div class="form-group">
				<label class="col-sm-2 control-label" for="membership"><?php _e('Membership','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php 	$obj_membership=new MJ_Gmgtmembership;
					$membershipdata=$obj_membership->MJ_gmgt_get_all_membership();?>
					<?php if($edit){ $membership_id=$result->membership_id; }elseif(isset($_POST['membership_id'])){$membership_id=$_POST['membership_id'];}else{$membership_id='';}?>
					<select name="membership_id" class="form-control payment_membership_detail validate[required] " id="membership_id">
					<option value=""><?php  _e('Select Membership ','gym_mgt');?></option>
					<?php 
					
						if(!empty($membershipdata))
						 {
							foreach ($membershipdata as $membership){
						
							
							echo '<option value='.$membership->membership_id.' '.selected($membership_id,$membership->membership_id).'>'.$membership->membership_label.'</option>';
						}
						}
						?>
					</select>
				</div>
			</div>
			<!--nonce-->
				<?php wp_nonce_field( 'save_membership_payment_nonce' ); ?>
			<!--nonce-->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="total_amount"><?php _e('Total Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="total_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php if($edit){ echo $result->membership_amount;}?>" name="membership_amount" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="begin_date"><?php _e('Membership Valid From','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input id="begin_date" class="form-control validate[required]" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="start_date" 
					value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->start_date);}
					elseif(isset($_POST['start_date'])) 
					echo $_POST['start_date'];?>" readonly>
				</div>
				<div class="col-sm-1 text-center to_label_css">
					<?php _e('To','gym_mgt');?>
				</div>
				<div class="col-sm-4">
					<input id="end_date" class="form-control validate[required]" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text"  name="end_date" 
					value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->end_date);}elseif(isset($_POST['end_date'])) echo $_POST['end_date'];?>" readonly>
				</div>
			</div>		
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_membership_payment" class="btn btn-success"/>
			</div>
		</form><!--PAYMENT FORM END-->
    </div><!--PANEL BODY DIV END-->
<?php 
}
?>