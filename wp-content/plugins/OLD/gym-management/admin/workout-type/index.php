<?php 
$obj_class=new MJ_Gmgtclassschedule;
$obj_activity=new MJ_Gmgtactivity;
$obj_workouttype=new MJ_Gmgtworkouttype;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'workouttypelist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important">
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	if(isset($_POST['save_workouttype']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_workouttype_nonce' ) )
		{	
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{						
				$result=$obj_workouttype->MJ_gmgt_add_workouttype($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_workouttype&tab=workouttypelist&message=2');
				}
					
					
			}
			else
			{
				$result=$obj_workouttype->MJ_gmgt_add_workouttype($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_workouttype&tab=workouttypelist&message=1');
					}
				
				}
			
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
				$result=$obj_workouttype->MJ_gmgt_delete_workouttype($_REQUEST['workouttype_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_workouttype&tab=workouttypelist&message=3');
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
			_e('Workout deleted successfully','gym_mgt');
		?></div></p><?php
				
		}
	}
	?>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
							<a href="?page=gmgt_workouttype&tab=workouttypelist" class="nav-tab <?php echo $active_tab == 'workouttypelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Assigned Workout', 'gym_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view' )
							{?>
								<a href="?page=gmgt_workouttype&tab=addworkouttype&action=view&workoutmember_id=<?php echo $_REQUEST['workoutmember_id'];?>" class="nav-tab <?php echo $active_tab == 'addworkouttype' ? 'nav-tab-active' : ''; ?>">
								<?php _e('View Assigned Workout', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_workouttype&tab=addworkouttype" class="nav-tab <?php echo $active_tab == 'addworkouttype' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Assign Workout', 'gym_mgt'); ?></a>
								
							<?php  
							}
							?>
						</h2>
						<?php						
						if($active_tab == 'workouttypelist')
						{ 
							?>	
							<script type="text/javascript">
								$(document).ready(function() 
								{
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
								} );
						   </script>
							<form name="wcwm_report" action="" method="post"><!--WORKOUT TYPE LIST FORM START-->
								<div class="panel-body"><!--PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="assignworkout_list" class="display" cellspacing="0" width="100%"><!--WORKOUT TYPE LIST TABLE START-->
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
														{
															echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
														}
													?>
													</td>
													<td class="member">
														<a href="?page=gmgt_workouttype&tab=addworkouttype&action=edit&workoutmember_id=<?php echo $retrieved_data->ID;?>">
														<?php $user=get_userdata($retrieved_data->ID);
														$display_label=$user->display_name;
														$memberid=get_user_meta($retrieved_data->ID,'member_id',true);
															if($memberid)
																$display_label.=" (".$memberid.")";
														echo $display_label;?></a>
														</td>
													<td class="member-goal"><?php $intrestid=get_user_meta($retrieved_data->ID,'intrest_area',true);
													echo get_the_title($intrestid);?></td>
													
													<td class="action"> 
														<a href="?page=gmgt_workouttype&tab=addworkouttype&action=view&workoutmember_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default">
														<i class="fa fa-eye"></i> <?php _e('View Workouts', 'gym_mgt');?></a>
													</td>
												</tr>
												<?php 
												} 
											}
											?>
											</tbody>
										</table><!--WORKOUT TYPE LIST TABLE END-->
									</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY DIV END-->
							</form><!--WORKOUT TYPE LIST FORM END-->
						</div><!--PANEL BODY DIV END-->
						<?php 
					}
					if($active_tab == 'addworkouttype')
					{
						require_once GMS_PLUGIN_DIR. '/admin/workout-type/add_workout_type.php';
					}
					?>
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
	</div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->