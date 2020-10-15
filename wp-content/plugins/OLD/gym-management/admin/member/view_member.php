<?php 
$obj_gyme = new MJ_Gym_management();
?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#member_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$('#birth_date').datepicker(
	{
		   changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst)
			{
	            $(this).val(month + "/" + year);
	        }     
    }); 
} );
</script>
<?php	
$member_id=0;
if(isset($_REQUEST['member_id']))
	$member_id=$_REQUEST['member_id'];
$edit=0;					
	$edit=1;
	$user_info = get_userdata($member_id);					
?>	
<div class="panel-body"><!-- PANEL BODY DIV START-->
	<div class="member_view_row1"><!-- MEMBER VIEW ROW 1 DIV START-->
		<div class="col-md-8 col-sm-12 membr_left">
			<div class="col-md-6 col-sm-12 left_side">
			<?php 
			if($user_info->gmgt_user_avatar == "") { ?>
				<img alt="" src="<?php echo get_option( 'biglogo' ); ?>">
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
						<i class="fa fa-graduation-cap"></i><?php _e('Class','gym_mgt');?>
					</div>
					<div class="col-md-7 col-sm-12 table_td">
						<span class="txt_color">
						<?php 
							$ClassArr=MJ_gmgt_get_current_user_classis($user_info->ID);
							$class_name="-";
							$class_name_string="";
							if($ClassArr)
							{												
								foreach($ClassArr as $key=>$class_id)
								{							
									$class_name_string.=MJ_gmgt_get_class_name($class_id)."";									
								}
								$class=rtrim($class_name_string,"");
								echo chunk_split($class,24,"<BR>");
							}						
							else
							{
								echo chunk_split($class_name,24,"<BR>");
							}
						?>
						</span>
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
						<i class="fa fa-user"></i>
						<?php _e('Staff Member','gym_mgt'); ?>	
					</div>
					<div class="col-md-6 col-sm-12 table_td">
					<?php  $staff_member=MJ_gmgt_get_display_name($user_info->staff_id);  ?>
						<span class="txt_color"><?php echo chunk_split($staff_member,18,"<BR>");?></span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-heart"></i>
						<?php _e('Interest Area','gym_mgt');?>
					</div>
					<?php $intrest_area=get_the_title($user_info->intrest_area);   ?>
					<div class="col-md-6 col-sm-12 table_td">
						<span class="txt_color"><?php if($user_info->intrest_area!=""){ echo chunk_split($intrest_area,18,"<BR>"); }?></span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-users"></i>
						<?php _e('MemberShip','gym_mgt');?>
					</div>
					<div class="col-md-6 col-sm-12 table_td">
					 <?php $membership_name=MJ_gmgt_get_membership_name($user_info->membership_id);   ?>
						<span class="txt_color"><?php echo chunk_split($membership_name,18,"<BR>");?></span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-power-off"></i>
						<?php _e('Status','gym_mgt');?>
					</div>
					<div class="col-md-6 col-sm-12 table_td">
						<span class="txt_color"><?php  _e($user_info->membership_status,'gym_mgt');  ?></span>
					</div>
				</div>
				<div class="table_row">
					<div class="col-md-6 col-sm-12 table_td">
						<i class="fa fa-calendar"></i>
						<?php _e('Expire Date','gym_mgt');?>
					</div>
					<div class="col-md-6 col-sm-12 table_td">
						<span class="txt_color"><?php if($user_info->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box(MJ_gmgt_check_membership($user_info->ID)); }else{ echo "--"; }?></span>
					</div>
				</div>
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
		</div>
	</div>
</div><!-- PANEL BODY DIV END-->
<div class="panel-body"><!-- PANEL BODY DIV START-->
	<div class="clear"></div>
	<div class="col-md-6  col-sm-6  col-xs-12 border">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Weight','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Weight" 
			class="btn btn-danger right"> <?php _e('Add Weight','gym_mgt');?></a>	
		</span>
		<?php 
		$weight_data = $obj_gyme->MJ_gmgt_get_weight_report('Weight',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Weight');
		require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;		
		$wait_chart = $GoogleCharts->load( 'LineChart' , 'weight_report' )->get( $weight_data , $option );		
		?>
		<div id="weight_report" style="width: 100%; height: 250px;">
			<?php 
			if(empty($weight_data) || count($weight_data) == 1)
			_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
		<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  		<script type="text/javascript">
			<?php 
			if(!empty($weight_data) && count($weight_data) > 1)
			echo $wait_chart;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border borderleft">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Waist Report','gym_mgt');?></span>
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Waist" 
			class="btn btn-danger right"> <?php _e('Add Waist','gym_mgt');?></a>		
		</span>
		<?php 
		$waist_chart = $obj_gyme->MJ_gmgt_get_weight_report('Waist',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Waist');
		$GoogleCharts = new GoogleCharts;		
		$waist_chartreport = $GoogleCharts->load( 'LineChart' , 'waist_report' )->get( $waist_chart , $option );	
		?>
		<div id="waist_report" style="width: 100%; height: 250px;">
			<?php 
			if(empty($waist_chart) || count($waist_chart) == 1)
			_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
  		<script type="text/javascript">
			<?php 
			if(!empty($waist_chart) && count($waist_chart) > 1)
			echo $waist_chartreport;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Height Report','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Height" 
			class="btn btn-danger right"> <?php _e('Add Height','gym_mgt');?></a>	
		</span>
		<?php 
		$height_data = $obj_gyme->MJ_gmgt_get_weight_report('Height',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Height');
		$GoogleCharts = new GoogleCharts;		
		$height_chart = $GoogleCharts->load( 'LineChart' , 'height_reort' )->get( $height_data , $option );		
		?>
		<div id="height_reort" style="width: 100%; height: 250px;">
		<?php if(empty($height_data) || count($height_data) == 1)
		_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
		<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  		<script type="text/javascript">
			<?php 
			if(!empty($height_data) && count($height_data) > 1)
			echo $height_chart;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border borderleft">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Chest Report','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Chest" 
			class="btn btn-danger right"> <?php _e('Add Chest','gym_mgt');?></a>	
		</span>
		<?php 
		$chest_data = $obj_gyme->MJ_gmgt_get_weight_report('Chest',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Chest');
		require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;		
		$chest_chart = $GoogleCharts->load( 'LineChart' , 'chest_reort' )->get( $chest_data , $option );		
		?>
		<div id="chest_reort" style="width: 100%; height: 250px;">
			<?php if(empty($chest_data) || count($chest_data) == 1)
			_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
		<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  		<script type="text/javascript">
			<?php 
			if(!empty($chest_data) && count($chest_data) > 1)
			echo $chest_chart;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Thigh Report','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Thigh" 
			class="btn btn-danger right"> <?php _e('Add Thigh','gym_mgt');?></a>	
		</span>
		<?php 
		$thigh_data = $obj_gyme->MJ_gmgt_get_weight_report('Thigh',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Thigh');
		require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;		
		$thigh_chart = $GoogleCharts->load( 'LineChart' , 'thigh_reort' )->get( $thigh_data , $option );		
		?>
		<div id="thigh_reort" style="width: 100%; height: 250px;">
			<?php if(empty($thigh_data) || count($thigh_data) == 1)
			_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
		<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  		<script type="text/javascript">
			<?php 
			if(!empty($thigh_data) && count($thigh_data) > 1)
			echo $thigh_chart;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border borderleft">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Arms Report','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Arms" 
			class="btn btn-danger right"> <?php _e('Add Arms','gym_mgt');?></a>	
		</span>
		<?php 
		$arm_data = $obj_gyme->MJ_gmgt_get_weight_report('Arms',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Arms');
		require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;		
		$arm_chart = $GoogleCharts->load( 'LineChart' , 'arm_reort' )->get( $arm_data , $option );		
		?>
		<div id="arm_reort" style="width: 100%; height: 250px;">
			<?php if(empty($arm_data) || count($arm_data) == 1)
			_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
		<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  		<script type="text/javascript">
			<?php 
			if(!empty($arm_data) && count($arm_data) > 1)
			echo $arm_chart;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Fat Report','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Fat" 
			class="btn btn-danger right"> <?php _e('Add Fat','gym_mgt');?></a>	
		</span>
		<?php 
		$fat_data = $obj_gyme->MJ_gmgt_get_weight_report('Fat',$_REQUEST['member_id']);
		$option =  $obj_gyme->MJ_gmgt_report_option('Fat');
		require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;		
		$fat_chart = $GoogleCharts->load( 'LineChart' , 'fat_reort' )->get( $fat_data , $option );		
		?>
		<div id="fat_reort" style="width: 100%; height: 250px;">
		<?php if(empty($fat_data) || count($fat_data) == 1)
		_e('There is not enough data to generate report','gym_mgt')?>
		</div>   
		<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  		<script type="text/javascript">
			<?php 
			if(!empty($fat_data) && count($fat_data) > 1)
			echo $fat_chart;?>
		</script>
	</div>
	<div class="col-md-6  col-sm-6  col-xs-12 border borderleft viewmember_image_height">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-line-chart fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('My Photos','gym_mgt');?></span>	
			<a href="<?php echo admin_url()?>admin.php?page=gmgt_workout&tab=addmeasurement&user_id=<?php echo $_REQUEST['member_id'];?>&result_measurment=Fat" 
			class="btn btn-danger right"> <?php _e('Add Photo','gym_mgt');?></a>
			
			<div id="slider1_container" style="visibility: hidden; position: relative; margin: 0 auto; width: 500px; height: 250px; overflow: hidden;">
            <!-- Loading Screen -->
				<div u="loading" style="position: absolute; top: 0px; left: 0px;">
					<div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;

					background-color: #000; top: 0px; left: 0px;width: 100%; height:100%;">
					</div>
				  
				</div>
				<!-- Slides Container -->
				<div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 250px; height: 245px;
				overflow: hidden; margin-left:130px;">
				<?php $obj_workout = new MJ_Gmgtworkout();
					$measurement_data = $obj_workout->MJ_gmgt_get_all_measurement_by_userid($_REQUEST['member_id']);
						if(!empty($measurement_data))
						{
							foreach ($measurement_data as $retrieved_data)
							{ 
								$userimage=$retrieved_data->gmgt_progress_image; 
								if($userimage!=""){ ?>	
								   <div>
										<img u="image" src="<?php echo $retrieved_data->gmgt_progress_image;?>"/>
									</div>
							<?php  }
							} 
						} ?>
				</div>
				<!--#region Bullet Navigator Skin Begin -->
				<!-- Help: http://www.jssor.com/tutorial/set-bullet-navigator.html -->
				<style>
					.jssorb05 {
						position: absolute;
					}
					.jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
						position: absolute;
						/* size of bullet elment */
						width: 16px;
						height: 16px;
						 background: url("../images/b05.png") no-repeat;
						overflow: hidden;
						cursor: pointer;
					}
					.jssorb05 div { background-position: -7px -7px; }
					.jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
					.jssorb05 .av { background-position: -67px -7px; }
					.jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }
				</style>
				<!-- bullet navigator container -->
				<div u="navigator" class="jssorb05" style="bottom: 16px; right: 6px;">
					<!-- bullet navigator item prototype -->
					<div u="prototype"></div>
				</div>
				<!--#endregion Bullet Navigator Skin End -->
				
				<!--#region Arrow Navigator Skin Begin -->
				<!-- Help: http://www.jssor.com/tutorial/set-arrow-navigator.html -->
				<style>
					/* jssor slider arrow navigator skin 11 css */
				   
					.jssora11l, .jssora11r {
						display: block;
						position: absolute;
						/* size of arrow element */
						width: 37px;
						height: 37px;
						cursor: pointer;
						background: url("<?php echo GMS_PLUGIN_URL."/assets/images/a11.png";?>") no-repeat;
						overflow: hidden;
					}
					.jssora11l { background-position: -11px -41px; }
					.jssora11r { background-position: -71px -41px; }
					.jssora11l:hover { background-position: -131px -41px; }
					.jssora11r:hover { background-position: -191px -41px; }
					.jssora11l.jssora11ldn { background-position: -251px -41px; }
					.jssora11r.jssora11rdn { background-position: -311px -41px; }
				</style>
				<!-- Arrow Left -->
				<span u="arrowleft" class="jssora11l" style="top: 123px; left: 8px;">
				
				</span>
				<!-- Arrow Right -->
				<span u="arrowright" class="jssora11r" style="top: 123px; right: 8px;">
				</span>
				<!--#endregion Arrow Navigator Skin End -->
			</div>
		</span>
		<?php ?>
	</div>
</div><!-- PANEL BODY DIV END-->
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#subscription_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true}],
					 language:<?php echo MJ_gmgt_datatable_multi_language();?>	 
		});
} );
</script>	
<div class="panel-body"><!-- PANEL BODY DIV START-->
	<div class="col-md-12  col-sm-12  col-xs-12" style="border:1px solid #dedede;">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-align-left fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php _e('Subscription History','gym_mgt');?></span>	
		</span>
		<form name="wcwm_report" action="" method="post"><!-- MEMBER Subscription History FORM STRT-->
			<table id="subscription_list" class="display" cellspacing="0" width="100%"><!-- MEMBER Subscription History LIST TABLE START-->
				<thead>
					<tr>
						<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>Start Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>End Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php  _e( 'Title', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>Start Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership <BR>End Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
					$obj_membership_payment=new MJ_Gmgt_membership_payment;
					$paymentdata=$obj_membership_payment->MJ_gmgt_get_member_subscription_history($member_id);

					 if(!empty($paymentdata))
					 {
						foreach ($paymentdata as $retrieved_data){ ?>
						<tr>
							<td class="productname"><?php echo MJ_gmgt_get_membership_name($retrieved_data->membership_id);?></td>
							<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->membership_amount;?></td>
							<td class="paid_amount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->paid_amount;?></td>
							<td class="totalamount"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->membership_amount-$retrieved_data->paid_amount;?></td>
							<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->start_date);?></td>
							<td class="paymentdate"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->end_date);?></td>
							<td class="paymentdate">
							<?php 
							echo "<span class='btn btn-success btn-xs'>";								
							 _e(MJ_gmgt_get_membership_paymentstatus($retrieved_data->mp_id), 'gym_mgt' ) 
							?>
							</td>							
						</tr>
						<?php 
						} 							
					}?>
				</tbody>
			</table><!-- MEMBER Subscription History LIST TABLE END-->
		</form><!-- MEMBER Subscription History LIST FORM END-->
	</div>
</div><!-- PANEL BODY DIV END-->
<script>
	jQuery(document).ready(function ($) 
	{
		var options = {
			$AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
			$AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
			$Idle: 2000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
			$PauseOnHover: 1,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

			$ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
			$SlideEasing: $JssorEasing$.$EaseOutQuint,          //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
			$SlideDuration: 800,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
			$MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
			//$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
			//$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
			$SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
			$Cols: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
			$ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
			$UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
			$PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
			$DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $Cols is greater than 1, or parking position is not 0)

			$ArrowNavigatorOptions: {                           //[Optional] Options to specify and enable arrow navigator or not
				$Class: $JssorArrowNavigator$,                  //[Requried] Class to create arrow navigator instance
				$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
				$AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
				$Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
				$Scale: false                                   //Scales bullets navigator or not while slider scale
			},

			$BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
				$Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
				$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
				$AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
				$Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
				$Rows: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
				$SpacingX: 12,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
				$SpacingY: 4,                                   //[Optional] Vertical space between each item in pixel, default value is 0
				$Orientation: 1,                                //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
				$Scale: false                                   //Scales bullets navigator or not while slider scale
			}
		};

		var jssor_slider1 = new $JssorSlider$("slider1_container", options);

		//responsive code begin
		//you can remove responsive code if you don't want the slider scales while window resizing
		function ScaleSlider() {
			var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
			if (parentWidth) {
				jssor_slider1.$ScaleWidth(parentWidth - 30);
			}
			else
				window.setTimeout(ScaleSlider, 30);
		}
		ScaleSlider();

		$(window).bind("load", ScaleSlider);
		$(window).bind("resize", ScaleSlider);
		$(window).bind("orientationchange", ScaleSlider);
		//responsive code end
	});
</script>	