<?php 
$obj_class=new MJ_Gmgtclassschedule;
$obj_membership=new MJ_Gmgtmembership;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'classlist';
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
		   <div class="category_list"></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNNER DIV START-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE Class DATA
	if(isset($_POST['save_class']))
	{	
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_class_nonce' ) )
		{	
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$time_validation=0;
				if($_POST['start_ampm'] == $_POST['end_ampm'] )
				{				
					if($_POST['end_time'] < $_POST['start_time'])
					{
						$time_validation='1';					
					
					}
					elseif($_POST['end_time'] ==  $_POST['start_time'] && $_POST['start_min'] > $_POST['end_min'] )
					{
						$time_validation='1';
					}				
				}
				else
				{
					if($_POST['start_ampm']!='am')
					{
						$time_validation='1';
					}	
				}	
				if($time_validation=='1')
				{
					?>
					<div id="message" class="updated below-h2 ">
					<p>
					<?php 
						_e('End Time should be greater than Start Time','gym_mgt');
					?></p>
					</div>
					<?php 
				}
				else
				{	
					$result=$obj_class->MJ_gmgt_add_class($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_class&tab=classlist&message=2');
					}
				}				
			}
			else
			{	
				$time_validation=0;
				if(!empty($_POST['start_time']))
				{
					foreach($_POST['start_time'] as $key=>$start_time)
					{
						if($_POST['start_ampm'][$key] == $_POST['end_ampm'][$key] )
						{				
							if($_POST['end_time'][$key] < $start_time)
							{
								$time_validation=$time_validation+1;
							
							}
							elseif($_POST['end_time'][$key] == $start_time && $_POST['start_min'][$key] > $_POST['end_min'][$key] )
							{
								$time_validation=$time_validation+1;
							}				
						}
						else
						{
							if($_POST['start_ampm'][$key]!='am')
							{
								$time_validation= $time_validation+1;
							}	
						}	
					}
				}
				
				if($time_validation > 0)
				{
					?>
					<div id="message" class="updated below-h2 ">
					<p>
					<?php 
						_e('End Time should be greater than Start Time','gym_mgt');
					?></p>
					</div>
					<?php 
				}
				else
				{ 			
					$result=$obj_class->MJ_gmgt_add_class($_POST);
		
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_class&tab=classlist&message=1');
					}
				}
			}
		}
	}
	//Delete Class DATA	AND Booked CLASS DATA	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
			if($_REQUEST['class_id'])
			{
				$result=$obj_class->MJ_gmgt_delete_class($_REQUEST['class_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_class&tab=classlist&message=3');
				}
			}else
			{
				$result=$obj_class->MJ_gmgt_delete_booked_class($_REQUEST['class_booking_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_class&tab=booking_list&message=4');
				}
				
			}
	}
	//Selected CLASS DATA Delete	
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_class=$obj_class->MJ_gmgt_delete_class($id);
				if($delete_class)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_class&tab=classlist&message=3');
				}
			}
		}
		else
		{
			echo '<script language="javascript">';
			echo 'alert("'.__('Please select at least one record.','gym_mgt').'")';
			echo '</script>';
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
				_e('Class added successfully.','gym_mgt');
			?></p></div>
			<?php 		
		}
		elseif($message == 2)
		{?>
			<div id="message" class="updated below-h2 "><p><?php
				_e("Class updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 			
		}
		elseif($message == 3) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('Class deleted successfully.','gym_mgt');
			?></div></p><?php				
		}
		elseif($message == 4) 
		{
				?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('Booked Class deleted successfully.','gym_mgt');
			?></div></p><?php				
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12"><!-- COL 12 DIV START-->
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->
					<div class="panel-body"><!-- PANEL BODY START-->
						<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU START-->
							<a href="?page=gmgt_class&tab=classlist" class="nav-tab <?php echo $active_tab == 'classlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Class List', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=gmgt_class&tab=addclass&action=edit&class_id=<?php echo $_REQUEST['class_id'];?>" class="nav-tab <?php echo $active_tab == 'addclass' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Class', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_class&tab=addclass" class="nav-tab <?php echo $active_tab == 'addclass' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Class', 'gym_mgt'); ?></a>
								
							<?php  } ?>
							<a href="?page=gmgt_class&tab=schedulelist" class="nav-tab <?php echo $active_tab == 'schedulelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Schedule List', 'gym_mgt'); ?></a>
							
							<a href="?page=gmgt_class&tab=booking_list" class="nav-tab <?php echo $active_tab == 'booking_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Booking List', 'gym_mgt'); ?></a>		
						   
						</h2><!-- NAV TAB WRAPPER MENU END-->
						 <?php 	
						if($active_tab == 'classlist')
						{ 						
							?>	
							<script type="text/javascript">
							$(document).ready(function() 
							{
								jQuery('#class_list').DataTable({
									"responsive": true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[
												  {"bSortable": false},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": false}],
										language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
									});
									
									$('.select_all').on('click', function(e)
											{
												 if($(this).is(':checked',true))  
												 {
													$(".sub_chk").prop('checked', true);  
												 }  
												 else  
												 {  
													$(".sub_chk").prop('checked',false);  
												 } 
											});
										
									$('.sub_chk').change(function()
									{ 
										if(false == $(this).prop("checked"))
										{ 
											$(".select_all").prop('checked', false); 
										}
										if ($('.sub_chk:checked').length == $('.sub_chk').length )
										{
											$(".select_all").prop('checked', true);
										}
								  });
							} );
							</script>
							<form name="wcwm_report" action="" method="post"><!-- CLASS LIST FORM START-->						
								<div class="panel-body"><!-- PANEL BODY DIV START-->
									<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
										<table id="class_list" class="display" cellspacing="0" width="100%"><!-- TABLE CLASS LIST START-->
											<thead>
												<tr>
												<th><input type="checkbox" class="select_all"></th>
												<th><?php  _e( 'Class Name', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Staff Name', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Starting Time', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Ending Time', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Day', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
												<th></th>
												<th><?php  _e( 'Class Name', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Staff Name', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Starting Time', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Ending Time', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Day', 'gym_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>								 
											<tbody>
											 <?php 										
												$classdata=$obj_class->MJ_gmgt_get_all_classes();
												 if(!empty($classdata))
												 {
													foreach ($classdata as $retrieved_data)
													{
													?>
													<tr>
														<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->class_id; ?>"></td>
														<td class="classname"><a href="?page=gmgt_class&tab=addclass&action=edit&class_id=<?php echo $retrieved_data->class_id;?>"><?php echo $retrieved_data->class_name;?></a></td>
														<td class="staff"><?php $userdata=get_userdata( $retrieved_data->staff_id );
														echo $userdata->display_name;?></td>
														<td class="starttime"><?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->start_time);?></td>
														<td class="endtime"><?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->end_time);?></td>
														<td class="day"><?php $days_array=json_decode($retrieved_data->day); 
														$days_string=array();
														if(!empty($days_array))
														{
															foreach($days_array as $day)
															{
																$days_string[]=substr($day,0,3);
															}
														}
														echo implode(", ",$days_string);
														?>
														</td>
														
														<td class="action"><a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->class_id?>" type="<?php echo 'view_class';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a> <a href="?page=gmgt_class&tab=addclass&action=edit&class_id=<?php echo $retrieved_data->class_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
														<a href="?page=gmgt_class&tab=classlist&action=delete&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
														<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
														
														</td>
													   
													</tr>
													<?php 
													}												
												}
												?>									 
											</tbody>										
										</table><!-- TABLE CLASS LIST END-->
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!-- TABLE RESPONSIVE DIV END-->		
								</div><!-- PANEL BODY DIV END-->								   
							</form><!-- CLASS LIST FORM END-->
						 <?php 
						}						
						if($active_tab == 'addclass')
						{
							require_once GMS_PLUGIN_DIR. '/admin/class-schedule/add_class.php';
						}
						if($active_tab == 'schedulelist')
						{
							require_once GMS_PLUGIN_DIR. '/admin/class-schedule/schedule_list.php';
						}
						if($active_tab == 'booking_list')
						{
						   require_once GMS_PLUGIN_DIR. '/admin/class-schedule/booking_list.php';
						}
						?>
					</div><!-- PANEL BODY DIV END-->	
				</div><!-- PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNNER DIV END-->