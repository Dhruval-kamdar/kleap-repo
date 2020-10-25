<?php 
$role='staff_member';
?>
<script type="text/javascript">
$(document).ready(function() {
	 $('#staff_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		
		$('#specialization').multiselect(
		{
			nonSelectedText :'<?php _e('Select specialization','gym_mgt');?>',
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			filterPlaceholder: '<?php _e('Search for specialization...','gym_mgt');?>'
		}); 	
	   $(".specialization").click(function()
		{	
			checked = $(".multiselect_validation_specialization .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast one specialization','gym_mgt');?>");
			  return false;
			}	
		}); 
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		$('#birth_date').datepicker(
		{
			endDate: '+0d',
			autoclose: true
		}); 
} );
</script>
<?php 	
if($active_tab == 'add_staffmember')
{
	$staff_member_id=0;
	$edit=0;
	if(isset($_REQUEST['staff_member_id']))
		$staff_member_id=$_REQUEST['staff_member_id'];
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$user_info = get_userdata($staff_member_id);
	}?>
	<style>
	.btn-group-vertical
	{
		position: relative !important;
		display: inline-block !important;
	}
	</style>
    <div class="panel-body"><!--PANEL BODY DIV START-->
		<form name="staff_form" action="" method="post" class="form-horizontal" id="staff_form"><!--Staff MEMBER FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<input type="hidden" name="user_id" value="<?php echo $staff_member_id;?>"  />
			<div class="header col-sm-12">	
				<h3><?php _e('Personal Information','gym_mgt');?></h3>
			</div>
			<div class="col-sm-6 padding_left_right_0">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="first_name"><?php _e('First Name','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name" tabindex="1">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="middle_name"><?php _e('Middle Name','gym_mgt');?></label>
					<div class="col-sm-7">
						<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name" tabindex="2">
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_staff_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-4 control-label" for="last_name"><?php _e('Last Name','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name" tabindex="3">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="gender"><?php _e('Gender','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
					<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
						<label class="radio-inline">
						 <input type="radio" value="male" class="tog validate[required] radio_class_member" name="gender"  <?php  checked( 'male', $genderval);  ?> tabindex="4" /><?php _e('Male','gym_mgt');?>
						</label>
						<label class="radio-inline">
						  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','gym_mgt');?> 
						</label>
					</div>
				</div>
			</div>
			<div class="col-sm-6 padding_left_right_0">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="birth_date"><?php _e('Date of birth','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="birth_date" class="form-control validate[required]" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="birth_date" 
						value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($user_info->birth_date);}
						elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" tabindex="5" readonly>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label" for="role_type"><?php _e('Assign Role','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-4">
						<select  class="form-control validate[required]" name="role_type" id="role_type" tabindex="6">
							<option value=""><?php _e('Select Role','gym_mgt');?></option>
							<?php 
							
							if(isset($_REQUEST['role_type']))
								$category =$_REQUEST['role_type'];  
							elseif($edit)
								$category =$user_info->role_type;
							else 
								$category = "";
							
							$role_type=MJ_gmgt_get_all_category('role_type');
							if(!empty($role_type))
							{
								foreach ($role_type as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							}
							?>
						</select>
					</div>
					<div class="col-sm-3 add_category_padding_0"><button id="addremove" model="role_type" tabindex="7"><?php _e('Add Or Remove','gym_mgt');?></button></div>
				</div>					
				<div class="form-group">
					<label class="col-sm-4 control-label" for="specialization"><?php _e('Specialization','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-4 multiselect_validation_specialization specialization_css1">
						<select class="form-control"  name="activity_category[]" id="specialization"  multiple="multiple" tabindex="8" >
						<?php 
							if($edit)
								$category =explode(',',$user_info->activity_category);
							elseif(isset($_REQUEST['activity_category']))
								$category =$_REQUEST['activity_category'];  
							else 
								$category = array();
							
							$activity_category=MJ_gmgt_get_all_category('activity_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									$selected = "";
									if(in_array($retrive_data->ID,$category))
										$selected = "selected";
									echo '<option value="'.$retrive_data->ID.'"'.$selected.'>'.$retrive_data->post_title.'</option>';
								}
							}
							?>
						</select>								
					</div>	
					<div class="col-sm-3 add_category_padding_0">
						<button id="addremove" model="activity_category" tabindex="9"><?php _e('Add Or Remove','gym_mgt');?></button>
					</div>
				</div>
			</div>
			<div class="header  col-sm-12">	<hr>
				<h3><?php _e('Contact Information','gym_mgt');?></h3>
			</div>
			<div class="col-sm-6 padding_left_right_0">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="address"><?php _e('Home Town Address','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150"  name="address" 
						value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>" tabindex="10">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="city_name"><?php _e('City','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" type="text" maxlength="50"  name="city_name" 
						value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>" tabindex="11">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="state_name"><?php _e('State','gym_mgt');?></label>
					<div class="col-sm-7">
						<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
						value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>" tabindex="12">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="zip_code"><?php _e('Zip Code','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="zip_code" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" 
						value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>" tabindex="13">
					</div>
				</div>
			</div>
			<div class="col-sm-6 padding_left_right_0">				
				<div class="form-group">
					<label class="col-sm-4 control-label " for="mobile"><?php _e('Mobile Number','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-2">
					<input type="text" readonly value="+<?php echo MJ_gmgt_get_countery_phonecode(get_option( 'gmgt_contry' ));?>"  class="form-control" name="phonecode">
					</div>
					<div class="col-sm-5">
						<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input phone_validation" type="text" name="mobile" minlength="6" maxlength="15"
						value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" tabindex="15">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label " for="phone"><?php _e('Phone','gym_mgt');?></label>
					<div class="col-sm-7">
						<input id="phone" class="form-control validate[custom[phone_number]] text-input phone_validation" minlength="6" maxlength="15"  type="text"  name="phone" 
						value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" tabindex="16">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label " for="email"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
					<input type="hidden"  name="hidden_email" value="<?php if($edit){ echo $user_info->user_email; } ?>">
						<input id="email" class="form-control validate[required,custom[email]] text-input" type="text" maxlength="100"  name="email" 
						value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>" tabindex="17">
					</div>
				</div>
			</div>
			<div class="header  col-sm-12">	<hr>
				<h3><?php _e('Login Information','gym_mgt');?></h3>
			</div>
			<div class="col-sm-6 padding_left_right_0">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="username"><?php _e('User Name','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-7">
						<input id="username" class="form-control validate[required,custom[username_validation]] space_validation" type="text"  name="username" maxlength="50" 
						value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?> tabindex="18">
					</div>
				</div>
			</div>
			<div class="col-sm-6 padding_left_right_0">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="password"><?php _e('Password','gym_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
					<div class="col-sm-7">
						<input id="password" class="form-control space_validation <?php if(!$edit) echo 'validate[required]';?> " type="password" minlength="8" maxlength="12"  name="password" value="" tabindex="19">
					</div>
				</div>
			</div>
			<div class="col-sm-6 padding_left_right_0">	
				<div class="form-group">
					<label class="col-sm-4 control-label" for="photo"><?php _e('Image','gym_mgt');?></label>
					<div class="col-sm-4">
						<input type="text" id="gmgt_user_avatar_url" class="form-control" name="gmgt_user_avatar"  readonly
						value="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar );elseif(isset($_POST['gmgt_user_avatar'])) echo $_POST['gmgt_user_avatar']; ?>" />
					</div>	
					<div class="col-sm-3">
							<input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" tabindex="20"/>		
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-offset-4 col-sm-7">
						<div id="upload_user_avatar_preview" >
						 <?php if($edit) 
							{
								if($user_info->gmgt_user_avatar == "")
								{?>
								<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
								<?php }
								else {
									?>
								<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar ); ?>" />
								<?php 
								}
							}
							else {
								?>
								<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
								<?php 
							}?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Add Staff','gym_mgt');}?>" name="save_staff"  class="btn btn-success specialization"/>
			</div>
		</form><!--Staff MEMBER FORM END-->
	</div><!--PANEL BODY DIV END-->        
<?php 
}
?>