<?php 
$class_id =0;
?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#product_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
	$('.curr_date').datepicker(
	{
		endDate: '+0d',
		autoclose: true
	});	

$('.checkAll').change(function()
{
    var state = this.checked;
    state? $(':checkbox').prop('checked',true):$(':checkbox').prop('checked',false);
    state? $(this).next('b').text('Uncheck All') :$(this).next('b').text('Check All')
});	

} );
</script>
<?php
if($active_tab == 'staff_attendence') 
{
	?>
	<style>
		.status 
		{
		  margin-right: -15px;
		  margin-left: 30px;
		  padding-top: 10px;
		  
		}						
	</style>
	<div class="panel-body"> <!-- PANEL BODY DIV START-->
		<form method="post">          
			<div class="form-group col-md-3">
				<label class="col-sm-2 control-label" for="tcurr_date"><?php _e('Date','gym_mgt');?></label>			
					<input class="form-control curr_date" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"	value="<?php if(isset($_POST['tcurr_date'])) echo $_POST['tcurr_date']; 
					else
						echo  MJ_gmgt_getdate_in_input_box(date("Y-m-d"));;?>" name="tcurr_date" readonly>			
			</div>
			<!--nonce-->
			<?php wp_nonce_field( 'staff_attendence_nonce' ); ?>
			<!--nonce-->
			 <div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" value="<?php _e('Take/View  Attendance','gym_mgt');?>" name="staff_attendence"  class="btn btn-success"/>
			</div>
		 </form>
	 </div> <!-- PANEL BODY DIV END-->
	<div class="clearfix"> </div>		  
	<?php 
	if(isset($_REQUEST['staff_attendence']) || isset($_REQUEST['save_staff_attendence']))
	{
	  $past_attendance=get_option('gym_enable_past_attendance'); ?>

		<div class="panel-body"> <!-- PANEL BODY DIV START--> 
			<form method="post">           
				<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
				<input type="hidden" name="tcurr_date" value="<?php echo $_POST['tcurr_date'];?>" />
				 <div class="panel-heading">
					<h4 class="panel-title"><?php _e('Staff Attendance','gym_mgt');?> , 
					<?php _e('Date')?> :  <?php echo MJ_gmgt_getdate_in_input_box(MJ_gmgt_get_format_for_db($_POST['tcurr_date']));?></h4>
				 </div>
				 <div class="status">
				<?php 					
					$date=MJ_gmgt_get_format_for_db($_POST['tcurr_date']);
					$i=1;
					$teacher = get_users(array('role'=>'staff_member'));
					foreach ($teacher as $user) 
					{
						$check_attendance = $obj_attend->MJ_gmgt_check_staff_attendence($user->ID,$date);
						$attendanc_status = "Present";
						if(!empty($check_attendance))
						{
							$attendanc_status = $check_attendance->status;
							 
						}
						echo '<tr>';  
						echo '<tr>';
						echo '<td>['.$i.']</td>';
						echo '<td><span>' .$user->first_name.' '.$user->last_name. '</span></td>';
						?>
						<td><label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>
						<?php _e('Present','gym_mgt');?></label>
						<label class="radio-inline "> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>
						<?php _e('Absent','gym_mgt');?></label></td><?php 
						
						echo '</tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						$i++;
					}
					?>
					</div>
					<?php
					
					if($past_attendance == "yes")
					{ ?>
						<div class="form-group status">
							  <label class="radio-inline">
									<input type="radio" name="status" value="Present" checked="checked"/> <?php _e('Present','gym_mgt');?>
							  </label>
							  <label class="radio-inline">
								  <input type="radio" name="status" value="Absent" /> <?php _e('Absent','gym_mgt');?><br />
							  </label>
						</div>
					<?php
					}
					else
					{
						if($date == date("Y-m-d"))
						{?> 
								<div class="form-group status">
								  <label class="radio-inline">
								  <input type="radio" name="status" value="Present" checked="checked"/> <?php _e('Present','gym_mgt');?>
								  </label>
								  <label class="radio-inline">
								  <input type="radio" name="status" value="Absent" /> <?php _e('Absent','gym_mgt');?><br />
								  </label>
								</div>
						<?php 
						}
					}?>
					<div class="col-md-12">
						<table class="table">
							<tr>
								<?php
								if($past_attendance == "yes")
								{ ?>
									<th width="50px"><input type="checkbox" name="selectall" class="checkAll" id="selectall"/></th>
								<?php }
								else
								{
									if($date == date("Y-m-d"))
									{?> 
									   <th width="50px"><input type="checkbox" name="selectall" class="checkAll" id="selectall"/></th>
									  <?php
									}
									else 
									{
									?>
									   <th width="70px"><?php _e('Status','gym_mgt');?></th>
									<?php 
									}
								}
								?>
								<th width="250px"><?php _e('Staff Member Name','gym_mgt');?></th>
								<?php
								if($past_attendance == "yes")
								{ ?>
									<th width="70px"><?php _e('Status','gym_mgt');?></th>
								<?php 
								}
								else
								{
									if($date == date("Y-m-d"))
									{?> 
										<th width="70px"><?php _e('Status','gym_mgt');?></th>
									<?php
									}
									else
									{
									?>
										<th width="70px"></th>
									<?php
									}	
								}?>
							</tr>
							<?php
							$teacher = get_users(array('role'=>'staff_member'));
							
							$date=MJ_gmgt_get_format_for_db($_REQUEST['tcurr_date']);
							
							foreach ( $teacher as $user )
							{								
								$date=MJ_gmgt_get_format_for_db($_REQUEST['tcurr_date']);
								$check_result=$obj_attend->MJ_gmgt_check_staff_attendence($user->ID,$date);
									
								echo '<tr>';
								if($past_attendance == "yes")
								{ ?>
								<td class="checkbox_field">
								<span><input type="checkbox" class="checkbox1" name="attendence[]" value="<?php echo $user->ID; ?>" <?php if($check_result=='true'){ echo "checked=\'checked\'"; } ?> /></span>
								</td>
								
								<?php
								}
								else
								{
									if($date== date("Y-m-d"))
									{?> 
									<td class="checkbox_field">
								<span><input type="checkbox" class="checkbox1" name="attendence[]" value="<?php echo $user->ID; ?>" <?php if($check_result=='true'){ echo "checked=\'checked\'"; } ?> /></span>
									</td>
									<?php
									}
									else 
									{
										?>
										<td><?php if($check_result=='true') _e('Present','gym_mgt'); else _e('Absent','gym_mgt');?></td>
										<?php 
									}
								}
								echo '<td><span>' .$user->first_name.' '.$user->last_name.' </span></td>';
									if(!empty($check_result)){ 
										 echo '<td><span>' .$check_result->status.'</span></td>';
									}
									else {echo '<td>&nbsp;</td>';}
								
								
								echo '</tr>';
									
							}?>
						</table>
					</div>
					<div class="cleatrfix"></div>
					<div class="col-sm-8">    
					<?php
						if($past_attendance == "yes")
						{ ?>
							<input type="submit" value="<?php _e('Save Attendance','gym_mgt');?>" name="save_staff_attendence" class="btn btn-success" />
						<?php }
						else
						{
						if($date == date("Y-m-d")){?>       	    	
						<input type="submit" value="<?php _e('Save Attendance','gym_mgt');?>" name="save_staff_attendence" class="btn btn-success" />
						<?php }
						}?>
					</div>
			</form>
		</div> <!-- PANEL BODY DIV END-->
		<?php 
	}
}?>