<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	var member_limit='';
	$('#membership_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$('#activity_id').multiselect(
	{
		nonSelectedText :'<?php _e('Select Activity','gym_mgt');?>',
		includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: '<?php _e('Search for activity...','gym_mgt');?>'
	}); 
	$('#activity_category').multiselect(
	{
		nonSelectedText :'<?php _e('Select Activity Category','gym_mgt');?>',
		includeSelectAllOption: true,
        enableFiltering: true,
		allowClear: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: '<?php _e('Search for activity category...','gym_mgt');?>'
	}); 
	$('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','gym_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','gym_mgt'); ?>'
		 });
});
</script>
<?php 	
if($active_tab == 'addmembership')
{
	$membership_id=0;
	if(isset($_REQUEST['membership_id']))
		$membership_id=$_REQUEST['membership_id'];
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_membership->MJ_gmgt_get_single_membership($membership_id);			
	}  
	?>		
    <div class="panel-body"><!--PANEL BODY DIV START-->
        <form name="membership_form" action="" method="post" class="form-horizontal" id="membership_form"><!--MEMBERSHIP FORM START-->
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="membership_id" class="membership_id_activity" value="<?php echo $membership_id;?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="membership_name"><?php _e('Membership Name','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="membership_name" class="form-control validate[required,custom[popup_category_validation]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $result->membership_label;}elseif(isset($_POST['membership_name'])) echo $_POST['membership_name'];?>" name="membership_name">
				</div>
			</div>
			<!--nonce-->
			<?php wp_nonce_field( 'save_membership_nonce' ); ?>
			<!--nonce-->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="membership_category"><?php _e('Membership Category','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">			
					<select class="form-control validate[required]"  name="membership_category" id="membership_category">
					<option value=""><?php _e('Select Membership Category','gym_mgt');?></option>
					<?php 				
					if(isset($_REQUEST['membership_category']))
						$category =$_REQUEST['membership_category'];  
					elseif($edit)
						$category =$result->membership_cat_id;
					else 
						$category = "";
					
					$mambership_category=MJ_gmgt_get_all_category('membership_category');
					if(!empty($mambership_category))
					{
						foreach ($mambership_category as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID .'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title .'</option>';
						}
					}
					?>				
					</select>
				</div>
				<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="membership_category"><?php _e('Add Or Remove','gym_mgt');?></button></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="membership_period"><?php _e('Membership Period(Days)','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="membership_period" class="form-control validate[required,custom[number]] text-input" min="0" type="number" onKeyPress="if(this.value.length==3) return false;" value="<?php if($edit){ echo $result->membership_length_id;}elseif(isset($_POST['membership_period'])) echo $_POST['membership_period'];?>" name="membership_period" placeholder="<?php _e('Enter Total Number of Days','gym_mgt');?>">
				
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label" for="member_limit"><?php _e('Members Limit','gym_mgt');?></label>
				<div class="col-sm-8">
				<?php $limitval = "unlimited"; if($edit){ $limitval=$result->membership_class_limit; }elseif(isset($_POST['gender'])) {$limitval=$_POST['gender'];}?>
					<label class="radio-inline">
					 <input type="radio" value="limited" class="tog radio_class_member"  name="member_limit"  <?php  checked( 'limited', $limitval);  ?>/><?php _e('limited','gym_mgt');?>
					</label>
					<label class="radio-inline">
					  <input type="radio" value="unlimited" class="tog radio_class_member"  name="member_limit"  <?php  checked( 'unlimited', $limitval);  ?>/><?php _e('unlimited','gym_mgt');?> 
					</label>
				</div>
			</div>
			
			<?php if($edit){
					if($result->membership_class_limit!='unlimited'){ ?>
							<div id="on_of_member_box">
						<div class="form-group ">
						<label class="col-sm-2 control-label" for="on_of_member"><?php _e('No Of Member','gym_mgt');?></label>
						<div class="col-sm-8">
							<input id="on_of_member" class="form-control text-input" type="number" min="0" onkeypress="if(this.value.length==4) return false;"  value="<?php print $result->on_of_member ?>" name="on_of_member">
						</div>
						</div>				
					</div>
					<?php } ?>
				
			<?php 			
			} 		
			?>
			<div id="member_limit"></div>		
			<div class="form-group">
				<label class="col-sm-2 control-label " for="classis_limit"><?php _e('Class Limit','gym_mgt');?></label>
				<div class="col-sm-8">
				<?php $limitvals = "unlimited"; if($edit){ $limitvals=$result->classis_limit; }elseif(isset($_POST['gender'])) {$limitvals=$_POST['gender'];}?>
					<label class="radio-inline">
					 <input type="radio" value="limited" class="classis_limit" style="margin-top: 2px;" name="classis_limit"  <?php  checked( 'limited', $limitvals);  ?>/><?php _e('limited','gym_mgt');?>
					</label>
					<label class="radio-inline">
					  <input type="radio" value="unlimited" class="classis_limit validate[required]" style="margin-top: 2px;" name="classis_limit"  <?php  checked( 'unlimited', $limitvals);  ?>/><?php _e('unlimited','gym_mgt');?> 
					</label>
				</div>
			</div>
			<div id="classis_limit"></div>	
			<?php
			if($edit)
			{ 
				if($result->classis_limit!='unlimited')
				{ 
				?>
				<div id="on_of_classis_box">
					<div class="form-group ">
					<label class="col-sm-2 control-label radio_class_member" for="on_of_classis"><?php _e('No Of Class','gym_mgt');?></label>
					<div class="col-sm-8">
						<input id="on_of_classis" class="form-control  text-input" type="number" min="0" onkeypress="if(this.value.length==4) return false;" value="<?php print $result->on_of_classis ?>" name="on_of_classis">
					</div>
					</div>				
				</div>
				<?php
				} 
			} 
			?>	

			<div class="form-group">
				<label class="col-sm-2 control-label" for="installment_amount"><?php _e('Membership Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="membership_amount" class="form-control text-input validate[required]" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01"  value="<?php if($edit){ echo $result->membership_amount;}elseif(isset($_POST['membership_amount'])) echo $_POST['membership_amount'];?>" name="membership_amount" placeholder="<?php _e('Amount','gym_mgt');?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="installment_plan"><?php _e('Installment Plan','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
				<div class="col-sm-2">
					<input id="installment_amount" class="form-control text-input validate[required]" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" type="number"  value="<?php if($edit){ echo $result->installment_amount;}elseif(isset($_POST['installment_amount'])) echo $_POST['installment_amount'];?>" name="installment_amount" placeholder="<?php _e('Amount','gym_mgt');?>">
				</div>
				<div class="col-sm-6">
				
					<select class="form-control" name="installment_plan" id="installment_plan">
					<option value=""><?php _e('Select Installment Plan','gym_mgt');?></option>
					<?php 
					
					if(isset($_REQUEST['installment_plan']))
						$category =$_REQUEST['installment_plan'];  
					elseif($edit)
						$category =$result->install_plan_id;
					else 
						
						$category = "";
					
					$installment_plan=MJ_gmgt_get_all_category('installment_plan');
					if(!empty($installment_plan))
					{
						foreach ($installment_plan as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
						}
					}
					?>
					
					</select>
				</div>
				<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="installment_plan"><?php _e('Add Or Remove','gym_mgt');?></button></div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="signup_fee"><?php _e('Signup Fee','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="signup_fee" class="form-control text-input" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" type="number" value="<?php if($edit){ echo $result->signup_fee;}elseif(isset($_POST['membership_name'])) echo $_POST['membership_name'];?>" name="signup_fee" placeholder="<?php _e('Amount','gym_mgt');?>" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=""><?php _e('Tax','gym_mgt');?>(%)</label>
				<div class="col-sm-3">
					<select  class="form-control tax_charge" name="tax[]" multiple="multiple">					
						<?php					
						if($edit)
						{
							$tax_id=explode(',',$result->tax);
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
				<label class="col-sm-2 control-label" for="activity_category"><?php _e('Select Activity Category','gym_mgt');?></label>
				<div class="col-sm-8">
					<?php
					if($edit)
					{
					?>
						<input type="hidden" class="action_membership" value="edit_membership">
					<?php
					}
					else
					{
					?>
						<input type="hidden" class="action_membership" value="add_membership">
					<?php
					}
					?>
					<select class="form-control activity_category_list" name="activity_cat_id[]" multiple="multiple" id="activity_category"><?php 					
						$activity_category=MJ_gmgt_get_all_category('activity_category');
						if($edit)
						{
							$activity_category_array=explode(',',$result->activity_cat_id);
						}
						else
						{	
							$activity_category_array[]='';
						}
						
						if(!empty($activity_category))
						{
							foreach ($activity_category as $retrive_data)
							{		
								$selected = "";
								if(in_array($retrive_data->ID,$activity_category_array))
									$selected = "selected";
								?>
									<option value="<?php echo $retrive_data->ID;?>" <?php echo $selected; ?>><?php echo $retrive_data->post_title;?></option>
								<?php
							}
						}
					?>				
					</select>
				</div>		
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="signup_fee"><?php _e('Select Activity','gym_mgt');?></label>
				<div class="col-sm-8">
					<?php 
						$activitydata=$obj_activity->MJ_gmgt_get_all_activity_by_activity_category($activity_category_array); ?>
					<select name="activity_id[]" id="activity_id" multiple="multiple" class="activity_list_from_category_type">		 <?php 
						$activity_array = $obj_activity->MJ_gmgt_get_membership_activity($membership_id);
						if(!empty($activitydata))
						{
							foreach($activitydata as $activity)
							{
								?>
								<option value="<?php echo $activity->activity_id;?>" <?php if(in_array($activity->activity_id,$activity_array)) echo "selected";?>><?php echo $activity->activity_title;?></option>
							<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="signup_fee"><?php _e('Membership Description','gym_mgt');?></label>
				<div class="col-md-8">
					<?php wp_editor(isset($result->membership_description)?stripslashes($result->membership_description) : '','description'); ?>
				</div>
			</div>		
			<div class="form-group">
				<label class="col-sm-2 control-label" for="gmgt_membershipimage"><?php _e('Membership Image','gym_mgt');?></label>
				<div class="col-sm-8">			
					<input type="text" id="gmgt_gym_background_image" name="gmgt_membershipimage" readonly
					value="<?php if($edit){ echo $result->gmgt_membershipimage;}elseif(isset($_POST['gmgt_membershipimage'])) echo $_POST['gmgt_membershipimage'];?>" />	
					 <input id="upload_image_button" type="button" class="button upload_user_cover_button" value="<?php _e( 'Upload Cover Image', 'gym_mgt' ); ?>" />
					 <span class="description"><?php _e('Upload Membership Image', 'gym_mgt' ); ?></span>
					<div id="upload_gym_cover_preview">
						<img class="image_preview_css" src="<?php if($edit  && $result->gmgt_membershipimage != ''){ echo $result->	gmgt_membershipimage;}elseif(isset($_POST['gmgt_membershipimage'])) echo $_POST['gmgt_membershipimage']; else echo get_option( 'gmgt_system_logo' );?>" />
					</div>
				</div>
			</div>
			
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save Membership','gym_mgt'); }else{ _e('Add Membership','gym_mgt');}?>" name="save_membership" class="btn btn-success"/>
			</div>
        </form><!--MEMBERSHIP FORM END-->
    </div>  <!--PANEL BODY DIV END-->
<?php 
} 
?>
