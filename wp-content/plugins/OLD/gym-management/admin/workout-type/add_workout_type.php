<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	$(".display-members").select2();
	$('#workouttype_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
			
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('#start_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   }).on('changeDate', function(){
			$('#end_date').datepicker('setStartDate', new Date($(this).val()));
		});
	   
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('#end_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   }).on('changeDate', function(){
			$('#start_date').datepicker('setEndDate', new Date($(this).val()));
		});
   
   
 $("#member_list").select2();
} );
</script>
<style>
.form-horizontal .checkbox, .form-horizontal .radio {
    min-height: 0px !important;
}
</style>
<?php 	
if($active_tab == 'addworkouttype')
{        	
	$workoutmember_id=0;
	$edit=0;
	if(isset($_REQUEST['workouttype_id']))
		$workouttype_id=$_REQUEST['workouttype_id'];
	if(isset($_REQUEST['workoutmember_id'])){
		$edit=1;
	$workoutmember_id=$_REQUEST['workoutmember_id'];				
	$workout_logdata=MJ_gmgt_get_userworkout($workoutmember_id);
}			
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit=1;
} 
?>
	<div class="col-md-12"><!--COL 12 DIV STRAT-->
		<div class="panel panel-white"><!--PANEL WHITE DIV STRAT-->
            <div class="panel-body"><!--PANEL BODY DIV STRAT-->
				<form name="workouttype_form" action="" method="post" class="form-horizontal" id="workouttype_form"><!--WORKOUT TYPE FORM STRAT-->
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="assign_workout_id" value="<?php //echo $workouttype_id;?>"  />	
				
					<div class="form-group">
						<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
						<div class="col-sm-8">
							<?php if($edit){ $member_id=$workoutmember_id; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
							<select id="member_list" class="display-members assigned_workout_member_id" name="member_id" required="true">
								<option value=""><?php _e('Select Member','gym_mgt');?></option>
									<?php $get_members = array('role' => 'member');
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
						<div class="col-sm-2">
						<a href="?page=gmgt_member&tab=addmember" class="btn btn-default"> <?php _e('Add Member','gym_mgt');?></a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="level_id"><?php _e('Level','gym_mgt');?></label>
						<div class="col-sm-8">				
							<select class="form-control" name="level_id" id="level_type">
								<option value=""><?php _e('Select Level','gym_mgt');?></option>
								<?php 
								
								if(isset($_REQUEST['level_id']))
									$category =$_REQUEST['level_id'];  
								elseif($edit)
									$category =$result->level_id;
								else 
									$category = "";
								
								$measurmentdata=MJ_gmgt_get_all_category('level_type');
								if(!empty($measurmentdata))
								{
									foreach ($measurmentdata as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								}
								?>					
							</select>
						</div>
						<div class="col-sm-2 add_category_padding_0">
							<button id="addremove" model="level_type"><?php _e('Add Or Remove','gym_mgt');?></button>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="description"><?php _e('Description','gym_mgt');?></label>
						<div class="col-sm-8">
							<textarea id="description" class="form-control validate[custom[address_description_validation]]" maxlength="150"  name="description"><?php if(isset($_POST['description'])) echo $_POST['description']; ?> </textarea>
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_workouttype_nonce' ); ?>
					<!--nonce-->					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="start_date"><?php _e('Start Date','gym_mgt');?> <span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input id="start_date" class="form-control validate[required]" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="start_date" 
							value="<?php if(isset($_POST['start_date'])){ echo $_POST['start_date'];}?>" readonly>
						</div>
						<label class="col-sm-2 control-label" for="end_date"><?php _e('End Date','gym_mgt');?> <span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input id="end_date" class="form-control validate[required]" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="last_date" 
							value="<?php if(isset($_POST['end_date'])){ echo $_POST['end_date'];}?>" readonly>
						</div>
					</div>	
					<div class="form-group">				
						<label class="col-sm-2 control-label" for="notice_content"><?php _e('Select Days','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">			
						<?php
						foreach (MJ_gmgt_days_array() as $key=>$name)
						{
						?>
						<div class="col-md-3" style="padding-left: 0px;">
							<div class="checkbox">
							  <label><input type="checkbox" class="validate[minCheckbox[3]] checkbox" value="" name="day[]" value="<?php echo $key;?>" id="<?php echo __($key,'gym_mgt');?>" data-val="day"><?php echo $name; ?> </label>
							</div>
						</div>
						<?php
						}
						?>
						</div>	
					</div>	
					<div class="form-group">					
						<div class="col-sm-12">		
							<label class="col-sm-2 control-label" for="notice_content"><?php _e('Workout Details','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-md-10 activity_list member_workout_activity" style="padding-left: 0px;">
								<?php 
								$activity_category=MJ_gmgt_get_all_category('activity_category');
								
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{	
										?>		
										<label class="activity_title"><strong><?php echo $retrive_data->post_title; ?></strong></label>					
										<?php 
										$activitydata =MJ_gmgt_get_activity_by_category($retrive_data->ID);
										foreach($activitydata as $activity)
										{ 
										?>
										<div class="checkbox child">
											<label class="col-sm-2" style="padding-top: 7px;padding-bottom: 7px;">
											<input type="checkbox"   value="" name="avtivity_id[]" value="<?php echo $activity->activity_id;?>" class="activity_check" 
											id="<?php echo $activity->activity_id;?>" data-val="activity" activity_title = "<?php echo $activity->activity_title; ?>">
											<?php echo $activity->activity_title; ?> 
											</label>
											<div id="reps_sets_<?php echo $activity->activity_id;?>" class="col-sm-10" style="padding:0px;"></div>
										</div>
										<div class="clear"></div>
											<?php 
										}
										?>
										<div class="clear"></div>
										<?php 
									}
								} ?>			
							</div>
						</div>
					</div>				
					<div class="col-sm-offset-2 col-sm-10">
						<div class="form-group">
							<div class="col-md-8">
								<input type="button" value="<?php _e('Step-1 Add Workout','gym_mgt');?>" name="sadd_workouttype" id="add_workouttype" class="btn btn-success"/>
							</div>
						</div>
					</div>	
					<div id="display_rout_list"></div>
					
					<div class="col-sm-offset-2 col-sm-8 schedule-save-button ">        	
						<input type="submit" value="<?php if($edit){ _e('Step-2 Save Workout','gym_mgt'); }else{ _e('Step-2 Save Workout','gym_mgt');}?>" name="save_workouttype" class="btn btn-success"/>
					</div>
				</form><!--WORKOUT TYPE FORM END-->
            </div><!--PANEL BODY DIV END-->
        </div>	<!--PANEL WHITE DIV END-->
			<?php
			if(isset($workout_logdata))
			foreach($workout_logdata as $row)
			{
				$all_logdata=MJ_gmgt_get_workoutdata($row->workout_id); //var_dump($workout_logdata);
				$arranged_workout=MJ_gmgt_set_workoutarray($all_logdata);
				?>
				<div class="workout_<?php echo $row->workout_id;?> workout-block"><!--WORKOUT BLOCK DIV START-->
					<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-calendar"></i> 
							
							<?php 
							_e('Start From ','gym_mgt');
							echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->start_date)."</span>";
							_e(' To ','gym_mgt');
							echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->end_date); 
							?> </h3>	
							<span class="removeworkout badge badge-delete pull-right" id="<?php echo $row->workout_id;?>">X</span>						
					</div>
					<div class="panel panel-white"><!--PANEL WHITE DIV START-->
						<?php
						if(!empty($arranged_workout))
						{
							?>
							<div class="work_out_datalist_header">
							<div class="col-md-2 col-sm-2">  
							<strong><?php _e('Day Name','gym_mgt');?></strong>
							</div>
							<div class="col-md-10 col-sm-10 hidden-xs">
							<span class="col-md-3"><?php _e('Activity','gym_mgt');?></span>
							<span class="col-md-3"><?php _e('Sets','gym_mgt');?></span>
							<span class="col-md-2"><?php _e('Reps','gym_mgt');?></span>
							<span class="col-md-2"><?php _e('KG','gym_mgt');?></span>
							<span class="col-md-2"><?php _e('Rest Time','gym_mgt');?></span>
							</div>
							</div>
							<?php 
							foreach($arranged_workout as $key=>$rowdata)
							{?>
								<div class="work_out_datalist">
									<div class="col-md-2 day_name">  
										<?php echo $key;?>
									</div>
									<div class="col-md-10 col-xs-12">
										<?php 
										foreach($rowdata as $row)
										{
												echo $row."<div class='clearfix'></div> <br>";
										} 
										?>
									</div>
								</div>
						 <?php
							} 
						}?>
				   </div><!--PANEL WHITE DIV END-->
			   </div><!--WORKOUT BLOCK DIV END-->
			<?php   
			}	
} 
?>