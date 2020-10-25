<?php $role="member"; ?>
<script type="text/javascript">
    $(document).ready(function()
	{
		$('#member_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$('#add_staff_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		 
		$('#membership_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	 
		$('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		 
		$("#group_form").validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$('#group_id').multiselect({
		nonSelectedText :'<?php _e('Select Group','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('.classis_ids').multiselect({
		nonSelectedText :'<?php _e('Select Class','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('#specialization').multiselect({
		nonSelectedText :'<?php _e('Select Specialization','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('#day').multiselect({
		nonSelectedText :'<?php _e('Select Day','gym_mgt');?>',
		includeSelectAllOption: true
		 });
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
		$('#class_membership_id').multiselect(
		{
			nonSelectedText :'<?php _e('Select Membership','gym_mgt');?>',
			includeSelectAllOption: true
		});
		$('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','gym_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','gym_mgt'); ?>'
		 });	
		$(".specialization_submit").click(function()
		{	
			 checked = $(".multiselect_validation_specialization  .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast one specialization','gym_mgt');?>");
			  return false;
			}	
		});
			
		$(".class_submit").click(function()
		{	
			checked = $(".multiselect_validation_member .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast one class','gym_mgt');?>");
			  return false;
			}	
		}); 
		
		$(".day_validation_submit").click(function()
		{	
			checked = $(".day_validation_member .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast One Day','gym_mgt');?>");
			  return false;
			}	  
		}); 
		$(".day_validation_submit").click(function()
		{	
			checked = $(".multiselect_validation_membership .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select Atleast One membership.','gym_mgt');?>");
			  return false;
			}	  
		}); 
		$.fn.datepicker.defaults.format ="<?php echo get_option('gmgt_datepicker_format');?>";
		$('.birth_date').datepicker(
		{
			endDate: '+0d',
			autoclose: true
		}); 
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		 $('#inqiury_date').datepicker({	
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
	   
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		 $('#triel_date').datepicker({
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
	   	   
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
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
	   
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('#first_payment_date').datepicker({
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

		//------ADD STAFF MEMBER AJAX----------
		$('#add_staff_form').on('submit', function(e) 
		{
			e.preventDefault();
			var form = $(this).serialize();
			var valid = $('#add_staff_form').validationEngine('validate');
			if (valid == true) 
			{				
				$.ajax(
				{
					type:"POST",
					url: $(this).attr('action'),
					data:form,
					success: function(data)
					{					
						if(data!='0')
						{ 
							if(data!="")
							{ 
								$('#add_staff_form').trigger("reset");
								$('#staff_id').append(data);
								$('#reference_id').append(data);
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
		//------ADD GROUP AJAX----------
		
		$('#group_form').on('submit', function(e)
		{
			e.preventDefault();
			var form = $(this).serialize();
			var valid = $("#group_form").validationEngine('validate');
			if (valid == true)
			{
				$('.modal').modal('hide');
			}
			var categCheck_group = $('#group_id').multiselect();	
			$.ajax(
			{
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data){
					if(data!=""){ 
						$('#group_form').trigger("reset");
						$('#group_id').append(data);
						categCheck_group.multiselect('rebuild');	
					}
				},
				error: function(data){
				}
			})
		});
		
		//------ADD MEMBERSHIP AJAX----------
		$('#membership_form').on('submit', function(e)
		{
			e.preventDefault();
			var form = $(this).serialize();
			var valid = $('#membership_form').validationEngine('validate');
			if (valid == true)
			{				
				$.ajax(
				{
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
		
		//------ADD CLASS AJAX----------
		$('#class_form').on('submit', function(e)
		{
			e.preventDefault(); 
			var form = $(this).serialize();
			
			var categCheck_class = $('#classis_id').multiselect();	
			var categCheck_day = $('#day').multiselect();	
			var categCheck_class_membership = $('#class_membership_id').multiselect();	
			var valid = $('#class_form').validationEngine('validate');
			if (valid == true)
			{			
				$.ajax({
					type:"POST",
					url: $(this).attr('action'),
					data:form,
					success: function(data)
					{	
						if(data=="1")
						{ 
							alert("<?php _e('End Time should be greater than Start Time','gym_mgt'); ?>");
							return false;		
						}
						else
						{
							$('#class_form').trigger("reset");
							$('#classis_id').append(data);
							categCheck_class.multiselect('rebuild');	
							categCheck_day.multiselect('rebuild');	
							categCheck_class_membership.multiselect('rebuild');	
							$('.modal').modal('hide');
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
if($active_tab == 'addmember')
{
  	$member_id=0;
	if(isset($_REQUEST['memberid']))
		$member_id=$_REQUEST['memberid'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$user_info = get_userdata($member_id);
			if($user_info->gmgt_hash)
			{
				$lastmember_id=MJ_gmgt_get_lastmember_id($role);
				$nodate=substr($lastmember_id,0,-4);
				$memberno=substr($nodate,1);
				$memberno+=1;
				$newmember='M'.$memberno.date("my");
			}
		}
		else
		{
		    $lastmember_id=MJ_gmgt_get_lastmember_id($role);
			$nodate=substr($lastmember_id,0,-4);
			$memberno=substr($nodate,1);
			$memberno+=1;
			$newmember='M'.$memberno.date("my");
		}?>
        <div class="panel-body"><!-- PAGE INNNER DIV START-->
			<form name="member_form" action="" method="post" class="form-horizontal" id="member_form"><!-- MEMBER FROM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="role" value="<?php echo $role;?>"  />
				<input type="hidden" name="user_id" value="<?php  echo $member_id;?>"  />
				<input type="hidden" name="gmgt_hash" value="<?php if($edit){ if($user_info->gmgt_hash){ echo $user_info->gmgt_hash;}}?>"  />

				<div class="header col-sm-12">	
					<h3><?php _e('Personal Information','gym_mgt');?></h3>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="member_id"><?php _e('Member Id','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="member_id" class="form-control" type="text" 
							value="<?php if($edit){  echo $user_info->member_id;}else echo $newmember;?>"  readonly name="member_id" tabindex="1">
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_member_nonce' ); ?>
					<!--nonce-->
					<div class="form-group">
						<label class="col-sm-4 control-label" for="first_name"><?php _e('First Name','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name"  tabindex="2">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="middle_name"><?php _e('Middle Name','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter] " type="text" maxlength="50"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name"  tabindex="3">
						</div>						
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="last_name"><?php _e('Last Name','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name" tabindex="4">
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="birth_date"><?php _e('Date of birth','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="birth_date" class="form-control validate[required] birth_date" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="birth_date" 
							value="<?php if($edit){  echo MJ_gmgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])) echo MJ_gmgt_getdate_in_input_box($_POST['birth_date']);?>" readonly  tabindex="5">
						</div>
					</div>
					<div class="form-group">	
						<label class="col-sm-4 control-label" for="gender"><?php _e('Gender','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
						<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
							<label class="radio-inline">
							 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?> tabindex="6" /><?php _e('Male','gym_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','gym_mgt');?> 
							</label>
						</div>
					</div>	
					<div class="form-group">	
						<label class="col-sm-4 control-label" for="group_id"><?php _e('Group','gym_mgt');?></label>
						<div class="col-sm-7">
							<?php 
							$joingroup_list = $obj_member->MJ_gmgt_get_all_joingroup($member_id);
							$groups_array = $obj_member->MJ_gmgt_convert_grouparray($joingroup_list);
							?>
							<?php if($edit){ $group_id=$user_info->group_id; }elseif(isset($_POST['group_id'])){$group_id=$_POST['group_id'];}else{$group_id='';}?>
							<select id="group_id"  name="group_id[]" multiple="multiple" tabindex="7">
							
							<?php $groupdata=$obj_group->MJ_gmgt_get_all_groups();
							 if(!empty($groupdata))
							 {
								foreach ($groupdata as $group){?>
									<option value="<?php echo $group->id;?>" <?php if(in_array($group->id,$groups_array)) echo 'selected';  ?>><?php echo $group->group_name; ?> </option>
						<?php } } ?>
							</select>
							<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_group" tabindex="8"> <?php _e('Add','gym_mgt');?></a>	
						</div>						
					</div>	
				</div>						
				<div class="header col-sm-12">	
					<hr>
					<h3><?php _e('Contact Information','gym_mgt');?></h3>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="address"><?php _e('Address','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
							value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>" tabindex="9">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="city_name"><?php _e('City','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
							value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>" tabindex="10">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="state_name"><?php _e('State','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
							value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>" tabindex="11">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="zip_code"><?php _e('Zip Code','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" 
							value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>" tabindex="12">
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
							<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input phone_validation"  type="text" minlength="6" name="mobile" maxlength="15"
							value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" tabindex="13">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label " for="phone"><?php _e('Phone','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="phone" class="form-control text-input phone_validation validate[custom[phone_number]]"  type="text" minlength="6" maxlength="15"  name="phone" 
							value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" tabindex="14">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label " for="email"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input type="hidden"  name="hidden_email" value="<?php if($edit){ echo $user_info->user_email; } ?>">
							<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>" tabindex="15">
						</div>
					</div>
				</div>
				<div class="header col-sm-12">	
					<hr>
					<h3><?php _e('Physical Information','gym_mgt');?></h3>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="weight"><?php _e('Weight','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="weight" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01"
							value="<?php if($edit){ echo $user_info->weight;}elseif(isset($_POST['weight'])) echo $_POST['weight'];?>" 	name="weight" placeholder="<?php echo get_option( 'gmgt_weight_unit' );?>" tabindex="16">							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="height"><?php _e('Height','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="height" class="form-control text-input decimal_number"type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" value="<?php if($edit){ echo $user_info->height;}elseif(isset($_POST['height'])) echo $_POST['height'];?>" 
							name="height" placeholder="<?php echo get_option( 'gmgt_height_unit' );?>" tabindex="17">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="Chest"><?php _e('Chest','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="Chest" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" 
							value="<?php if($edit){ echo $user_info->chest;}elseif(isset($_POST['chest'])) echo $_POST['chest'];?>" name="chest" placeholder="<?php echo get_option( 'gmgt_chest_unit' );?>" tabindex="18">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="Waist"><?php _e('Waist','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="waist" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" 
							value="<?php if($edit){ echo $user_info->waist;}elseif(isset($_POST['waist'])) echo $_POST['waist'];?>" name="waist" placeholder="<?php echo get_option( 'gmgt_waist_unit' );?>" tabindex="19">
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="thigh"><?php _e('Thigh','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="thigh" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" 
							value="<?php if($edit){ echo $user_info->thigh;}elseif(isset($_POST['thigh'])) echo $_POST['thigh'];?>" name="thigh" placeholder="<?php echo get_option( 'gmgt_thigh_unit' );?>" tabindex="20">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="arms"><?php _e('Arms','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="arms" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01"
							value="<?php if($edit){ echo $user_info->arms;}elseif(isset($_POST['arms'])) echo $_POST['arms'];?>" name="arms" placeholder="<?php echo get_option( 'gmgt_arms_unit' );?>" tabindex="21">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="fat"><?php _e('Fat','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="fat" class="form-control text-input decimal_number" type="number" min="0" max="100" onkeypress="if(this.value.length==6) return false;" step="0.01"
							value="<?php if($edit){ echo $user_info->fat;}elseif(isset($_POST['fat'])) echo $_POST['fat'];?>" name="fat" placeholder="<?php echo get_option( 'gmgt_fat_unit' );?>" tabindex="22">
						</div>
					</div>
				</div>
				<div class="header col-sm-12">
					<hr>
					<h3><?php _e('Login Information','gym_mgt');?></h3>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="username"><?php _e('User Name','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<input id="username" class="form-control validate[required,custom[username_validation]] space_validation" type="text" maxlength="50"  name="username" 
							value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?> tabindex="23">
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="password"><?php _e('Password','gym_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
						<div class="col-sm-7">
							<input id="password" class="form-control space_validation <?php if(!$edit) echo 'validate[required]';?>" minlength="8" maxlength="12" type="password"  name="password" value="" tabindex="24">
						</div>
					</div>
				</div>	
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="photo"><?php _e('Image','gym_mgt');?></label>
						<div class="col-sm-4">
							<input type="text" id="gmgt_user_avatar_url" class="form-control" name="gmgt_user_avatar" readonly 
							value="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar );elseif(isset($_POST['gmgt_user_avatar'])) echo $_POST['gmgt_user_avatar']; ?>" />
						</div>	
							<div class="col-sm-3">
								<input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" tabindex="25" />
							</div>
						<div class="clearfix"></div>						
						<div class="col-sm-offset-4 col-sm-7">
							 <div id="upload_user_avatar_preview"  >
							 <?php if($edit) 
							  {
								if($user_info->gmgt_user_avatar == "")
								{
									 ?>
									<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
									<?php
									}
									else
									{
										?>
									<img class="image_preview_css"  src="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar ); ?>" />
									<?php 
									}
								}
								else 
								{
									?>
									<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
									<?php 
								}
								?>
							</div>
					 </div>
					</div>
				</div>
				<div class="header col-sm-12">	
					<hr>
					<h3><?php _e('More Information','gym_mgt');?></h3>
				</div>	
				<div class="col-sm-6 padding_left_right_0">		
					<div class="form-group">
						<label class="col-sm-4 control-label" for="refered"><?php _e('Member type','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-7">
							<select name="member_type" class="form-control validate[required]" id="member_type" tabindex="26">
								<option value=""><?php  _e('Select Member Type','gym_mgt');?></option>
								<?php if($edit)
										$mtype=$user_info->member_type;
									elseif(isset($_POST['member_type']))
										$mtype=$_POST['member_type'];
									else
										$mtype="";
									$membertype_array=MJ_gmgt_member_type_array();
									if(!empty($membertype_array))
									{
										foreach($membertype_array as $key=>$type)
										{
											echo '<option value='.$key.' '.selected($mtype,$key).'>'.$type.'</option>';
										}
									} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="staff_name"><?php _e('Select Staff Member','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-5">
							<?php $get_staff = array('role' => 'Staff_member');
								$staffdata=get_users($get_staff);
							?>
							<select name="staff_id" class="form-control validate[required] " id="staff_id" tabindex="27">
								<option value=""><?php  _e('Select Staff Member','gym_mgt');?></option>
								<?php if($edit)
										$staff_data=$user_info->staff_id;
									elseif(isset($_POST['staff_id']))
										$staff_data=$_POST['staff_id'];
									else
										$staff_data="";
									if(!empty($staffdata))
									{
									foreach($staffdata as $staff)
									{
										
										echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
									}
									}
									?>
							</select>
						</div>
						<div class="col-sm-2">					
							<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_staff_member" tabindex="28"> <?php _e('Add','gym_mgt');?></a>					
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="intrest"><?php _e('Interest Area','gym_mgt');?></label>
						<div class="col-sm-4">
							<select class="form-control" name="intrest_area" id="intrest_area" tabindex="29">
								<option value=""><?php _e('Select Interest','gym_mgt');?></option>
								<?php 
								
								if(isset($_REQUEST['intrest']))
									$category =$_REQUEST['intrest'];  
								elseif($edit)
									$category =$user_info->intrest_area;
								else 
									$category = "";
								
								$role_type=MJ_gmgt_get_all_category('intrest_area');
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
						<div class="col-sm-3 add_category_padding_0"><button id="addremove" model="intrest_area" tabindex="30"><?php _e('Add Or Remove','gym_mgt');?></button></div>
					</div>
				</div>				
				<?php 
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					?>
				<div class="row">	
					<div class="col-sm-6 padding_left_right_0">		
						<div class="form-group">
							<label class="col-sm-4 control-label" for="member_convert"><?php  _e(' Convert into Staff Member','gym_mgt');?></label>
							<div class="col-sm-7">
								<input type="checkbox"   name="member_convert" value="staff_member">
							</div>
						</div>
					</div>	
				</div>	
				<?php 
				}
				?>
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="Source"><?php _e('Referral Source','gym_mgt');?></label>
						<div class="col-sm-4">
							<select class="form-control" name="source" id="source" tabindex="31">
								<option value=""><?php _e('Select Referral Source','gym_mgt');?></option>
								<?php 								
								if(isset($_REQUEST['source']))
									$category =$_REQUEST['source'];  
								elseif($edit)
									$category =$user_info->source;
								else 
									$category = "";
								
								$role_type=MJ_gmgt_get_all_category('source');
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
						<div class="col-sm-3 add_category_padding_0"><button id="addremove" model="source" tabindex="32"><?php _e('Add Or Remove','gym_mgt');?></button></div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="refered"><?php _e('Referred By','gym_mgt');?></label>
						<div class="col-sm-5">
							<?php 
								$staffdata=get_users([ 'role__in' => ['Staff_member', 'member']]);
							?>
							<select name="reference_id" class="form-control" id="reference_id" tabindex="33">
								<option value=""><?php  _e('Select Referred Member','gym_mgt');?></option>
								<?php if($edit)
										$staff_data=$user_info->reference_id;
									elseif(isset($_POST['reference_id']))
										$staff_data=$_POST['reference_id'];
									else
										$staff_data="";					
									
									if(!empty($staffdata))
									{
										foreach($staffdata as $staff)
										{						
											echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
										}
									}
									?>
							</select>
						</div>
						<div class="col-sm-2">					
						<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_staff_member" tabindex="34"> <?php _e('Add','gym_mgt');?></a>
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="inqiury_date"><?php _e('Inquiry Date','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="inqiury_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="inqiury_date" 
							value="<?php if($edit){ if($user_info->inqiury_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->inqiury_date); } }elseif(isset($_POST['inqiury_date'])){ echo $_POST['inqiury_date']; }?>" tabindex="35" readonly>
						</div>
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">	
					<div class="form-group">
						<label class="col-sm-4 control-label" for="triel_date"><?php _e('Trial End Date','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="triel_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="triel_date" 
							value="<?php if($edit){ if($user_info->triel_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->triel_date); } }elseif(isset($_POST['triel_date'])){ echo $_POST['triel_date']; }?>" tabindex="36" readonly>
						</div>
					</div>
				</div>
				<div id="non_prospect_area">
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="membership"><?php _e('Membership','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-5">							
								<input type="hidden" name="membership_hidden" class="membership_hidden" value="<?php if($edit){ if(!empty($user_info->membership_id)) { echo $user_info->membership_id; }else{ echo '0'; } }else{ echo '0';}?>">
								<?php 	$membershipdata=$obj_membership->MJ_gmgt_get_all_membership(); ?>
								<select name="membership_id" class="form-control validate[required]" id="membership_id" tabindex="37">	
									<option value=""><?php  _e('Select Membership','gym_mgt');?></option>
									<?php 
										$staff_data=$user_info->membership_id;
										if(!empty($membershipdata))
										{
											foreach ($membershipdata as $membership)
											{						
												echo '<option value='.$membership->membership_id.' '.selected($staff_data,$membership->membership_id).'>'.$membership->membership_label.'</option>';
											}
										}
										?>
								</select>
							</div>
							<div class="col-sm-2">						
							<a href="#" class="btn btn-default" data-toggle="modal" id="add_membership_btn" data-target="#myModal_add_membership" tabindex="38"> <?php _e('Add','gym_mgt');?></a>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0" <?php if(!$edit){ ?> style="clear: both;" <?php } ?>>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="class_id"><?php _e('Class','gym_mgt');?><span class="require-field">*</span></label>			
							<div class="col-sm-5 multiselect_validation_member">				
								<!--<?php if($edit){ $class_id=$user_info->class_id; }elseif(isset($_POST['class_id'])){$class_id=$_POST['class_id'];}else{$class_id='';}?>-->
								<select id="classis_id" class="form-control validate[required] classis_ids" multiple="multiple" name="class_id[]" tabindex="39">
										<?php 
										if($edit)					
										{		
											$obj_class=new MJ_Gmgtclassschedule;
											$MemberShipClass = MJ_gmgt_get_class_id_by_membership_id($user_info->membership_id);
											$userclass  = MJ_gmgt_get_current_user_classis($member_id);
											
											foreach($MemberShipClass as $key=>$class_id)
											{
												$class_data=$obj_class->MJ_gmgt_get_single_class($class_id);
											?>
												<option value="<?php echo $class_id ;?>" <?php if(in_array($class_id,$userclass)){ print "Selected"; }  ?>><?php echo MJ_gmgt_get_class_name($class_id); ?> ( <?php echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->start_time).' - '.MJ_gmgt_timeremovecolonbefoream_pm($class_data->end_time);?>)</option>
											<?php 
											}
										}
										?>
										
								</select>
							</div>
							<div class="col-sm-2">						
							<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_class" tabindex="40"> <?php _e('Add','gym_mgt');?></a>
							</div>
						</div>
					</div>
					<?php 
					if($edit)
					{ 
					?>					
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="membership_status"><?php _e('Membership Status','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
							<?php $membership_statusval = "Continue"; if($edit){ $membership_statusval=$user_info->membership_status; }elseif(isset($_POST['membership_status'])) {$membership_statusval=$_POST['membership_status'];}?>
								<label class="radio-inline">
								 <input type="radio" value="Continue" class="tog validate[required]" name="membership_status"  <?php  checked( 'Continue', $membership_statusval);  ?>/><?php _e('Continue','gym_mgt');?>
								</label>
								<label class="radio-inline">
								 <input type="radio" value="Expired" class="tog validate[required]" name="membership_status"  <?php  checked( 'Expired', $membership_statusval);  ?>/><?php _e('Expired','gym_mgt');?>
								</label>
								<label class="radio-inline">
								  <input type="radio" value="Dropped" class="tog validate[required]" name="membership_status"  <?php  checked( 'Dropped', $membership_statusval);  ?>/><?php _e('Dropped','gym_mgt');?> 
								</label>
							</div>
						</div>
						<input type="hidden" name="auto_renew" value="No">		
					</div>		
					<?php } ?>	
					<div class="padding_left_right_0" style="clear: both;"> 	
						<div class="col-sm-6 padding_left_right_0">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="begin_date"><?php _e('Membership Valid From','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-7">
									<input id="begin_date" class="form-control validate[required] begin_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text"  name="begin_date" 
									value="<?php if($edit){ if($user_info->begin_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->begin_date); } }elseif(isset($_POST['begin_date'])) echo $_POST['begin_date'];?>" tabindex="41" readonly>
								</div>								
							</div>
						</div>
						<div class="col-sm-6 padding_left_right_0">
							<div class="form-group">								
								<label class="col-sm-4 control-label" for="begin_date"><?php _e('Membership Valid To','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-7">
									<input id="end_date" class="form-control validate[required]" type="text"  name="end_date" 
									value="<?php if($edit){ 
									if($user_info->end_date!=""){
									echo MJ_gmgt_getdate_in_input_box($user_info->end_date); }
									}
									elseif(isset($_POST['end_date'])) echo $_POST['end_date'];?>" readonly>
								</div>
							</div>
						</div>					
					</div>
				</div>
				<div class="col-sm-6 padding_left_right_0">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="first_payment_date"><?php _e('First Payment Date','gym_mgt');?></label>
						<div class="col-sm-7">
							<input id="first_payment_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="first_payment_date" 
							value="<?php if($edit){ if($user_info->first_payment_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->first_payment_date); } }elseif(isset($_POST['first_payment_date'])){ echo $_POST['first_payment_date']; }?>" tabindex="42" readonly>
						</div>
					</div>
				</div>
				<div id="no_of_class"></div>
				<div class="col-sm-offset-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save Member','gym_mgt');}?>" name="save_member" class="btn btn-success class_submit " tabindex="43" />
				</div>
			</form><!-- MEMBER FROM END-->
		</div><!-- PANEL BODY DIV END-->
<?php 
}
?>
<!----------ADD STAFF MEMBER POPUP------------->
<div class="modal fade" id="myModal_add_staff_member" role="dialog" style="overflow:scroll;"><!-- MODAL MAIN DIV START-->
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
					<input type="hidden" name="user_id" value=""/>
					<div class="header" style="clear:both;">	
						<h4><?php _e('Personal Information','gym_mgt');?></h4>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="first_name"><?php _e('First Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="first_name" tabindex="44">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="middle_name"><?php _e('Middle Name','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="" name="middle_name" tabindex="45">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="last_name"><?php _e('Last Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="" name="last_name" tabindex="46">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="gender"><?php _e('Gender','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
							<?php $genderval = "male"; ?>
								<label class="radio-inline">
								 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?> tabindex="47" /><?php _e('Male','gym_mgt');?>
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
								<input id="staff_birth_date" class="form-control validate[required] birth_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text"  name="birth_date" value="" readonly tabindex="48">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="role_type"><?php _e('Assign Role','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-4">
							
								<select class="form-control" name="role_type" id="role_type" tabindex="49">
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
							<div class="col-sm-3 add_category_padding_0"><button id="addremove" model="role_type" tabindex="50"><?php _e('Add Or Remove','gym_mgt');?></button></div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="specialization"><?php _e('Specialization','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-4 multiselect_validation_specialization specialization_css">
								<select class="form-control"  name="activity_category[]" id="specialization"  multiple="multiple" tabindex="51" >
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
								<button id="addremove" model="activity_category" tabindex="52"><?php _e('Add Or Remove','gym_mgt');?></button>
							</div>
						</div>							
					</div>							
					<div class="header" style="clear:both;">	
						<hr>
						<h4><?php _e('Contact Information','gym_mgt');?></h4>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="address"><?php _e('Home Town Address','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
								value="<?php if(isset($_POST['address'])) echo $_POST['address'];?>" tabindex="53">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="city_name"><?php _e('City','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
								value="" tabindex="54">
							</div>
						</div>
							<div class="form-group">
							<label class="col-sm-4 control-label" for="state_name"><?php _e('State','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="state_name" class="form-control validate[city_state_country_validation]" type="text" maxlength="50"  name="state_name" 
								value="" tabindex="55">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="zip_code"><?php _e('Zip Code','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15" name="zip_code" 
								value="" tabindex="56">
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
								<input id="mobile" class="form-control validate[required,custom[phone_number]] phone_validation text-input" type="text" minlength="6"  name="mobile" maxlength="15"
								value="" tabindex="57">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label " for="phone"><?php _e('Phone','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="phone" class="form-control validate[custom[phone_number]] phone_validation text-input" minlength="6" maxlength="15" type="text"  name="phone" 
								value="" tabindex="58">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label " for="email"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
								value="" tabindex="59">
							</div>
						</div>
					</div>
					<div class="header" style="clear:both;">	
						<hr>
						<h4><?php _e('Login Information','gym_mgt');?></h4>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="username"><?php _e('User Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="username" class="form-control validate[required,custom[username_validation]] space_validation" maxlength="50" type="text"  name="username" 
								value="" tabindex="60">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="password"><?php _e('Password','gym_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
							<div class="col-sm-7">
								<input id="password" class="form-control validate[required] space_validation" type="password" min_length="8" maxlength="12"  name="password" value="" tabindex="61">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="photo"><?php _e('Image','gym_mgt');?></label>
							<div class="col-sm-4">
								<input type="text" id="gmgt_user_avatar_url1" class="form-control gmgt_user_avatar_url" name="gmgt_user_avatar" readonly 
								value="" />
							</div>	
							<div class="col-sm-3">
									 <input id="upload_user_avatar_button1" type="button" class="button upload_user_avatar_button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" tabindex="62" />
									
							</div>
							<div class="clearfix"></div>								
							<div class="col-sm-offset-4 col-sm-7">
									 <div id="upload_user_avatar_preview1" class="upload_user_avatar_preview">
										 <img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
									</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0 " style="clear:both;">
						<div class="col-sm-offset-4 col-sm-7">
							<input type="submit" value="<?php  _e('Add Staff','gym_mgt');?>" name="save_staff" id="add_staff_member" class="btn btn-success specialization_submit" tabindex="63" />
						</div>
					</div>
				</form><!-- Staff MEMBER FORM END-->
			</div><!-- MODAL BODY DIV END-->
			<div class="modal-footer float_and_width">
				<div class="col-sm-12 padding_left_right_0">	
					<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="64"><?php _e('Close','gym_mgt');?></button>
				</div>
			</div>
		</div><!-- MODAL ContENT DIV END-->
	</div><!-- MODAL DIALOG DIV END-->
</div><!-- MODAL MAIN DIV END-->
<!----------ADD GORUP POPUP------------->
<div class="modal fade" id="myModal_add_group" role="dialog" style="overflow:scroll;"><!-- MODAL MAIN DIV START-->
	<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
		<div class="modal-content"><!-- MODAL CONTENT DIV START-->
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php _e('Add Group','gym_mgt');?></h3>
			</div>
			<div class="modal-body"><!-- MODAL BODY DIV START-->
				<form name="group_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="group_form"><!-- GROUP FORM START-->
						<input type="hidden" name="action" value="MJ_gmgt_add_group">
						<input type="hidden" name="group_id" value=""  />
						<div class="form-group">
							<label class="col-sm-2 control-label" for="group_name"><?php _e('Group Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="group_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if(isset($_POST['group_name'])) echo $_POST['group_name'];?>" name="group_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for=""><?php _e('Group Description','gym_mgt');?></label>
							<div class="col-sm-8">
							<textarea name="group_description" class="form-control validate[custom[address_description_validation]]" maxlength="500" ></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_membershipimage"><?php _e('Group Image','gym_mgt');?></label>
							<div class="col-sm-8">
								<input type="text" id="gmgt_gym_background_image" name="gmgt_groupimage" readonly value="<?php if(isset($_POST['gmgt_groupimage'])) echo $_POST['gmgt_groupimage'];?>" />	
								<input id="upload_image_button" type="button" class="button upload_user_cover_button" value="<?php _e( 'Upload Cover Image', 'gym_mgt' ); ?>" />
								<span class="description"><?php _e('Upload Group Image', 'gym_mgt' ); ?></span>
								<div id="upload_gym_cover_preview" style="min-height: 100px;">
								<img style="max-width:100%;" src="<?php if(isset($_POST['gmgt_groupimage'])) echo $_POST['gmgt_groupimage']; else echo get_option( 'gmgt_system_logo' );?>" />
								</div>
							</div>
						</div>
						<div class="col-sm-offset-2 col-sm-8">
							<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_group" class="btn btn-success"/>
						</div>
				</form><!-- GROUP FORM END-->
			</div><!-- MODAL BODY DIV END-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','gym_mgt');?></button>
			</div>
		</div><!-- MODAL CONTENT DIV END-->
	</div><!-- MODAL DIALOG DIV END-->
</div><!-- MODAL MAIN DIV END-->
<!----------ADD MEMBERSHIP POPUP------------->
<div class="modal fade" id="myModal_add_membership" role="dialog" style="overflow:scroll;"><!-- MODAL MAIN DIV START-->
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
					<input type="hidden" name="membership_id" class="membership_id_activity" value=""  />
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
							if(isset($_REQUEST['membership_category']))
								$category =$_REQUEST['membership_category'];  
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
							<input id="membership_period" class="form-control validate[required,custom[number]] text-input" type="number" onKeyPress="if(this.value.length==3) return false;" value="<?php if(isset($_POST['membership_period'])) echo $_POST['membership_period'];?>" name="membership_period" placeholder="<?php _e('Enter Total Number of Days','gym_mgt');?>">
						
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="member_limit"><?php _e('Members Limit','gym_mgt');?></label>
						<div class="col-sm-8">
						<?php $limitval = "unlimited"; if(isset($_POST['gender'])) {$limitval=$_POST['gender'];}?>
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
						<?php $limitvals = "unlimited"; if(isset($_POST['gender'])) {$limitvals=$_POST['gender'];}?>
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
							<input id="membership_amount" class="form-control validate[required] text-input" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php if(isset($_POST['membership_amount'])) echo $_POST['membership_amount'];?>" name="membership_amount">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="installment_plan"><?php _e('Installment Plan','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="installment_amount" class="form-control validate[required] text-input" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" type="number" value="<?php if(isset($_POST['installment_amount'])) echo $_POST['installment_amount'];?>" name="installment_amount" placeholder="<?php _e('Amount','gym_mgt');?>">
						</div>
						<div class="col-sm-6">
						
							<select class="form-control" name="installment_plan" id="installment_plan">
							<option value=""><?php _e('Select Installment Plan','gym_mgt');?></option>
							<?php 
							
							if(isset($_REQUEST['installment_plan']))
								$category =$_REQUEST['installment_plan'];  
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
							<input id="signup_fee" class="form-control text-input" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php if(isset($_POST['membership_name'])) echo $_POST['membership_name'];?>" name="signup_fee">
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
								<input type="hidden" class="action_membership" value="add_membership">
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
							<input type="text" id="gmgt_user_avatar_url1" class="gmgt_user_avatar_url" name="gmgt_membershipimage" readonly
							value="<?php if(isset($_POST['gmgt_membershipimage'])) echo $_POST['gmgt_membershipimage'];?>" />	
							 <input id="upload_image_button1" type="button" class="button upload_user_avatar_button" value="<?php _e( 'Upload Cover Image', 'gym_mgt' ); ?>" />
							 <span class="description"><?php _e('Upload Membership Image', 'gym_mgt' ); ?></span>
							<div class="upload_user_avatar_preview" id="upload_user_avatar_preview1">
								<img class="image_preview_css" src="<?php if(isset($_POST['gmgt_membershipimage'])) echo $_POST['gmgt_membershipimage']; else echo get_option( 'gmgt_system_logo' );?>" />
							</div>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ _e('Save Membership','gym_mgt'); }else{ _e('Add Membership','gym_mgt');}?>" name="save_membership" class="btn btn-success"/>
					</div>
				</form><!-- MEMBERSHIP FORM END-->
			</div><!-- MODAL BODY DIV END-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','gym_mgt');?></button>
			</div>
		</div><!-- MODAL ContENT DIV END-->
	</div><!-- MODAL DIALOG DIV END-->
</div>	<!--MODAL MAIN DIV END-->
<!----------ADD CLASS POPUP------------->
<div class="modal fade" id="myModal_add_class" role="dialog" style="overflow:scroll;"><!--MODAL MAIN DIV START-->
	<div class="modal-dialog modal-lg"><!--MODAL DIALOG DIV START-->
		<div class="modal-content"><!--MODAL CONTENT DIV START-->
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php _e('Add Class','gym_mgt');?></h3>
			</div>
			<div class="modal-body"><!--MODAL BODY DIV START-->
				<form name="class_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="class_form"><!--CLASS FORM START-->
					<input type="hidden" name="action" value="MJ_gmgt_add_ajax_class">
					<input type="hidden" name="class_id" value=""  />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="class_name"><?php _e('Class Name','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="class_name" class="form-control validate text-input validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="" name="class_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="staff_name"><?php _e('Select Staff Member','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<?php $get_staff = array('role' => 'Staff_member');
								$staffdata=get_users($get_staff);?>
							<select name="staff_id" class="form-control validate[required] " id="staff_id">
							<option value=""><?php  _e('Select Staff Member ','gym_mgt');?></option>
							<?php 								
								$staff_data="";
								if(!empty($staffdata))
								{
									foreach($staffdata as $staff)
									{
										
										echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
									}
								}
								?>
							</select>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Assistant Staff Member','gym_mgt');?></label>
						<div class="col-sm-8">
							<?php $get_staff = array('role' => 'Staff_member');
								$staffdata=get_users($get_staff);?>
							<select name="asst_staff_id" class="form-control" id="asst_staff_id">
							<option value=""><?php  _e('Select Assistant Staff Member ','gym_mgt');?></option>
							<?php 								
								$assi_staff_data="";
								
								if(!empty($staffdata))
								{
									foreach($staffdata as $staff)
									{
										
										echo '<option value='.$staff->ID.' '.selected($assi_staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="invoice_date"><?php _e('Start Date','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="class_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" class="form-control class_date validate[required]" type="text"  
							value="<?php  echo MJ_gmgt_getdate_in_input_box(date("Y-m-d")); ?>" name="start_date">
						</div>
					</div>
					
					<div class="form-group"><label class="control-label col-md-2" for="End"><?php _e('End Date','gym_mgt');?><span class="text-danger"> *</span></label>
						 <div class="col-md-8">
							 <div class="radio">
								 <div class="input text">
								 <input id="end_date"  data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" class="form-control class_date validate[required]" type="text"  
								value="<?php echo MJ_gmgt_getdate_in_input_box(date("Y-m-d")); ?>" name="end_date">
							  </div>
						   </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="day"><?php _e('Select Day','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8 day_validation_member">			
							<select name="day[]" class="form-control validate[required]" id="day" multiple="multiple">							
							<?php $class_days=array();
								foreach(MJ_gmgt_days_array() as $key=>$day)
								{
									$selected = "";
									if(in_array($key,$class_days))
										$selected = "selected";
									echo '<option value='.$key.' '.$selected.'>'.$day.'</option>';
								}?>					
							</select>
						</div>			
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="day"><?php _e('Membership','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8 multiselect_validation_membership">
							<?php 
								$membersdata=array();
								$data=array();
							?>
							<?php $membershipdata=$obj_membership->MJ_gmgt_get_all_membership();?>
							<select name="membership_id[]" class="form-control" multiple="multiple" id="class_membership_id">	
								<?php 					
								if(!empty($membershipdata))
								{
									foreach ($membershipdata as $membership)
									{
										$selected = "";
										if(in_array($membership->membership_id,$data))
											$selected="selected";
									
										echo '<option value='.$membership->membership_id .' '.$selected.' >'.$membership->membership_label.'</option>';
										
									}
								}
								?>
							</select>
						</div>			
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="quentity"><?php _e('Member Limit','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input  class="form-control validate[required] text-input" min="0" onkeypress="if(this.value.length==3) return false;" type="number" value="" name="member_limit">
						</div>
					</div>	
					<div class="add_more_time_entry">
						<div class="time_entry">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="starttime"><?php _e('Start Time','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-2">			
								 <select name="start_time[]" class="form-control validate[required]">
								 <option value=""><?php _e('Select Time','gym_mgt');?></option>
										 <?php 
											for($i =0 ; $i <= 12 ; $i++)
											{
											?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php
											}
										 ?>
										 </select>
							</div>
							<div class="col-sm-2">
								 <select name="start_min[]" class="form-control validate[required]">
										 <?php 
											foreach(MJ_gmgt_minute_array() as $key=>$value)
											{?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
											<?php
											}
										 ?>
										 </select>
							</div>
							<div class="col-sm-2">
								 <select name="start_ampm[]" class="form-control validate[required]">
									<option value="am"><?php _e('am','gym_mgt');?></option>
									<option value="pm"><?php _e('pm','gym_mgt');?></option>
								 </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="weekday"><?php _e('End Time','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-2">			
								 <select name="end_time[]" class="form-control validate[required]">
								 <option value=""><?php _e('Select Time','gym_mgt');?></option>
								 <?php 
									for($i =0 ; $i <= 12 ; $i++)
									{
									?>
										<option value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php
									}
								 ?>
								 </select>
							</div>
							<div class="col-sm-2">
								 <select name="end_min[]" class="form-control validate[required]">
								 <?php 
									foreach(MJ_gmgt_minute_array() as $key=>$value)
									{
										?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php
									}
								 ?>
								 </select>
							</div>
							<div class="col-sm-2">				
								<select name="end_ampm[]" class="form-control validate[required]">				
									<option value="am"><?php _e('am','gym_mgt');?></option>
									<option value="pm"><?php _e('pm','gym_mgt');?></option>					
							   </select>
							</div>	
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"></label>
						<div class="col-sm-3">					
							<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add More Time Slots','gym_mgt'); ?>
							</button>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="quentity"><?php _e('Class Color','gym_mgt');?></label>
						<div class="col-sm-4">
							  <input type="color" value="<?php if($edit){ echo $result->color;}elseif(isset($_POST['class_color'])) echo $_POST['class_color'];?>" name="class_color" >
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php _e('Save','gym_mgt');?>" name="save_class" class="btn btn-success day_validation_submit"/>
					</div>
				</form><!--CLASS FORM END-->
			</div><!--MODAL BODY DIV END-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','gym_mgt');?></button>
			</div>
		</div><!--MODAL CONTENT DIV END-->
	</div><!--MODAL DIALOG DIV END-->
</div>	<!--MODAL MAIN DIV END-->
<script>
	$(document).ready(function() 
	{
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format'); ?>";
		$('.class_date').datepicker({
		startDate: date,
		autoclose: true
	   });
	});
	//ADD ENTRY
	function add_entry()
	{
		$(".add_more_time_entry").append('<div class="time_entry"><div class="form-group"><label class="col-sm-2 control-label" for="starttime"><?php _e('Start Time','gym_mgt');?><span class="require-field">*</span></label><div class="col-sm-2"><select name="start_time[]" class="form-control validate[required]"><option value=""><?php _e('Select Time','gym_mgt');?></option>  <?php for($i =0 ; $i <= 12 ; $i++) { ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?></select></div><div class="col-sm-2"><select name="start_min[]" class="form-control validate[required]"> <?php foreach(MJ_gmgt_minute_array() as $key=>$value){ ?> <option value="<?php echo $key;?>"><?php echo $value;?></option><?php }?></select></div><div class="col-sm-2"><select name="start_ampm[]" class="form-control validate[required]"><option value="am"><?php _e('am','gym_mgt');?></option><option value="pm"><?php _e('pm','gym_mgt');?></option></select></div></div><div class="form-group"><label class="col-sm-2 control-label" for="weekday"><?php _e('End Time','gym_mgt');?><span class="require-field">*</span></label><div class="col-sm-2"><select name="end_time[]" class="form-control validate[required]"><option value=""><?php _e('Select Time','gym_mgt');?></option>                <?php for($i =0 ; $i <= 12 ; $i++){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php } ?>              </select></div><div class="col-sm-2"><select name="end_min[]" class="form-control validate[required]">                <?php 	foreach(MJ_gmgt_minute_array() as $key=>$value)	{ ?><option value="<?php echo $key;?>"><?php echo $value;?></option><?php } ?> </select></div><div class="col-sm-2"><select name="end_ampm[]" class="form-control validate[required]"><option value="am"><?php _e('am','gym_mgt');?></option><option value="pm"><?php _e('pm','gym_mgt');?></option></select></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','gym_mgt');?></i></button></div></div></div>');			
	}
	
	// REMOVING  ENTRY
	function deleteParentElement(n)
	{
		alert("<?php _e('Do you really want to delete this time Slots','gym_mgt'); ?>");
		n.parentNode.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode.parentNode);
	}
 </script> 