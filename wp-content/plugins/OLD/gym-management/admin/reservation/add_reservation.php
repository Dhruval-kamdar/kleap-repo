<?php ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#reservation_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php  echo get_option('gmgt_datepicker_format'); ?>";
		$('#event_date').datepicker(
		{
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
} );
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		  <div class="category_list"></div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<?php 	
if($active_tab == 'addreservation')
{
	$reservation_id=0;
	if(isset($_REQUEST['reservation_id']))
		$reservation_id=$_REQUEST['reservation_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_reservation->MJ_gmgt_get_single_reservation($reservation_id);
		}
		?>
        <div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="reservation_form" action="" method="post" class="form-horizontal" id="reservation_form"><!--RESERVATION FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="reservation_id" value="<?php echo $reservation_id;?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="event_name"><?php _e('Event Name','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="event_name" class="form-control validate[required,custom[popup_category_validation]] text-input" type="text" maxlength="100" value="<?php if($edit){ echo $result->event_name;}elseif(isset($_POST['event_name'])) echo $_POST['event_name'];?>" name="event_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="event_date"><?php _e('Event Date','gym_mgt');?></label>
					<div class="col-sm-8">
						<input id="event_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="event_date" 
						value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->event_date);  }elseif(isset($_POST['event_date'])){ echo $_POST['event_date']; }else{ echo MJ_gmgt_getdate_in_input_box(date('Y-m-d'));}?>" readonly>
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_group_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="event_place"><?php _e('Event Place','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					
						<select class="form-control validate[required]" name="event_place" id="event_place">
						<option value=""><?php _e('Select Event Place','gym_mgt');?></option>
						<?php 
						
						if(isset($_REQUEST['event_place']))
							$category =$_REQUEST['event_place'];  
						elseif($edit)
							$category =$result->place_id;
						else 
							$category = "";
						
						$mambership_category=MJ_gmgt_get_all_category('event_place');
						if(!empty($mambership_category))
						{
							foreach ($mambership_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
							}
						}
						?>
						
						</select>
					</div>
					<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="event_place"><?php _e('Add Or Remove','gym_mgt');?></button></div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="starttime"><?php _e('Start Time','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-2">
					<?php 
					if($edit)
					{
						$start_time_data = explode(":", $result->start_time);						
					}
					?>
						 <select name="start_time" class="form-control validate[required]">
						 <option value=""><?php _e('Start Time','gym_mgt');?></option>
						 <?php 
							for($i =0 ; $i <= 12 ; $i++)
							{
							?>
								<option value="<?php echo $i;?>" <?php  if($edit) selected($start_time_data[0],$i);  ?>><?php echo $i;?></option>
							<?php
							}
						 ?>
						 </select>
					</div>
					<div class="col-sm-2">
						 <select name="start_min" class="form-control validate[required]">
						  <?php 
							foreach(MJ_gmgt_minute_array() as $key=>$value)
							{?>
							<option value="<?php echo $key;?>" <?php  if($edit) selected($start_time_data[1],$key);  ?>><?php echo $value;?></option>
							<?php
							}
						 ?>
						 </select>
					</div>
					<div class="col-sm-2">
						 <select name="start_ampm" class="form-control validate[required]">
							<option value="am" <?php  if($edit) if(isset($start_time_data[2])) selected($start_time_data[2],'am');  ?>><?php _e('am','gym_mgt');?></option>
							<option value="pm" <?php  if($edit) if(isset($start_time_data[2])) selected($start_time_data[2],'pm');  ?>><?php _e('pm','gym_mgt');?></option>
						 </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="weekday"><?php _e('End Time','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-2">
					<?php 
					if($edit)
					{
						$end_time_data = explode(":", $result->end_time);
					}
					?>
					 <select name="end_time" class="form-control validate[required]">
					  <option value=""><?php _e('End Time','gym_mgt');?></option>
							 <?php 
								for($i =0 ; $i <= 12 ; $i++)
								{
								?>
								<option value="<?php echo $i;?>" <?php  if($edit) selected($end_time_data[0],$i);  ?>><?php echo $i;?></option>
								<?php
								}
							 ?>
						</select>
					</div>
					<div class="col-sm-2">
						 <select name="end_min" class="form-control validate[required]">
						  <?php 
							foreach(MJ_gmgt_minute_array() as $key=>$value)
							{?>
							<option value="<?php echo $key;?>" <?php  if($edit) selected($end_time_data[1],$key);  ?>><?php echo $value;?></option>
							<?php
							} ?>
						 </select>
					</div>					
					<div class="col-sm-2">
						<select name="end_ampm" class="form-control validate[required]">
                         	<option value="am" <?php  if($edit) if(isset($end_time_data[2])) selected($end_time_data[2],'am');  ?> ><?php _e('am','gym_mgt');?></option>
                            <option value="pm" <?php  if($edit) if(isset($end_time_data[2]))selected($end_time_data[2],'pm');  ?>><?php _e('pm','gym_mgt');?></option>
                         </select>
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
							if($edit)
								$staff_data=$result->staff_id;
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
				</div>				
				<div class="col-sm-offset-2 col-sm-8">					
					<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_group" class="btn btn-success"/>
				</div>				
			</form><!--RESERVATION FORM END-->
        </div><!--PANEL BODY END-->
<?php 
}
?>