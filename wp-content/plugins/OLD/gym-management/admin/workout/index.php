<?php 
$obj_workouttype=new MJ_Gmgtworkouttype;
$obj_workout=new MJ_Gmgtworkout;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'workoutlist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data">
			 </div>
	    </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV START-->	
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE WORKOUT DATA
	if(isset($_POST['save_workout']))
	{	

		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_workout_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				if(!empty($_POST['workouts_array']))
				{
					$result=$obj_workout->MJ_gmgt_add_workout($_POST);
				}
				else
				{
				?>
					<div id="message" class="updated below-h2">
					<p><p><?php _e('Today Can Not Assign Workout.','gym_mgt');?></p></p>
					</div>
				<?php	
				}			
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_workout&tab=workoutlist&message=2');
				}
			}
			else
			{	
				$exists_record=MJ_gmgt_check_user_workouts($_POST['member_id'],$_POST['record_date']);
				if($exists_record==0)
				{
					if(!empty($_POST['workouts_array']))
					{
						$result=$obj_workout->MJ_gmgt_add_workout($_POST);
					}
					else
					{
					?>
						<div id="message" class="updated below-h2">
						<p><p><?php _e('Today Can Not Assign Workout.','gym_mgt');?></p></p>
						</div>
					<?php	
					}
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_workout&tab=workoutlist&message=1');
					}
				}
				else
				{?>
					<div id="message" class="updated below-h2">
					<p><p><?php _e('Workout is already available for today.','gym_mgt');?></p></p>
					</div>
		  <?php }			
			}	
		}	
	}
	//DELETE WORKOUT DATA	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_workout->MJ_gmgt_delete_workout($_REQUEST['daily_workout_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_workout&tab=workoutlist&message=3');
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
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Measurement added successfully.','gym_mgt');
		?></div></p><?php
				
		}
	}
	//SAVE MEASUREMENT DATA
	if(isset($_POST['save_measurement']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_measurement_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$txturl=$_POST['gmgt_progress_image'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$result=$obj_workout->MJ_gmgt_add_measurement($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_workout&tab=workoutlist&message=4');
					}
				}			
				else
				{ ?>
					<div id="message" class="updated below-h2 ">
					<p>
						<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
					</p></div>				 
					<?php 
				}		
			}
			else
			{
				$txturl=$_POST['gmgt_progress_image'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{				
					$result=$obj_workout->MJ_gmgt_add_measurement($_POST);
				
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_workout&tab=workoutlist&message=1');
					}
				}			
				else
				{ 
				
				?>
					<div id="message" class="updated below-h2 ">
					<p>
						<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
					</p></div>				 
					<?php 
				}					
			}
				
		}
	}
	?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->	
		<div class="row"><!--ROW DIV START-->	
			<div class="col-md-12"><!--COL 12 DIV START-->	
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->	
					<div class="panel-body"><!--PANEL BODY DIV START-->	
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU START-->	
							<a href="?page=gmgt_workout&tab=workoutlist" class="nav-tab <?php echo $active_tab == 'workoutlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Workout List', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view' && $active_tab == 'addworkout')
							{?>
							<a href="?page=gmgt_workout&tab=addworkout&action=view&workoutmember_id=<?php echo $_REQUEST['workoutmember_id'];?>" class="nav-tab <?php echo $active_tab == 'addworkout' ? 'nav-tab-active' : ''; ?>">
							<?php _e('View Workout Log', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_workout&tab=addworkout" class="nav-tab <?php echo $active_tab == 'addworkout' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Workout Log', 'gym_mgt'); ?></a>
								
							<?php  }?>
							
							
							<a href="?page=gmgt_workout&tab=addmeasurement" class="nav-tab <?php echo $active_tab == 'addmeasurement' ? 'nav-tab-active' : ''; ?>">
							<?php 
							  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'addmeasurement')
									{
							echo __('Edit Measurement', 'gym_mgt'); }
							else 
					        {
							 echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Measurement', 'gym_mgt');
							}?>
							</a>
						</h2><!--NAV TAB WRAPPER MENU END-->	
						<?php 						
						if($active_tab == 'workoutlist')
						{ 
							?>	
							<script type="text/javascript">
							$(document).ready(function()
							{
							jQuery('#workout_list').DataTable({
								"responsive": true,
								"order": [[ 0, "asc" ]],
								"aoColumns":[
											  {"bSortable": false},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": false}],
										language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
								});
							} );
							</script>
							<form name="wcwm_report" action="" method="post"><!--WORKOUT LIST FORM START-->	
								<div class="panel-body"><!--PANEL BODY DIV START-->	
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV STRAT-->	
										<table id="workout_list" class="display" cellspacing="0" width="100%"><!--WORKOUT LIST TABLE START-->	
											<thead>
												<tr>
													<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Joining Date', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Expire Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Joining Date', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Expire Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											<?php
											//GET ALL MEMBER DATA
											$get_members = array('role' => 'member');
											$membersdata=get_users($get_members);
											if(!empty($membersdata))
											{
												foreach ($membersdata as $retrieved_data)
												{
													?>
													<tr>
														<td class="user_image"><?php $uid=$retrieved_data->ID;
															$userimage=get_user_meta($uid, 'gmgt_user_avatar', true);
															if(empty($userimage))
															{
																echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
															}
															else
																echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
														?>
														</td>
														<td class="membername"><a href="?page=gmgt_workout&tab=addworkout&action=view&workoutmember_id=<?php echo $retrieved_data->ID;?>">
														<?php $user=get_userdata($retrieved_data->ID);
														$display_label=$user->display_name;
														$memberid=get_user_meta($retrieved_data->ID,'member_id',true);
															if($memberid)
																$display_label.=" (".$memberid.")";
															echo $display_label;?></a></td>
														<td><?php echo MJ_gmgt_get_membership_name($retrieved_data->membership_id);?></td>
														<td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box($retrieved_data->begin_date); }else{ echo "--"; }?></td>
														
														<td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box(MJ_gmgt_check_membership($retrieved_data->ID)); }else{ echo "--"; }?>
														</td>	
														<td class="action"> 
															<a href="?page=gmgt_workout&tab=addworkout&action=view&workoutmember_id=<?php echo $retrieved_data->ID;?>" class="btn btn-success"> <?php _e('View', 'gym_mgt' ) ;?></a>
															<a href="#" class="btn btn-default view-measurement-popup" data-val="<?php echo $retrieved_data->ID;?>"><i class="fa fa-eye"></i> <?php _e('View Measurement', 'gym_mgt' ) ;?></a>												
														</td>
													</tr>
													<?php
												} 
											}
											?>
											</tbody>
										</table><!--WORKOUT LIST TABLE END-->	
									</div><!--TABLE RESPONSIVE DIV END-->	
								</div><!--PANEL BODY DIV END-->	
							</form><!--WORKOUT LIST FORM END-->	
							<?php 
						}
						if($active_tab == 'addworkout')
						{
							require_once GMS_PLUGIN_DIR. '/admin/workout/add_workout.php';
						}
						if($active_tab == 'addmeasurement')
						{
							require_once GMS_PLUGIN_DIR. '/admin/workout/add_measurement.php';
						}
						?>
					</div><!--PANEL BODY DIV END-->	
              	</div><!--PANEL WHITE DIV END-->	
	        </div><!--COL 12 DIV END-->	
        </div><!--ROW DIV END-->	
    </div><!--MAIN WRAPPER DIV END-->	
</div><!--PAGE INNER DIV END-->	