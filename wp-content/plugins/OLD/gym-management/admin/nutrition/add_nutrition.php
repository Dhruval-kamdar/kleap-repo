<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{
	$(".display-members").select2();
	$('#nutrition_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	       
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('.start_date').datepicker({
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
			$('.end_date').datepicker('setStartDate', new Date($(this).val()));
		});
	   
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('.end_date').datepicker({
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
			$('.start_date').datepicker('setEndDate', new Date($(this).val()));
		});
} );
</script>
<?php 	
if($active_tab == 'addnutrition')
{
	$nutrition_id=0;
	$edit=0;
	$member_id=0;
	if(isset($_REQUEST['workoutmember_id']))
	{
		$member_id=$_REQUEST['workoutmember_id'];
	}			 
	if(isset($_REQUEST['workouttype_id']))
		$workouttype_id=$_REQUEST['workouttype_id'];
		if(isset($_REQUEST['workoutmember_id']))
		{
			$edit=1;
			$workoutmember_id=$_REQUEST['workoutmember_id'];			
			$nutrition_logdata=MJ_gmgt_get_user_nutrition($workoutmember_id);			
		}
		?>
        <div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="nutrition_form" action="" method="post" class="form-horizontal" id="nutrition_form"><!--Nutrition FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="nutrition_id" value="<?php echo $nutrition_id;?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
					<div class="col-sm-8">
						<?php if(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}?>
						<select id="member_list" class="display-members" name="member_id" required="true">
							<option value=""><?php _e('Select Member','gym_mgt');?></option>
								<?php $get_members = array('role' => 'member');
								$membersdata=get_users($get_members);
								 if(!empty($membersdata))
								 {
									foreach ($membersdata as $member){
										if( $member->membership_status == "Continue")
											{?>
										<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
									<?php } }
								 }?>
						</select>
					</div>
			    </div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_nutrition_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Start Date','gym_mgt');?><span class="require-field">*</span></label>
					
					<div class="col-sm-3">
					<input id="Start_date" class="start_date form-control validate[required] text-input" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text" 
					value="<?php if(isset($_POST['start_date'])){echo $_POST['start_date'];}?>" name="start_date" readonly>
						
					</div>
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('End Date','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
					<input id="end_date" class="datepicker form-control validate[required] text-input end_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text" value="<?php if(isset($_POST['end_date'])){echo $_POST['end_date'];}?>" name="end_date" readonly>
						
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
						  <label><input type="checkbox" value="" name="day[]" value="<?php echo $key;?>" id="<?php echo $key;?>" data-val="day"><?php echo $name; ?> </label>
						</div>
					</div>
					<?php
					}
					?>
					</div>	
				</div>
				<div class="form-group">					
					<div class="col-sm-12">		
						<label class="col-sm-2 control-label" for="notice_content"><?php _e('Nutrition Details','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-md-8 activity_list">
							<label class="activity_title checkbox">				  		
							<strong>
								<input type="checkbox" value="" name="avtivity_id[]" value="breakfast" class="nutrition_check" 
								id="breakfast"  activity_title = "" data-val="nutrition_time"><?php _e('Break Fast','gym_mgt');?></strong></label>	
								<div id="txt_breakfast"></div>
								<label class="activity_title checkbox">
								<strong>
								<input type="checkbox" value="" name="avtivity_id[]" value="lunch" class="nutrition_check" 
								id="lunch"  activity_title = "" data-val="nutrition_time"><?php _e('Lunch','gym_mgt');?></strong></label>
								<div id="txt_lunch"></div>
								<label class="activity_title checkbox"><strong>
								<input type="checkbox" value="" name="avtivity_id[]" value="dinner" class="nutrition_check" 
								id="dinner"  activity_title = "" data-val="nutrition_time"><?php _e('Dinner','gym_mgt');?></strong></label>	
								<div id="txt_dinner"></div>							
							<div class="clear"></div>
						</div>
			     	</div>
			    </div>
				<div class="col-sm-offset-2 col-sm-8">
					<div class="form-group">
						<div class="col-md-8">
							<input type="button" value="<?php _e('Step-1 Add Nutrition','gym_mgt');?>" name="save_nutrition" id="add_nutrition" class="btn btn-success"/>
						</div>
					</div>
				</div>
			    <div id="display_nutrition_list" style="clear: both;"></div>
			    <div class="clear"></div>
				</hr>
				<div class="col-sm-offset-2 col-sm-8 schedule-save-button ">
					<input type="submit" value="<?php if($edit){ _e('Step 2 Save Nutrition Plan','gym_mgt'); }else{ _e('Step 2 Save Nutrition Plan','gym_mgt');}?>" name="save_nutrition" class="btn btn-success"/>
				</div>
			</form><!--Nutrition FORM END-->
        </div><!--PANEL BODY DIV END-->
<?php 
}
if(isset($nutrition_logdata))
foreach($nutrition_logdata as $row)
{
	$all_logdata=MJ_gmgt_get_nutritiondata($row->id); 
	$arranged_workout=MJ_gmgt_set_nutrition_array($all_logdata);
	?>
		<div class="workout_<?php echo $row->id;?> workout-block"><!--WORKOUT BLOCK DIV START-->
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-calendar"></i> 
				<?php
				_e('Start From ','gym_mgt');
				echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->start_date)."</span>";
				_e(' To ','gym_mgt');
				echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->expire_date); 
				?> </h3>
				<span class="removenutrition badge badge-delete pull-right" id="<?php echo $row->id;?>">X</span>	 
			</div>
			<div class="panel panel-white"><!--PANEL WHITE DIV START-->
				<?php
				if(!empty($arranged_workout))
				{
					?>
					<div class="work_out_datalist_header">
						<div class="col-md-3 col-sm-3 col-xs-3">  
							<strong><?php _e('Day Name','gym_mgt');?></strong>
						</div>
						<div class="col-md-9 col-sm-9 col-xs-9">
							<span class="col-md-3 hidden-xs"><?php _e('Time','gym_mgt');?></span>
							<span class="col-md-6"><?php _e('Description','gym_mgt');?></span>					
						</div>
					</div>
					<?php 
					foreach($arranged_workout as $key=>$rowdata)
					{
						?>
						<div class="work_out_datalist">
							<div class="col-md-3 col-sm-3 col-xs-12 day_name">  
								<?php echo $key;?>
							</div>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<?php
								foreach($rowdata as $row)
								{	
									echo $row."<br>";									
								} 
								?>
							</div>
						</div>
				<?php } 
				}
				?>
			</div><!--PANEL WHITE DIV END-->
		</div><!--WORKOUT BLOCK DIV END-->
<?php
}	
?>