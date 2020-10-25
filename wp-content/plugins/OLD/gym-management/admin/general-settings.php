<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNNER DIV START-->
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//	SAVE GENERAL SETTINGS DATA
	if(isset($_POST['save_setting']))
	{
		$txturl=$_POST['gmgt_system_logo'];
		$txturl1=$_POST['gmgt_gym_background_image'];
		$ext=MJ_gmgt_check_valid_extension($txturl);
		$ext1=MJ_gmgt_check_valid_extension($txturl1);
		if(!$ext == 0 && !$ext1==0)
		{
			$optionval=MJ_gmgt_option();
			foreach($optionval as $key=>$val)
			{
				if(isset($_POST[$key]))
				{
				  $result=update_option( $key, $_POST[$key]);
				}
			}
		}			
		else
		{ 
			?>
			<div id="message" class="updated below-h2 ">
				<p>
					<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
				</p>
			</div>				 
			<?php 
		}	
		//	UPDATE GENERAL SETTINGS OPTION
		if(isset($_REQUEST['gmgt_paymaster_pack']))
		{
			update_option( 'gmgt_paymaster_pack', 'yes' );
		}
		else
		{
			update_option( 'gmgt_paymaster_pack', 'no' );
		}
		if(isset($_REQUEST['gym_enable_sandbox']))
		{
			update_option( 'gym_enable_sandbox', 'yes' );
		}
		else 
		{
			update_option( 'gym_enable_sandbox', 'no' );
		}
		if(isset($_REQUEST['gym_enable_memberlist_for_member']))
		{
			update_option( 'gym_enable_memberlist_for_member', 'yes' );
		}
		else
		{
			update_option( 'gym_enable_memberlist_for_member', 'no' );
		}
		if(isset($_REQUEST['gym_enable_membership_alert_message']))
		{
			update_option( 'gym_enable_membership_alert_message', 'yes' );
		}
		else
		{	
			update_option( 'gym_enable_membership_alert_message', 'no' );
		}		
		if(isset($_REQUEST['gym_enable_member_can_message']))
		{
			update_option( 'gym_enable_member_can_message', 'yes' );
		}
		else
		{
			update_option( 'gym_enable_member_can_message', 'no' );
		}
		if(isset($_REQUEST['gym_enable_trainee_memberlist_for_staffmember']))
		{
			update_option( 'gym_enable_trainee_memberlist_for_staffmember', 'yes' );
		}
		else
		{
			update_option( 'gym_enable_trainee_memberlist_for_staffmember', 'no' );
		}		
			
		if(isset($_REQUEST['gym_enable_notifications']))
		{
			update_option( 'gym_enable_notifications', 'yes' );
		}
		else 
		{
			update_option( 'gym_enable_notifications', 'no' );
		}
		if(isset($_REQUEST['gym_enable_past_attendance']))
		{
			update_option( 'gym_enable_past_attendance', 'yes' );
		}
		else 
		{
			update_option( 'gym_enable_past_attendance', 'no' );
		}
		if(isset($_REQUEST['gym_enable_datepicker_privious_date']))
		{
			update_option( 'gym_enable_datepicker_privious_date', 'yes' );
		}
		else 
		{
			update_option( 'gym_enable_datepicker_privious_date', 'no' );
		}
		
		if(isset($_REQUEST['gym_frontend_class_booking']))
		{
			update_option( 'gym_frontend_class_booking', 'yes' );
		}
		else 
		{
			update_option( 'gym_frontend_class_booking', 'no' );
		}
		if(isset($result))
		{?>	
			<div id="message" class="updated below-h2">
				<p><?php _e('General Settings updated successfully.','gym_mgt');?></p>
			</div>
			<?php 
		}
	}
	?>
	<script type="text/javascript">
	$(document).ready(function()
	{
		$('#setting_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	} );
	</script>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->
	    <div class="panel panel-white"><!--PANEL WHITE DIV START-->
			<div class="panel-body"><!--PANEL BODY DIV START-->
				<h2>	
					<?php  echo esc_html( __( 'General Settings', 'gym_mgt')); ?>
				</h2>
		        <div class="panel-body"><!--PANEL BODY DIV START-->
					<form name="setting_form" action="" method="post" class="form-horizontal" id="setting_form"><!--GENERAL SETTINGS FORM START-->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_system_name"><?php _e('Gym Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_system_name" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php echo get_option( 'gmgt_system_name' );?>"  name="gmgt_system_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_staring_year"><?php _e('Starting Year','gym_mgt');?></label>
							<div class="col-sm-8">
								<input id="gmgt_staring_year" class="form-control" min="0" type="number" onKeyPress="if(this.value.length==4) return false;" value="<?php echo get_option( 'gmgt_staring_year' );?>"  name="gmgt_staring_year">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_gym_address"><?php _e('Gym Address','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_gym_address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text" value="<?php echo get_option( 'gmgt_gym_address' );?>"  name="gmgt_gym_address">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_contact_number"><?php _e('Official Phone Number','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_contact_number" class="form-control validate[required,custom[phone_number]] phone_validation"  minlength="6" maxlength="15" type="text" value="<?php echo get_option( 'gmgt_contact_number' );?>"  name="gmgt_contact_number">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_contact_number"><?php _e('Alternate Phone Number','gym_mgt');?></label>
							<div class="col-sm-8">
								<input  class="form-control validate[custom[phone_number]] phone_validation"  minlength="6" maxlength="15" type="text" value="<?php echo get_option( 'gmgt_alternate_contact_number' );?>"  name="gmgt_alternate_contact_number">
							</div>
						</div>
						
						
						<div class="form-group" class="form-control" id="">
							<label class="col-sm-2 control-label" for="gmgt_contry"><?php _e('Country','gym_mgt');?></label>
							<div class="col-sm-8">
								<?php 							
								$url = plugins_url( 'countrylist.xml', __FILE__ );
								$xml =simplexml_load_string(MJ_gmgt_get_remote_file($url));
								?>
								<select name="gmgt_contry" class="form-control validate[required]" id="smgt_contry">
									<option value=""><?php _e('Select Country','gym_mgt');?></option>
									<?php
										foreach($xml as $country)
										{  
										?>
										 <option value="<?php echo $country->name;?>" <?php selected(get_option( 'gmgt_contry' ), $country->name);  ?>><?php echo $country->name;?></option>
									<?php }?>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_email"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text" value="<?php echo get_option( 'gmgt_email' );?>"  name="gmgt_email">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_datepicker_format"><?php _e('Date Format','gym_mgt');?>
							</label>
							<div class="col-sm-8">
							<?php $date_format_array = MJ_gmgt_datepicker_dateformat();
							if(get_option( 'gmgt_datepicker_format' ))
							{
								$selected_format = get_option( 'gmgt_datepicker_format' );
							}
							else
								$selected_format = 'Y-m-d';
							?>
							<select id="gmgt_datepicker_format" class="form-control" name="gmgt_datepicker_format">
								<?php 
								foreach($date_format_array as $key=>$value)
								{
									echo '<option value="'.$value.'" '.selected($selected_format,$value).'>'.$value.'</option>';
								}
								?>
							</select>
							</div>
						</div>					
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_email"><?php _e('Gym Logo','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input type="text" id="gmgt_user_avatar_url" name="gmgt_system_logo" class=""  readonly value="<?php  echo get_option( 'gmgt_system_logo' ); ?>" />
								<input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" />
								<span class="description"><?php _e('Upload image.', 'gym_mgt' ); ?></span>                     
								<div id="upload_user_avatar_preview">
									<img class="image_preview_css" src="<?php  echo get_option( 'gmgt_system_logo' ); ?>" />		
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_cover_image"><?php _e('Profile Cover Image','gym_mgt');?></label>
							<div class="col-sm-8">			
								<input type="text" id="gmgt_gym_background_image" name="gmgt_gym_background_image" readonly value="<?php  echo get_option( 'gmgt_gym_background_image' ); ?>" />	
								<input id="upload_image_button" type="button" class="button upload_user_cover_button" value="<?php _e( 'Upload Cover Image', 'gym_mgt' ); ?>" />
								<span class="description"><?php _e('Upload Cover Image', 'gym_mgt' ); ?></span>                     
								<div id="upload_gym_cover_preview" style="min-height: 100px;margin-top:5px;">
									<img style="width:100%;height:300px;" src="<?php  echo get_option( 'gmgt_gym_background_image' ); ?>" />			
								</div>
							</div>
						</div>
						<!-- notification template   -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_enable_notifications"><?php _e('Enable Notifications','gym_mgt');?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="gym_enable_notifications"  value="1" <?php echo checked(get_option('gym_enable_notifications'),'yes');?>/><?php _e('Enable','gym_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<!-- end notification template   -->
						
						<div class="header">	<hr>
							<h3><?php _e('Measurement Units','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_weight_unit"><?php _e('Weight','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_weight_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_weight_unit' );?>"  name="gmgt_weight_unit">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_height_unit"><?php _e('Height','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_height_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_height_unit' );?>"  name="gmgt_height_unit">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_chest_unit"><?php _e('Chest','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_chest_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_chest_unit' );?>"  name="gmgt_chest_unit">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_waist_unit"><?php _e('Waist','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_waist_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_waist_unit' );?>"  name="gmgt_waist_unit">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_thigh_unit"><?php _e('Thigh','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_thigh_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_thigh_unit' );?>"  name="gmgt_thigh_unit">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_arms_unit"><?php _e('Arms','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_arms_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_arms_unit' );?>"  name="gmgt_arms_unit">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_fat_unit"><?php _e('Fat','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_fat_unit" class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="20" type="text" value="<?php echo get_option( 'gmgt_fat_unit' );?>"  name="gmgt_fat_unit">
							</div>
						</div>
						<div class="header">	
						<hr>
							<h3><?php _e('Paypal Setting','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_enable_sandbox"><?php _e('Enable Sandbox','gym_mgt');?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="gym_enable_sandbox"  value="1" <?php echo checked(get_option('gym_enable_sandbox'),'yes');?>/><?php _e('Enable','gym_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_paypal_email"><?php _e('Paypal Email Id','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="gmgt_paypal_email" class="form-control validate[required,custom[email]]  text-input" maxlength="100" type="text" value="<?php echo get_option( 'gmgt_paypal_email' );?>"  name="gmgt_paypal_email">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_currency_code"><?php _e('Select Currency','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select name="gmgt_currency_code" class="form-control validate[required] text-input">
								  <option value=""> <?php _e('Select Currency','gym_mgt');?></option>
								  <option value="AED" <?php echo selected(get_option( 'gmgt_currency_code' ),'AED');?>>
								  <?php _e('United Arab Emirates Dirham','gym_mgt');?></option>
								  <option value="AUD" <?php echo selected(get_option( 'gmgt_currency_code' ),'AUD');?>>
								  <?php _e('Australian Dollar','gym_mgt');?></option>
								  <option value="BRL" <?php echo selected(get_option( 'gmgt_currency_code' ),'BRL');?>>
								  <?php _e('Brazilian Real','gym_mgt');?> </option>
								  <option value="CAD" <?php echo selected(get_option( 'gmgt_currency_code' ),'CAD');?>>
								  <?php _e('Canadian Dollar','gym_mgt');?></option>
								  <option value="CZK" <?php echo selected(get_option( 'gmgt_currency_code' ),'CZK');?>>
								  <?php _e('Czech Koruna','gym_mgt');?></option>
								  <option value="DKK" <?php echo selected(get_option( 'gmgt_currency_code' ),'DKK');?>>
								  <?php _e('Danish Krone','gym_mgt');?></option>
								  <option value="EUR" <?php echo selected(get_option( 'gmgt_currency_code' ),'EUR');?>>
								  <?php _e('Euro','gym_mgt');?></option>
								  <option value="HKD" <?php echo selected(get_option( 'gmgt_currency_code' ),'HKD');?>>
								  <?php _e('Hong Kong Dollar','gym_mgt');?></option>
								  <option value="HUF" <?php echo selected(get_option( 'gmgt_currency_code' ),'HUF');?>>
								  <?php _e('Hungarian Forint','gym_mgt');?> </option>
								  <option value="INR" <?php echo selected(get_option( 'gmgt_currency_code' ),'INR');?>>
								  <?php _e('Indian Rupee','gym_mgt');?></option>
								  <option value="ILS" <?php echo selected(get_option( 'gmgt_currency_code' ),'ILS');?>>
								  <?php _e('Israeli New Sheqel','gym_mgt');?></option>
								  <option value="JPY" <?php echo selected(get_option( 'gmgt_currency_code' ),'JPY');?>>
								  <?php _e('Japanese Yen','gym_mgt');?></option>
								  <option value="MYR" <?php echo selected(get_option( 'gmgt_currency_code' ),'MYR');?>>
								  <?php _e('Malaysian Ringgit','gym_mgt');?></option>
								  <option value="MXN" <?php echo selected(get_option( 'gmgt_currency_code' ),'MXN');?>>
								  <?php _e('Mexican Peso','gym_mgt');?></option>
								  <option value="NOK" <?php echo selected(get_option( 'gmgt_currency_code' ),'NOK');?>>
								  <?php _e('Norwegian Krone','gym_mgt');?></option>
								  <option value="NZD" <?php echo selected(get_option( 'gmgt_currency_code' ),'NZD');?>>
								  <?php _e('New Zealand Dollar','gym_mgt');?></option>
								  <option value="PHP" <?php echo selected(get_option( 'gmgt_currency_code' ),'PHP');?>>
								  <?php _e('Philippine Peso','gym_mgt');?></option>
								  <option value="PLN" <?php echo selected(get_option( 'gmgt_currency_code' ),'PLN');?>>
								  <?php _e('Polish Zloty','gym_mgt');?></option>
								  <option value="GBP" <?php echo selected(get_option( 'gmgt_currency_code' ),'GBP');?>>
								  <?php _e('Pound Sterling','gym_mgt');?></option>
								  <option value="SGD" <?php echo selected(get_option( 'gmgt_currency_code' ),'SGD');?>>
								  <?php _e('Singapore Dollar','gym_mgt');?></option>
								  <option value="SEK" <?php echo selected(get_option( 'gmgt_currency_code' ),'SEK');?>>
								  <?php _e('Swedish Krona','gym_mgt');?></option>
								  <option value="CHF" <?php echo selected(get_option( 'gmgt_currency_code' ),'CHF');?>>
								  <?php _e('Swiss Franc','gym_mgt');?></option>
								  <option value="TWD" <?php echo selected(get_option( 'gmgt_currency_code' ),'TWD');?>>
								  <?php _e('Taiwan New Dollar','gym_mgt');?></option>
								  <option value="THB" <?php echo selected(get_option( 'gmgt_currency_code' ),'THB');?>>
								  <?php _e('Thai Baht','gym_mgt');?></option>
								  <option value="TRY" <?php echo selected(get_option( 'gmgt_currency_code' ),'TRY');?>>
								  <?php _e('Turkish Lira','gym_mgt');?></option>
								  <option value="USD" <?php echo selected(get_option( 'gmgt_currency_code' ),'USD');?>>
								  <?php _e('U.S. Dollar','gym_mgt');?></option>
								</select>
							</div>
							<div class="col-sm-1">
								<span style="font-size: 23px;"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span>
							</div>
						</div>
						<?php if(is_plugin_active('paymaster/paymaster.php')) { ?> 
						<div class="form-group">
							<label for="gmgt_paymaster_pack" class="col-sm-2 control-label"><?php _e('Use Paymaster Payment Gateways','gym_mgt');?></label>
							<div class="col-sm-4">
								<div class="checkbox">
								<label><input type="checkbox" value="yes" <?php echo checked(get_option('gmgt_paymaster_pack'),'yes');?> name="gmgt_paymaster_pack"><?php _e('Enable','gym_mgt') ?> </label>
							  </div>
							</div>
						</div>
						<?php } ?>
						<div class="header">	<hr>
							<h3><?php _e('Bank Details','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_paypal_email"><?php _e('Name of the A/c holder','gym_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-8">
								<input id="gmgt_system_name" class="form-control validate[custom[onlyLetter_specialcharacter]" maxlength="50" type="text" value="<?php echo get_option( 'gmgt_bank_holder_name' );?>"  name="gmgt_bank_holder_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_paypal_email"><?php _e('Name of the A/c Bank','gym_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-8">
								<input id="gmgt_system_name" class="form-control validate[custom[onlyLetter_specialcharacter]" maxlength="50" type="text" value="<?php echo get_option( 'gmgt_bank_name' );?>"  name="gmgt_bank_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_paypal_email"><?php _e('Account Number','gym_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-8">
								<input id="gmgt_system_name" class="form-control phone_validation"  min="0" type="number" onKeyPress="if(this.value.length==30) return false;" value="<?php echo get_option( 'gmgt_bank_acount_number' );?>"  name="gmgt_bank_acount_number">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_paypal_email"><?php _e('IFSC Code','gym_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-8">
								<input id="gmgt_system_name" class="form-control validate[custom[onlyLetterNumber]" maxlength="30" type="text" value="<?php echo get_option( 'gmgt_bank_ifsc_code' );?>"  name="gmgt_bank_ifsc_code">
							</div>
						</div>
						<div class="header">	<hr>
							<h3><?php _e('Membership Expiration Mail Notification Setting','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_enable_sandbox"><?php _e('Enable Alert Mail','gym_mgt');?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="gym_enable_membership_alert_message"  value="yes" <?php echo checked(get_option('gym_enable_membership_alert_message'),'yes');?>/><?php _e('Enable','gym_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_reminder_before_days"><?php _e('Reminder Before Days','gym_mgt');?></label>
							<div class="col-sm-8">
								<input id="gmgt_reminder_before_days" class="form-control" min="0" type="number" onKeyPress="if(this.value.length==4) return false;" value="<?php echo get_option( 'gmgt_reminder_before_days' );?>"  name="gmgt_reminder_before_days">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gmgt_paypal_email"><?php _e('Reminder Subject','gym_mgt');?><span class="require-field"></span></label>
							<div class="col-sm-8">
								<input class="form-control validate[required] text-input onlyletter_number_space_validation" maxlength="100" value="<?php echo get_option( 'gmgt_reminder_subject' );?>" type="text" name="gmgt_reminder_subject">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_reminder_message"><?php _e('Reminder Message','gym_mgt');?></label>
							<div class="col-sm-8">
								<textarea name="gym_reminder_message" class="form-control"><?php echo get_option('gym_reminder_message');?>
								</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for=""><?php _e('ShortCodes For Notification Mail Message Content','gym_mgt'); ?></label>
							<div class="col-sm-8">
								<label>[GMGT_MEMBERNAME]</label> <br>
								<label>[GMGT_STARTDATE]</label><br>
								<label>[GMGT_ENDDATE]</label><br>
								<label>[GMGT_MEMBERSHIP]</label><br>
								<label>[GMGT_GYM_NAME]</label><br>
							</div>
						</div>
						
						<div class="header"><hr>
							<h3><?php _e('Attendance Setting','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_enable_past_attendance"><?php _e("Past Date Attendance","gym_mgt");?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="gym_enable_past_attendance"  value="yes" <?php echo checked(get_option('gym_enable_past_attendance'),'yes');?>/><?php _e('Enable','gym_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<div class="header"><hr>
							<h3><?php _e('Datepicker Setting','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for=""><?php _e("Past Date","gym_mgt");?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="gym_enable_datepicker_privious_date"  value="yes" <?php echo checked(get_option('gym_enable_datepicker_privious_date'),'yes');?>/><?php _e('Enable','gym_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<div class="header">	
							<hr>
							<h3><?php _e('Class Booking Setting','gym_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="gym_frontend_class_booking"><?php _e("FrontEnd Class Booking","gym_mgt");?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="gym_frontend_class_booking"  value="yes" <?php echo checked(get_option('gym_frontend_class_booking'),'yes');?>/><?php _e('Enable','gym_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<div class="col-sm-offset-2 col-sm-8">
							<input type="submit" value="<?php _e('Save', 'gym_mgt' ); ?>" name="save_setting" class="btn btn-success"/>
						</div>
					</form><!--GENERAL SETTINGS FORM END-->
	            </div><!--PANEL BODY DIV END-->
            </div><!--PANEL BODY DIV END-->
        </div><!--PANEL WHITE DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNNER DIV END-->