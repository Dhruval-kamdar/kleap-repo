<?php 
$obj_gyme = new MJ_Gym_management(); 
$obj_activity = new MJ_Gmgtactivity(); 

$staff_member_id=0;
if(isset($_REQUEST['staff_member_id']))
	$staff_member_id=$_REQUEST['staff_member_id'];
	$edit=0;					
	$edit=1;
	$user_info = get_userdata($staff_member_id);					
?>	
<div class="panel-body"><!--PANEL BODY DIV START-->
	<div class="member_view_row1"><!--MEMBER VIEW ROW 1 DIV START-->
		<div class="col-md-8 col-sm-12 membr_left">
			<div class="col-md-6 col-sm-12 left_side">
			<?php 
			if($user_info->gmgt_user_avatar == "") { ?>
				<img alt="" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
			<?php } 
			else { ?>
				<img style="max-width:100%;" src="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar ); ?>" />
			<?php }	?>
			</div>
			<div class="col-md-6 col-sm-12 right_side">
				<div class="table_row">
					<div class="col-md-5 col-sm-12 table_td">
						<i class="fa fa-user"></i> 
						<?php _e('Name','gym_mgt'); ?>	
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color">
							<?php echo chunk_split($user_info->first_name." ".$user_info->middle_name." ".$user_info->last_name,24,"<BR>");?> 
						</span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-5 col-sm-12 table_td">
						<i class="fa fa-envelope"></i> 
						<?php _e('Email','gym_mgt');?> 	
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color"><?php echo chunk_split($user_info->user_email,24,"<BR>");?></span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-5 col-sm-12 table_td">
					<i class="fa fa-phone"></i>
					<?php _e('Mobile No','gym_mgt');?> 
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color">
							<span class="txt_color"><?php echo $user_info->mobile;?> </span>
						</span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-5 col-sm-12 table_td">
						<i class="fa fa-calendar"></i>
						<?php _e('Date Of Birth','gym_mgt');?>	
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color"><?php echo MJ_gmgt_getdate_in_input_box($user_info->birth_date);?></span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-5 col-sm-12 table_td">
						<i class="fa fa-mars"></i>
						<?php _e('Gender','gym_mgt');?> 
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color"><?php echo $user_info->gender;?></span>
					</div>
				</div>
				
				<div class="table_row">
					<div class="col-md-5 col-sm-12 table_td">
						<i class="fa fa-user"></i>
						<?php _e('User Name','gym_mgt');?>
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color"><?php echo chunk_split($user_info->user_login,25,"<BR>");?> </span>
					</div>
				</div>
			
			</div>
		</div>
		<div class="col-md-4 col-sm-12 member_right">	
				<span class="report_title">
					<span class="fa-stack cutomcircle">
						<i class="fa fa-align-left fa-stack-1x"></i>
					</span> 
					<span class="shiptitle"><?php _e('More Info','gym_mgt');?></span>		
				</span>
				
				
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-map-marker" style="padding-right: 15px;"></i>						
						<?php _e('Address','gym_mgt');?>
					</div>
					<div class="col-md-6 col-sm-12 table_td">
						<span class="txt_color"><?php 
							 if($user_info->address != '')
							 {
								echo chunk_split($user_info->address.", <BR>",15);
							 }
							 
							if($user_info->city_name != '')
							{
								echo chunk_split($user_info->city_name.", <BR>",15);
							}
							 ?>
				        </span>
					</div>
				</div>
				
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-map-marker" style="padding-right: 15px;"></i>						
						<?php _e('Activity','gym_mgt');?>
					</div>
					<div class="col-md-6 col-sm-12 table_td">
						<span class="txt_color">
						<?php 
							$activity_array=$obj_activity->MJ_gmgt_get_activity_staffmemberwise($user_info->ID);
							$class_name="-";
							$class_name_string="";
							if($activity_array)
							{												
								foreach($activity_array as $key=>$activity_id)
								{
								  echo chunk_split($activity_id,24,", ");
								}
								
							}						
							else
							{
								echo chunk_split($class_name,20,"<BR>");
							}
						?>
						</span>
					</div>
				</div>				
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-map-marker" style="padding-right: 15px;"></i>						
						<?php _e('Assign Role','gym_mgt');?>
					</div>
					
					<div class="col-md-6 col-sm-12 table_td">
						<span class="txt_color"><?php echo chunk_split(get_the_title($user_info->role_type),25,"<BR>");?> </span>
					</div>
				</div>
		</div>
	</div><!--MEMBER VIEW ROW 1 DIV END-->
</div><!--PANEL BODY DIV END-->