<?php
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_class=new MJ_Gmgtclassschedule;
$obj_workouttype=new MJ_Gmgtworkouttype;
$obj_activity=new MJ_Gmgtactivity;
$obj_workout=new MJ_Gmgtworkout;
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'workoutassignlist';
//access right
$user_access=MJ_gmgt_get_userrole_wise_page_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_gmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='view'))
		{
			if($user_access['add']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
	}
}
?>
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#assignworkout_list').DataTable({
		"responsive": true,
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
					  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
					 {"bSortable": false}],
				language:<?php echo MJ_gmgt_datatable_multi_language();?>		 
		});
	$(".display-members").select2();
} );
</script>
<?php 
//SAVE WORKOUT TYPE DATA
if(isset($_POST['save_workouttype']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce($nonce, 'save_workouttype_nonce' ))
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
				
			$result=$obj_workouttype->MJ_gmgt_add_workouttype($_POST);
			if($result)
			{
				wp_redirect ( home_url() .'?dashboard=user&page=assign-workout&tab=workoutassignlist&message=2');
			}
		}
		else
		{
			$result=$obj_workouttype->MJ_gmgt_add_workouttype($_POST);

			if($result)
			{
				wp_redirect ( home_url() .'?dashboard=user&page=assign-workout&tab=workoutassignlist&message=1');
			}
		}
	}
}
//Delete WORKOUT TYPE DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$result=$obj_workouttype->MJ_gmgt_delete_workouttype($_REQUEST['assign_workout_id']);
	if($result)
	{
		wp_redirect ( home_url() .'?dashboard=user&page=assign-workout&tab=workoutassignlist&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Workout added successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Workout updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Workout deleted successfully.','gym_mgt');
	?></div></p><?php
	}
}
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		    <div class="category_list"></div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white"><!-- PANEL BODY DIV START -->
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!-- NAV TABS MENU START -->
		<li class="<?php if($active_tab == 'workoutassignlist') echo "active";?>">
			<a href="?dashboard=user&page=assign-workout&tab=workoutassignlist">
				 <i class="fa fa-align-justify"></i> <?php _e('Assigned Workout', 'gym_mgt'); ?></a>
			</a>
		</li>	  
		<?php
		if($user_access['add']=='1')
		{
		?>
		 <li class="<?php if($active_tab == 'assignworkout') echo "active";?>">
			<a href="?dashboard=user&page=assign-workout&tab=assignworkout">
			<i class="fa fa-plus-circle"></i> <?php
			if(isset($_REQUEST['action']) && $_REQUEST['action'] =='view')
				_e('View Assigned Workout', 'gym_mgt'); 
			else
				_e('Assign Workout', 'gym_mgt'); 
			?></a> 
		  </li>	 
		<?php
		}
		?>	
	</ul><!-- NAV TABS MENU END -->
	<div class="tab-content"><!-- TAB CONTENT DIV START -->
    	<div class="tab-pane <?php if($active_tab == 'workoutassignlist') echo "fade active in";?>" id="workoutassignlist"><!-- TAB PANE DIV START -->
			<div class="panel-body"><!-- PANEL BODY DIV START -->
			    <?php 
				if($obj_gym->role == 'member')
				{
					$workout_logdata=MJ_gmgt_get_userworkout(get_current_user_id());
					if(!empty($workout_logdata))
					{
						foreach($workout_logdata as $row)
						{
							$all_logdata=MJ_gmgt_get_workoutdata($row->workout_id); 
							$arranged_workout=MJ_gmgt_set_workoutarray($all_logdata);
							?>
							<div class="workout_<?php echo $row->workout_id;?> workout-block"><!-- WORKOUT BLOCK DIV START -->
								<div class="panel-heading">
									<h3 class="panel-title"><i class="fa fa-calendar"></i> 
										<?php 
										_e('Start From ','gym_mgt');
										echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->start_date)."</span>";
										_e(' To ','gym_mgt');
										echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->end_date); 
										if($user_access['delete']=='1')
										{
										?>
											<span class="removenutrition badge badge-delete pull-right" id="<?php echo $row->workout_id;?>">X</span> 
										<?php 
										}
										?>
									</h3>						
								</div>
								<div class="panel panel-white"><!-- PANEL WHITE DIV START -->
									<?php
										if(!empty($arranged_workout))
										{
										?>
											<div class="work_out_datalist_header">
												<div class="col-md-4 col-sm-4">  
													<strong><?php _e('Day Name','gym_mgt');?></strong>
												</div>
												<div class="col-md-8 col-sm-8 hidden-xs">
													<span class="col-md-3"><?php _e('Activity','gym_mgt');?></span>
													<span class="col-md-3"><?php _e('Sets','gym_mgt');?></span>
													<span class="col-md-2"><?php _e('Reps','gym_mgt');?></span>
													<span class="col-md-2"><?php _e('KG','gym_mgt');?></span>
													<span class="col-md-2"><?php _e('Rest Time','gym_mgt');?></span>
												</div>
											</div>
											<?php 
											foreach($arranged_workout as $key=>$rowdata)
											{
												?>
												<div class="work_out_datalist">
													<div class="col-md-4 day_name">  
														<!--<?php echo $key;?>-->
														<?php _e($key,'gym_mgt');?>
													</div>
													<div class="col-md-8 col-xs-12">
														<?php 
														foreach($rowdata as $row)
														{
																echo $row."<br>";
														} ?>
													</div>
												</div>
											 <?php 
											} 
										}
										?>										
								</div><!-- PANEL WHITE DIV END -->
							</div><!-- WORKOUT BLOCK DIV END -->
				  <?php }
					}
					else
					{
						_e('No Any Assigned Workout Data','gym_mgt');
					}		
			    }
				else
				{
					?>
					<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
						<table id="assignworkout_list" class="display" cellspacing="0" width="100%"><!-- ASSIGNED WORKOUT TABLE START -->
							<thead>
								<tr>
									<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
									<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
									<th><?php  _e( 'Member Goal', 'gym_mgt' ) ;?></th>
									<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
									<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
									<th><?php  _e( 'Member Goal', 'gym_mgt' ) ;?></th>
									<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
								$get_members = array('role' => 'member');
								$membersdata=get_users($get_members);
								if(!empty($membersdata))
								{
									foreach ($membersdata as $retrieved_data){?>
									<tr>
										<td class="user_image"><?php $uid=$retrieved_data->ID;
													$userimage=get_user_meta($uid, 'gmgt_user_avatar', true);
												if(empty($userimage))
												{
													echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
												}
												else
												{
													echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
												}
										?></td>
										<td class="member">
										<?php if($obj_gym->role == 'staff_member'){?>
										<a href="#"><?php $user=get_userdata($retrieved_data->ID);
										$display_label=$user->display_name;
										$memberid=get_user_meta($retrieved_data->ID,'member_id',true);
											if($memberid)
												$display_label.=" (".$memberid.")";
											echo $display_label;?></a>
										<?php }
										else
										{?>
										<a href=""><?php $user=get_userdata($retrieved_data->ID);
										$display_label=$user->display_name;
										$memberid=get_user_meta($retrieved_data->ID,'member_id',true);
											if($memberid)
												$display_label.=" (".$memberid.")";
											echo $display_label;?></a>
									<?php	}
										?></td>
										<td class="member-goal"><?php $intrestid=get_user_meta($retrieved_data->ID,'intrest_area',true);
										echo get_the_title($intrestid);?></td>
										<td class="action"> 
										<?php if($obj_gym->role == 'staff_member'|| ($obj_gym->role == 'member' && $retrieved_data->ID==get_current_user_id())){?>
											<a href="?dashboard=user&page=assign-workout&tab=assignworkout&action=view&workoutmember_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default">
										<i class="fa fa-eye"></i></i> <?php _e('View Workouts', 'gym_mgt');?></a>
										<?php 
										}						
										
										?>
									   </td>
									</tr>
									<?php 
									} 
								}?>
							</tbody>
						</table><!-- ASSIGNED WORKOUT TABLE END -->
					</div><!-- TABLE RESPONSIVE DIV END-->
					<?php
				}
				?>
	    	</div><!-- PANEL BODY DIV END -->
	    </div><!-- TAB PANE DIV END -->
		<script type="text/javascript">
		$(document).ready(function() 
		{
			$("#member_list").select2();
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
		} );
		</script>
		<?php 	
			$workoutmember_id=0;
			$edit=0;
			if(isset($_REQUEST['workouttype_id']))
				$workouttype_id=$_REQUEST['workouttype_id'];
			if(isset($_REQUEST['workoutmember_id'])){
				$edit=1;
				$workoutmember_id=$_REQUEST['workoutmember_id'];
				$workout_logdata=MJ_gmgt_get_userworkout($workoutmember_id);
			}
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
			{
				$edit=1;					
			}
			?>	
			<div class="tab-pane <?php if($active_tab == 'assignworkout') echo "fade active in";?>"><!-- TAB PANE DIV START -->
			   <?php 
				if($obj_gym->role == 'staff_member' )
				{
					?>
					<div class="panel-body"><!-- PANEL BODY DIV START -->
						<form name="workouttype_form" action="" method="post" class="form-horizontal" id="workouttype_form"><!-- WORKOUT TYPE FORM START -->
							<?php 
							$action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
							<input type="hidden" name="action" value="<?php echo $action;?>">
							<input type="hidden" name="assign_workout_id" value=""  />
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
												{?>
												<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
											<?php } 
											}
										 }?>
								</select>
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
								<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="level_type"><?php _e('Add Or Remove','gym_mgt');?></button></div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="description"><?php _e('Description','gym_mgt');?></label>
								<div class="col-sm-8">
									<textarea id="description" class="form-control validate[custom[address_description_validation]]" maxlength="150" name="description"><?php if(isset($_POST['description'])) echo $_POST['description']; ?> </textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="start_date"><?php _e('Start Date','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-3">
									<input id="start_date" class="form-control validate[required]" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="start_date" 
									value="<?php if(isset($_POST['start_date'])){ echo $_POST['start_date'];}?>" readonly>
								</div>
								<label class="col-sm-2 control-label" for="end_date"><?php _e('End Date','gym_mgt');?><span class="require-field">*</span></label>
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
													<input type="checkbox" value="" name="avtivity_id[]" value="<?php echo $activity->activity_id;?>" class="activity_check" 
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
										}?>			
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
							<!--nonce-->
							<?php wp_nonce_field( 'save_workouttype_nonce' ); ?>
							<!--nonce-->
							<div id="display_rout_list"></div>
							<div class="col-sm-offset-2 col-sm-8 schedule-save-button ">
								<input type="submit" value="<?php if($edit){ _e('Step-2 Save Workout','gym_mgt'); }else{ _e('Step-2 Save Workout','gym_mgt');}?>" name="save_workouttype" class="btn btn-success"/>
							</div>
						</form><!-- WORKOUT TYPE FORM END -->
					</div><!-- PANEL BODY DIV END -->
					<?php 
				}
				if($obj_gym->role == 'staff_member'|| ($obj_gym->role == 'member' && $workoutmember_id==get_current_user_id()))
				{
					if(isset($workout_logdata))
					foreach($workout_logdata as $row)
					{
						$all_logdata=MJ_gmgt_get_workoutdata($row->workout_id); //var_dump($workout_logdata);
						$arranged_workout=MJ_gmgt_set_workoutarray($all_logdata);
						?>
						<div class="workout_<?php echo $row->workout_id;?> workout-block">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-calendar"></i> 
								<?php echo "Start From <span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->start_date)."</span> To <span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->end_date); ?>
								<?php
								if($user_access['delete']=='1')
								{
								?>
								<span class="removeworkout badge badge-delete pull-right" id="<?php echo $row->workout_id;?>">X</span>	
								<?php
								}
								?>
								</h3>						
							</div>	
							<div class="panel panel-white">
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
									{
										?>
										<div class="work_out_datalist">
											<div class="col-md-2 day_name">  
												<?php echo $key;?>
											</div>
											<div class="col-md-10 col-xs-12">
													<?php foreach($rowdata as $row){
															echo $row."<div class='clearfix'></div><br>";
													} ?>
											</div>
										</div>
							  <?php } 
								}?>
							</div>
						</div>
					<?php
					}						
				}	?>
			</div><!-- TAB PANE DIV END -->
	</div><!-- TAB CONTENT DIV END -->
</div><!-- PANEL BODY DIV END -->