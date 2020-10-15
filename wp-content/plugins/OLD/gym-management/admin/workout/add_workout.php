<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	 var date = new Date();
	 date.setDate(date.getDate()-0);
	 $.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
	 $('#curr_date').datepicker({
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
    $('#record_date').datepicker({
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
	$('#workout_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$(".display-members").select2();
} );
</script>
<?php 	
if($active_tab == 'addworkout')
{
	$workoutmember_id=0;
	if(isset($_REQUEST['workoutmember_id']))
		$workoutmember_id=$_REQUEST['workoutmember_id'];
		$view=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
		{
			$view=1;
			?>
			<form method="post" class="form-horizontal">  
				<div class="col-md-12">
					<h2><?php echo MJ_gmgt_get_display_name($_REQUEST['workoutmember_id']).'\'s '; ?> <?php _e('Workout','gym_mgt')?></h2>
				</div>
				<div class="form-group">
					<label class="col-sm-1 control-label" for="curr_date"><?php _e('Date','gym_mgt');?></label>
					<div class="col-sm-3">
					<input id="curr_date" class="form-control" type="text" 
					value="<?php if(isset($_POST['tcurr_date'])) echo $_POST['tcurr_date']; 
					else 
						echo  MJ_gmgt_getdate_in_input_box(date("Y-m-d"));?>" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="tcurr_date" readonly>
					</div>
					<div class="col-sm-3">
					<input type="submit" value="<?php _e('View Workouts','gym_mgt');?>" name="view_workouts"  class="btn btn-success"/>
					</div>
				</div>
			</form>
		   <div class="clearfix"> </div>
			<?php 
			if(isset($_REQUEST['view_workouts']) || isset($_REQUEST['view_workouts']))
			{			
					$tcurrent_date=MJ_gmgt_get_format_for_db($_POST['tcurr_date']);
					$today_workouts=$obj_workout->MJ_gmgt_get_member_today_workouts($workoutmember_id,$tcurrent_date);
					if(!empty($today_workouts))
					{
						?>
						<div class="col-md-12 my-workouts-display">
						<?php
						foreach($today_workouts as $value)
						{
							$workoutid=$value->user_workout_id;
							$activity_name=$value->workout_name;
							$workflow_category=$obj_workout->MJ_gmgt_get_user_workouts($workoutid,$activity_name);
							
							if($workflow_category->sets!='0')
							{
								$sets_progress=$value->sets*100/$workflow_category->sets;
							}
							else
							{
								$sets_progress=100;
							}
							if($workflow_category->reps!='0')
							{							
								$reps_progress=$value->reps*100/$workflow_category->reps;
							}
							else
							{
								$reps_progress=100;
							}
							if($workflow_category->kg!='0')
							{
								$kg_progress=$value->kg*100/$workflow_category->kg;
							}
							else
							{
								$kg_progress=100;
							}
							if($workflow_category->time!='0')
							{
								$rest_time_progress=$value->rest_time*100/$workflow_category->time;
							}
							else
							{
								$rest_time_progress=100;
							}
						?>
						<div class='col-md-12 activity-data no-padding'>
							<div class='workout_datalist_header'>
								<h2><?php echo $value->workout_name;?></h2>
							</div>
							<div class="col-md-12 workout_datalist no-padding"> 
								<div class="col-md-3 sets-row no-paddingleft">	
									<div class="workout_box">
										<div class="col-md-3">	
											<h2 class="activity_box_number"><?php echo 1 ;?></h2>
										</div>
										<div class="col-md-9 no-paddingleft padding_10 activity_width">
											<p class="activity_attribute"><?php _e('Sets','gym_mgt');?></p>
											<div class="activity_progress_line"><div style="height: 3px;background-color: #fff;width:<?php echo $sets_progress; ?>%;"></div></div>
											<p class="workout_of"><?php echo $value->sets;?> <?php _e('Out Of','gym_mgt');?> <?php echo $workflow_category->sets;?> <?php _e('Sets','gym_mgt');?></p>
										</div>
									</div>										
								</div>
								<div class="col-md-3 sets-row no-paddingleft">	
									<div class="workout_box">
										<div class="col-md-3">	
											<h2 class="activity_box_number"><?php echo 2 ;?></h2>
										</div>
										<div class="col-md-9 no-paddingleft padding_10 activity_width">
											<p class="activity_attribute"><?php _e('Reps','gym_mgt');?></p>
											<div class="activity_progress_line"><div style="height: 3px;background-color: #fff;width:<?php echo $reps_progress; ?>%;"></div></div>
											<p class="workout_of"><?php echo $value->reps;?> <?php _e('Out Of','gym_mgt');?> <?php echo $workflow_category->reps;?> <?php _e('Reps','gym_mgt');?></p>
										</div>
									</div>										
								</div>									
								<div class="col-md-3 sets-row no-paddingleft">	
									<div class="workout_box">
										<div class="col-md-3">	
											<h2 class="activity_box_number"><?php echo 3 ;?></h2>
										</div>
										<div class="col-md-9 no-paddingleft padding_10 activity_width">
											<p class="activity_attribute"><?php _e('Kg','gym_mgt');?></p>
											<div class="activity_progress_line"><div style="height: 3px;background-color: #fff;width:<?php echo $kg_progress; ?>%;"></div></div>
											<p class="workout_of"><?php echo $value->kg;?> <?php _e('Out Of','gym_mgt');?> <?php echo $workflow_category->kg;?> <?php _e('Kg','gym_mgt');?></p>
										</div>
									</div>										
								</div>	
								<div class="col-md-3 sets-row no-paddingleft">	
									<div class="workout_box">
										<div class="col-md-3">	
											<h2 class="activity_box_number"><?php echo 4 ;?></h2>
										</div>
										<div class="col-md-9 no-paddingleft padding_10 activity_width">
											<p class="activity_attribute"><?php _e('Rest Time','gym_mgt');?></p>
											<div class="activity_progress_line"><div style="height: 3px;background-color: #fff;width:<?php echo $rest_time_progress; ?>%;"></div></div>
											<p class="workout_of"><?php echo $value->rest_time;?> <?php _e('Out Of','gym_mgt');?> <?php echo $workflow_category->time;?> <?php _e('Rest Time','gym_mgt');?></p>
										</div>
									</div>										
								</div>
							</div>								
						</div>
						<div class="border_line"></div>								
						<?php 							
						}
						?>							
						</div>						
					<?php 
					}
					else
					{ ?>
					<span class="col-md-10"><?php _e('No Data Of Today workout','gym_mgt');?></span>
			<?php }
			}
		}
		else
		{ ?>
			<div class="panel-body"><!--PANEL BODY DIV STRAT-->
				<form name="workout_form" action="" method="post" class="form-horizontal" id="workout_form"><!--WORKOUT FORM STRAT-->
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="daily_workout_id" value="<?php echo $workoutmember_id;?>"  />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?> <span class="require-field">*</span></label>
						<div class="col-sm-8">
							<?php if($view){ $member_id=$result->member_id; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
							<select id="member_list" class="display-members" name="member_id" required="true">
							<option value=""><?php _e('Select Member','gym_mgt');?></option>
								<?php
								$get_members = array('role' => 'member');
								$membersdata=get_users($get_members);
								 if(!empty($membersdata))
								 {
									foreach ($membersdata as $member)
									{
										if( $member->membership_status == "Continue")
										{
											?>
										<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
									<?php
										} 
									}
								 }?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="record_date"><?php _e('Record Date','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="record_date" class="form-control  validate[required]" type="text" userid="<?php echo get_current_user_id();?>" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="record_date" 
							value="<?php if($view){ echo $result->record_date;}elseif(isset($_POST['record_date'])){ echo $_POST['record_date'];}?>" readonly>
						</div>
					</div>	
					<!--nonce-->
					<?php wp_nonce_field( 'save_workout_nonce' ); ?>
					<!--nonce-->					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="workout_id"><?php _e('Workout','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8 workout_area">
						<div class='work_out_datalist'><div class='col-sm-10'><span class='col-md-10'><?php _e('Select Record Date For Today Workout','gym_mgt');?></span></div></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="note"><?php _e('Note','gym_mgt');?></label>
						<div class="col-sm-8">
							<textarea id="note" class="form-control validate[custom[address_description_validation]]" name="note" maxlength="150"><?php if($view){echo $result->note; }elseif(isset($_POST['note'])) echo $_POST['note']; ?> </textarea>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($view){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_workout" class="btn btn-success"/>
					</div>
				</form><!--WORKOUT FORM END-->
			</div><!--PANEL BODY DIV END-->
			<?php 
		}
}
?>