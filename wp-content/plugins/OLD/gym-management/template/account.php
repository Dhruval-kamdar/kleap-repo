<?php 
$user = wp_get_current_user ();
$obj_member=new MJ_Gmgtmember;
$user_data =get_userdata( $user->ID);
require_once ABSPATH . 'wp-includes/class-phpass.php';
$user_data =get_userdata( $user->ID);	
$first_name = get_user_meta($user_data->ID,'first_name',true);
$last_name = get_user_meta($user_data->ID,'last_name',true);	
$wp_hasher = new PasswordHash( 8, true );
//SAVE USER DATA
if(isset($_POST['save_change']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_change_nonce' ) )
	{
	$referrer = $_SERVER['HTTP_REFERER'];
	$success=0;
	if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
	{
		if($_REQUEST['new_pass']==$_REQUEST['conform_pass'])
		{
			 wp_set_password( $_REQUEST['new_pass'], $user->ID);
			$success=1;
		}
		else
		{
			wp_redirect($referrer.'&sucess=2');
		}
		
	}
	else
	{
		wp_redirect($referrer.'&sucess=3');
	}
	if($success==1)
	{
		 wp_cache_delete($user->ID,'users');
		wp_cache_delete($user_data->user_login,'userlogins');
		wp_logout();
		if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
			$referrer = $_SERVER['HTTP_REFERER'];
			
			wp_redirect($referrer.'&sucess=1');
		endif;
		ob_start();
	}
	else
	{
		wp_set_auth_cookie($user->ID, true);
	}
}
}
?>
<?php 
	$edit=1;
	$coverimage=get_option( 'gmgt_gym_background_image' );
	if($coverimage!="")
	{?>
		<style>
			.profile-cover
			{
				background: url("<?php echo get_option( 'gmgt_gym_background_image' );?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
			}
		</style>	
<?php
	}
	?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#doctor_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	 $.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
      $('#birth_date').datepicker({
     endDate: '+0d',
        autoclose: true
   });  
} );
</script>
<div>
	<div class="profile-cover"><!-- PROFILE COVER DIV START -->
		<div class="row">
			<div class="col-md-3 profile-image">
				<div class="profile-image-container">
					<?php $umetadata=get_user_meta($user->ID, 'gmgt_user_avatar', true);
					if(empty($umetadata)){
						echo '<img src='.get_option( 'gmgt_system_logo' ).' height="150px" width="150px" class="img-circle" />';
					}
					else
						echo '<img src='.$umetadata.' height="150px" 
					width="150px" class="img-circle" />';
	                ?>
				</div>
			</div>
		</div>
	</div><!-- PROFILE COVER DIV END -->
	<div id="main-wrapper"> <!-- MAIN WRAPPER DIV START -->
		<div class="row"><!--ROW DIV START -->
			<div class="col-md-3 user-profile">
				<h3 class="text-center">
					<?php 
						echo $user_data->display_name;
					?>
				</h3>				
				<hr>
				<ul class="list-unstyled text-center">
				<li>
				<p><i class="fa fa-map-marker m-r-xs"></i>
					<?php echo $user_data->address.",".$user_data->city;?></p>
				</li>	
				<li><i class="fa fa-envelope m-r-xs"></i>
					<?php echo 	$user_data->user_email;?></p>
				</p></li>
				</ul>
				<?php
				if($obj_gym->role == "staff_member")
				{
				?>
				<h3 class="text-center">
					<?php 
						echo __('My Activity','gym_mgt');
					?>
				</h3>
				<ul class="list-unstyled activity_list">
				<?php 
					$activity_list = MJ_gmgt_get_activity_by_staffmember(get_current_user_id());
					if(!empty($activity_list))
						foreach($activity_list as $retrive)
						{
							echo "<li> <i class='fa fa-arrow-right'></i> ".$retrive->activity_title."</li>";
						}
				?>	
				</ul>			
				<hr>
				<?php
				}
				?>
			</div>			
			<?php 
			if(isset($_REQUEST['message']))
			{
				$message =$_REQUEST['message'];
				if($message == 2)
				{ ?><div class="col-md-8 m-t-lg"><div id="message" class="updated below-h2 "><p><?php
							_e("User Profile updated successfully.",'gym_mgt');
							?></p>
							</div>
					</div>
						<?php 
				}
			}?>
			<div class="col-md-8 m-t-lg">
				<div class="panel panel-white">
					<div class="panel-heading">
						<div class="panel-title"><?php _e('Account Settings ','gym_mgt');?>	</div>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" action="#" method="post">
							<div class="form-group">
									<label  class="control-label col-xs-2"></label>
									<div class="col-xs-10">	
										<p>
											<h4 class="bg-danger"><?php 
											if(isset($_REQUEST['sucess']))
											{ 
												if($_REQUEST['sucess']==1)
												{
													wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit&message=2" );
												}
											}?></h4>
										</p>
									</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Name','gym_mgt');?></label>
								<div class="col-sm-10">
									<input type="Name" class="form-control validate[custom[onlyLetterSp]]" id="name" maxlength="50" placeholder="" value="<?php echo $user->display_name; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Username','gym_mgt');?></label>
								<div class="col-sm-10">
									<input type="username" class="form-control space_validation" maxlength="50" id="name" placeholder="" value="<?php echo $user->user_login; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="control-label col-sm-2 "><?php _e('Current Password','gym_mgt');?></label>
								<div class="col-sm-10">
									<input type="password" class="form-control space_validation" min_length="8" maxlength="12" id="inputPassword" placeholder="<?php _e('Current Password','gym_mgt');?>"  name="current_pass">
								</div>
							</div>
							<div class="form-group">
										<label for="inputPassword" class="control-label col-sm-2"><?php _e('New Password','gym_mgt');?></label>
										<div class="col-sm-10">
											<input type="password" class="validate[required] form-control space_validation" id="new_pass" min_length="8" maxlength="12" id="inputPassword" placeholder="<?php _e('New Password','gym_mgt');?>" name="new_pass">
										</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="control-label col-sm-2"><?php _e('Confirm Password','gym_mgt');?></label>
								<div class="col-sm-10">
									<input type="password" class="validate[required,equals[new_pass]] form-control space_validation" id="inputPassword"  min_length="8" maxlength="12" placeholder="<?php _e('Confirm Password','gym_mgt');?>" name="conform_pass">
								</div>
							</div>
							<!--nonce-->
							<?php wp_nonce_field( 'save_change_nonce' ); ?>
							<!--nonce-->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-success" name="save_change"><?php _e('Save','gym_mgt');?></button>
								</div>
							</div>
						</form>
					</div>		   
				</div>					
				<?php 			
				$user_info=get_userdata(get_current_user_id());
				 ?> 
				<div class="panel panel-white">
					<div class="panel-heading">
						<div class="panel-title"><?php _e('Other Information','gym_mgt');?>	</div>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" action="#" method="post" id="doctor_form">
							<input type="hidden" value="edit" name="action">
							<input type="hidden" value="<?php echo $obj_gym->role;?>" name="role">
							<input type="hidden" value="<?php echo get_current_user_id();?>" name="user_id">
							<input type="hidden" value="<?php print $first_name ?>" name="first_name" >
							<input type="hidden" value="<?php print $last_name ?>" name="last_name" >
							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php _e('Date of birth','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<input id="birth_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" class="form-control validate[required]" type="text"  name="birth_date" value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" readonly>
								</div>
							</div>	
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Home Town Address','gym_mgt');?></label>

								<div class="col-sm-10">

									<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" value="<?php if($edit){ echo $user_info->address;}?>">

								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('City','gym_mgt');?></label>
								<div class="col-sm-10">
									<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo $user_info->city_name;}?>">
								</div>
							</div>							
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Phone','gym_mgt');?></label>

								<div class="col-sm-10">
									<input id="phone" class="form-control text-input validate[custom[phone_number]]" type="text" minlength="6" maxlength="15"  name="phone" value="<?php if($edit){ echo $user_info->phone;}?>">

								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

								</div>
							</div>
							<div class="form-group">

								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-success" name="profile_save_change"><?php _e('Save','gym_mgt');?></button>
								</div>
							</div>
						</form>
					</div>
				</div>				
			</div>					
		</div><!-- ROW DIV END -->
 	</div><!-- MAIN WRAPPER DIV END -->
</div>
<?php 
//ADD USER DATA
if(isset($_POST['profile_save_change']))
{
	$result=$obj_member->MJ_gmgt_gmgt_add_user($_POST);
	if($result)
	{ 
		wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit&message=2" );
	}
}
?>