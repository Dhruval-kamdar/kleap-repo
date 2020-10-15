<?php ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#membership_id').multiselect(
	{
		nonSelectedText :'<?php _e('Select Membership','gym_mgt');?>',
		includeSelectAllOption: true
	});
	$('#acitivity_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#add_staff_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#membership_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	
	$('#specialization').multiselect(
	{
		nonSelectedText :'<?php _e('Select Specialization','gym_mgt');?>',
		includeSelectAllOption: true
	});
	$('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','gym_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','gym_mgt'); ?>'
		 });
    $(".specialization_submit").click(function()
	{	
		checked = $(".multiselect_validation_specialization .dropdown-menu input:checked").length;
		if(!checked) 
		{
		  alert("<?php _e('Please select atleast one specialization name','gym_mgt');?>");
		  return false;
		}	
    });

		 
	   $(".membership_submit").click(function()
		 {	
		  checked = $(".multiselect_validation .dropdown-menu input:checked").length;
		if(!checked)
		{
		  alert("<?php _e('Please select atleast one membership','gym_mgt');?>");
		  return false;
		}	
		}); 
	 $.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
      $('.birth_date').datepicker({
     endDate: '+0d',
        autoclose: true
   }); 
	//------ADD STAFF MEMBER AJAX----------
	 $('#add_staff_form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this).serialize();
		var valid = $('#add_staff_form').validationEngine('validate');
		if (valid == true) 
		{		
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data){
					if(data!='0')
					{				
						if(data!="")
						{ 
							$('#add_staff_form').trigger("reset");
							$('#staff_id').append(data);
							$('.upload_user_avatar_preview').html('<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">');
							$('.gmgt_user_avatar_url').val('');
						}
						$('.modal').modal('hide');
						$('.show_msg').css('display','none');
					}
					else
					{				
						$('.show_msg').css('display','block');
					}					
				},
				error: function(data){

				}
			})
		}
	});
	
	//------ADD MEMBERSHIP AJAX----------
	$('#membership_form').on('submit', function(e) 
	{
		e.preventDefault();
		var form = $(this).serialize();
		var valid = $('#membership_form').validationEngine('validate');
		if (valid == true)
		{			
			var categCheck_membership = $('#membership_id').multiselect();
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data){
					if(data!='0')
					{
						if(data!="")
						{ 
							$('#membership_form').trigger("reset");
							$('#membership_id').append(data);
							categCheck_membership.multiselect('rebuild');	
						}
						$('.modal').modal('hide');
						$('.show_msg').css('display','none');
					}
					else
					{				
						$('.show_msg').css('display','block');
					}	
				},
				error: function(data){

				}
			})
		}
	});
	
} );
</script>
<?php 	
if($active_tab == 'addactivity')
{
	$activity_id=0;
	if(isset($_REQUEST['activity_id']))
		$activity_id=$_REQUEST['activity_id'];
	$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_activity->MJ_gmgt_get_single_activity($activity_id);
		}
		?>
	<div class="panel-body"><!-- PANEL BODY DIV START-->
		<form name="acitivity_form" action="" method="post" class="form-horizontal" id="acitivity_form"><!-- ACTIVITY FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="activity_id" value="<?php echo $activity_id;?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php _e('Activity Category','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control validate[required] activity_cat_to_staff" name="activity_cat_id" id="activity_category">
					<option value=""><?php _e('Select Activity Category','gym_mgt');?></option>
					<?php 
				if(isset($_REQUEST['activity_cat_id']))
						$category =$_REQUEST['activity_cat_id'];  
					elseif($edit)
						$category =$result->activity_cat_id;
					else 
						$category = "";
					
					$activity_category=MJ_gmgt_get_all_category('activity_category');
					if(!empty($activity_category))
					{
						foreach ($activity_category as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
						}
					}?>
					
					</select>
				</div>
				<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="activity_category"><?php _e('Add Or Remove','gym_mgt');?></button></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_title"><?php _e('Activity Title','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="activity_title" class="form-control validate[required,custom[popup_category_validation]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $result->activity_title;}elseif(isset($_POST['activity_title'])) echo $_POST['activity_title'];?>" name="activity_title">
				</div>
			</div>
			<?php wp_nonce_field( 'save_activity_nonce' ); ?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="staff_name"><?php _e('Assign to Staff Member','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">						
					<select name="staff_id" class="form-control validate[required] category_to_staff_list" id="staff_id">
					<option value=""><?php  _e('Select Staff Member ','gym_mgt');?></option>
					<?php 
					if($edit)
					{	
						$get_staff = array('role' => 'Staff_member');
						$staffdata=get_users($get_staff);	
						$staff_data=$result->activity_assigned_to;
						if(!empty($staffdata))
						{
							foreach($staffdata as $staff)
							{	
								$staff_specialization=explode(',',$staff->activity_category);
								if(in_array($result->activity_cat_id,$staff_specialization))
								{	
									echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
								}
							}
						} 
					}
						?>
					</select>
				</div>
				<div class="col-sm-2">			
				<a href="#" class="btn btn-default" data-toggle="modal" id="add_staff_btn" data-target="#myModal_add_staff_member"> <?php _e('Add Staff Member','gym_mgt');?></a>				
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="membership"><?php _e('Membership','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 multiselect_validation">
					<?php 	$membershipdata=$obj_membership->MJ_gmgt_get_all_membership();?>
					<select name="membership_id[]" class="form-control validate[required]" multiple="multiple" id="membership_id">
					
					<?php $getmembership_array=array();
					if($edit)
						$getmembership_array=$obj_activity->MJ_gmgt_get_activity_membership($activity_id);
					elseif(isset($_REQUEST['membership_id']))
						$getmembership_array[]=$_REQUEST['membership_id'];
						
						if(!empty($membershipdata))
						 {
							foreach ($membershipdata as $membership){?>
								<option value="<?php echo $membership->membership_id;?>" <?php if(in_array($membership->membership_id,$getmembership_array)) echo "selected";?> ><?php echo $membership->membership_label;?></option>
						<?php }
						} ?>
					</select>
				</div>
				<div class="col-sm-2">				
					<a href="#" class="btn btn-default" data-toggle="modal" id="add_membership_btn" data-target="#myModal_add_membership"> <?php _e('Add Membership','gym_mgt');?></a>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_activity" class="btn btn-success membership_submit"/>
			</div>
		</form><!--Activity FORM END-->
	</div><!-- PANEL BODY DIV END-->
<?php 
} 
?>
<!----------ADD STAFF MEMBER POPUP----------->
<div class="modal fade myModal_add_staff_member12" id="myModal_add_staff_member" role="dialog" style="overflow:scroll;"><!-- MAIN MODAL DIV START-->
    <div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
        <div class="modal-content float_and_width"><!-- MODAL CONTENT DIV START-->
			<div class="modal-header float_and_width">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php _e('Add Staff Member','gym_mgt');?></h3>
			</div>
			<div id="message" class="updated below-h2 show_msg">
				<p>
				<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
				</p>
			</div>
			<div class="modal-body float_and_width"><!-- MODAL BODY DIV START-->
			    <form name="staff_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal float_and_width" id="add_staff_form" enctype="multipart/form-data"><!-- STAFF MEMBER FORM START-->	
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="MJ_gmgt_add_staff_member">
					<input type="hidden" name="role" value="staff_member"  />
					<input type="hidden" name="user_id" value=""  />
					<div class="header" style="clear:both;">	
						<h4><?php _e('Personal Information','gym_mgt');?></h4>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="first_name"><?php _e('First Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text" value="" name="first_name" tabindex="1">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="middle_name"><?php _e('Middle Name','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text"  value="" name="middle_name" tabindex="2">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="last_name"><?php _e('Last Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text"  value="" name="last_name" tabindex="3">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="gender"><?php _e('Gender','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
							<?php $genderval = "male";?>
								<label class="radio-inline">
								 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?> tabindex="4" /><?php _e('Male','gym_mgt');?>
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
								<input id="birth_date2" class="form-control validate[required] birth_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text"  name="birth_date" value="" readonly tabindex="5">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="role_type"><?php _e('Assign Role','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-4">
							
								<select class="form-control" name="role_type" id="role_type" tabindex="6">
								<option value=""><?php _e('Select Role','gym_mgt');?></option>
								<?php 
								
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
							<div class="col-sm-4 multiselect_validation_specialization specialization_css">
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
					<div class="header" style="clear:both;">	<hr>
						<h4><?php _e('Contact Information','gym_mgt');?></h4>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="address"><?php _e('Home Town Address','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150"  name="address" 
								value="" tabindex="10">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="city_name"><?php _e('City','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
								value="" tabindex="11">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label " for="email"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
								value="" tabindex="12">
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
								<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input phone_validation"  minlength="6" maxlength="15" type="text"  name="mobile"
								value="" tabindex="13">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label " for="phone"><?php _e('Phone','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="phone" class="form-control validate[custom[phone_number]] text-input phone_validation" minlength="6" maxlength="15" type="text"  name="phone" 
								value="" tabindex="14">
							</div>
						</div>
					</div>					
					<div class="header" style="clear:both;">	<hr>
						<h4><?php _e('Login Information','gym_mgt');?></h4>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="username"><?php _e('User Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="username" class="form-control validate[required,custom[username_validation]] space_validation"  maxlength="50" type="text"  name="username" 
								value="" tabindex="15">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="password"><?php _e('Password','gym_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
							<div class="col-sm-7">
								<input id="password" class="form-control space_validation" type="password" minlength="8" maxlength="12"  name="password" value="" tabindex="16">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="photo"><?php _e('Image','gym_mgt');?></label>
							<div class="col-sm-4">
								<input type="text" id="gmgt_user_avatar_url1" class="form-control gmgt_user_avatar_url" name="gmgt_user_avatar"  readonly
								value="" />
							</div>	
								<div class="col-sm-3">
								 <input id="upload_user_avatar_button1" type="button" class="button upload_user_avatar_button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" tabindex="17" />
							</div>
							<div class="clearfix"></div>							
							<div class="col-sm-offset-4 col-sm-7">
								 <div id="upload_user_avatar_preview1" class="upload_user_avatar_preview">		
									<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0" style="clear:both;">
						<div class="col-sm-offset-4 col-sm-7">
							<input type="submit" value="<?php _e('Add Staff','gym_mgt');?>" name="save_staff" id="add_staff_member" class="btn btn-success specialization_submit" tabindex="18" />
						</div>
					</div>
				</form><!--Staff MEMBER FORM END-->
			</div><!-- MODAL BODY DIV END-->
			<div class="modal-footer float_and_width">
				<div class="col-sm-12 padding_left_right_0">
					<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="19"><?php _e('Close','gym_mgt');?></button>
				</div>
			</div>
		</div><!-- MODAL ContENT DIV END-->
	</div><!-- MODAL DIALOG DIV END-->
</div><!-- MAIN MODAL DIV END-->
 <!----------ADD MEMBERSHIP POPUP------------->
<div class="modal fade" id="myModal_add_membership" role="dialog" style="overflow:scroll;"><!-- MAIN MODAL DIV START-->
	<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
		<div class="modal-content"><!-- MODAL ContENT DIV START-->
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php _e('Add Membership','gym_mgt');?></h3>
			</div>
			<div id="message" class="updated below-h2 show_msg">
				<p>
				<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
				</p>
			</div>
			<div class="modal-body"><!-- MODAL BODY DIV START-->
				<form name="membership_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="membership_form"><!-- MEMBERSHIP FORM START-->
					<input type="hidden" name="action" value="MJ_gmgt_add_ajax_membership">
					<input type="hidden" name="membership_id" value=""  />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="membership_name"><?php _e('Membership Name','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="membership_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="" name="membership_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="membership_category"><?php _e('Membership Category','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">			
							<select class="form-control" name="membership_category" id="membership_category">
							<option value=""><?php _e('Select Membership Category','gym_mgt');?></option>
							<?php 				
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
							<input id="membership_period" class="form-control validate[required,custom[number]] text-input" type="number" onKeyPress="if(this.value.length==3) return false;"  value="<?php if($edit){ echo $result->membership_length_id;}elseif(isset($_POST['membership_period'])) echo $_POST['membership_period'];?>" name="membership_period" placeholder="<?php _e('Enter Total Number of Days','gym_mgt');?>">

						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="member_limit"><?php _e('Members Limit','gym_mgt');?></label>
						<div class="col-sm-8">
						<?php $limitval = "unlimited"; ?>
							<label class="radio-inline">
							 <input type="radio" value="limited" class="tog" name="member_limit"  <?php  checked( 'limited', $limitval);  ?>/><?php _e('limited','gym_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="unlimited" class="tog" name="member_limit"  <?php  checked( 'unlimited', $limitval);  ?>/><?php _e('unlimited','gym_mgt');?> 
							</label>
						</div>
					</div>
					
					<div id="member_limit"></div>		
					<div class="form-group">
						<label class="col-sm-2 control-label" for="classis_limit"><?php _e('Classic Limit','gym_mgt');?></label>
						<div class="col-sm-8">
						<?php $limitvals = "unlimited"; if($edit){ $limitvals=$result->classis_limit; }elseif(isset($_POST['gender'])) {$limitvals=$_POST['gender'];}?>
							<label class="radio-inline">
							 <input type="radio" value="limited" class="classis_limit" name="classis_limit"  <?php  checked( 'limited', $limitvals);  ?>/><?php _e('limited','gym_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="unlimited" class="classis_limit validate[required]" name="classis_limit"  <?php  checked( 'unlimited', $limitvals);  ?>/><?php _e('unlimited','gym_mgt');?> 
							</label>
						</div>
					</div>
					<div id="classis_limit"></div>	
					

					<div class="form-group">
						<label class="col-sm-2 control-label" for="installment_amount"><?php _e('Membership Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="membership_amount" class="form-control validate[required] text-input" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="membership_amount">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="installment_plan"><?php _e('Installment Plan','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="installment_amount" class="form-control validate[required] text-input" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="installment_amount" placeholder="<?php _e('Amount','gym_mgt');?>">
						</div>
						<div class="col-sm-6">
						
							<select class="form-control" name="installment_plan" id="installment_plan">
							<option value=""><?php _e('Select Installment Plan','gym_mgt');?></option>
							<?php 
							
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
							<input id="signup_fee" class="form-control text-input" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->signup_fee;}elseif(isset($_POST['membership_name'])) echo $_POST['membership_name'];?>" name="signup_fee">
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
						<label class="col-sm-2 control-label" for="signup_fee"><?php _e('Membership Description','gym_mgt');?></label>
						<div class="col-md-8">
							<?php wp_editor(isset($result->membership_description)?stripslashes($result->membership_description) : '','description'); ?>
						</div>
					</div>						
					<div class="form-group">
						<label class="col-sm-2 control-label" for="gmgt_membershipimage"><?php _e('Membership Image','gym_mgt');?></label>
						<div class="col-sm-8">			
							<input type="text" id="gmgt_user_avatar_url1" class="gmgt_user_avatar_url" name="gmgt_membershipimage" readonly
							value="" />	
							 <input id="upload_image_button1" type="button" class="button upload_user_avatar_button" value="<?php _e( 'Upload Cover Image', 'gym_mgt' ); ?>" />
							 <span class="description"><?php _e('Upload Membership Image', 'gym_mgt' ); ?></span>
							<div class="upload_user_avatar_preview" id="upload_user_avatar_preview1">
								<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' );?> " />
							</div>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php  _e('Add Membership','gym_mgt');?>" name="save_membership" class="btn btn-success"/>
					</div>
				</form><!-- MEMBERSHIP FORM END-->
			</div><!-- PANEL BODY DIV END-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','gym_mgt');?></button>
			</div>
		</div><!-- MODAL CONTENT DIV END-->
	</div><!-- MODAL DIALOG DIV END-->
</div>	<!-- MAIN MODAL DIV END-->