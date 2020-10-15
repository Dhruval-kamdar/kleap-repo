<?php 
$obj_membership=new MJ_Gmgtmembership;
$obj_activity=new MJ_Gmgtactivity;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'activitylist';
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
		   <div class="category_list"> </div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE Activity DATA
	if(isset($_POST['save_activity']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce($nonce, 'save_activity_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{				
				$result=$obj_activity->MJ_gmgt_add_activity($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_activity&tab=activitylist&message=2');
				}
			}
			else
			{
				$result=$obj_activity->MJ_gmgt_add_activity($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_activity&tab=activitylist&message=1');
				}
			}
		}
	}
	//Delete ACTIVITY DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
			
		$result=$obj_activity->MJ_gmgt_delete_activity($_REQUEST['activity_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_activity&tab=activitylist&message=3');
		}
	}
	//selected activity delete//
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_activity=$obj_activity->MJ_gmgt_delete_activity($id);
			}
			if($delete_activity)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_activity&tab=activitylist&message=3');
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
				_e('Activity added successfully.','gym_mgt');
			?></p></div>
			<?php 			
		}
		elseif($message == 2)
		{?>
			<div id="message" class="updated below-h2 "><p><?php
				_e("Activity updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
			
		}
		elseif($message == 3) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('Activity deleted successfully.','gym_mgt');
			?></div></p>
			<?php				
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->   
		<div class="row"><!-- ROW DIV START-->   
			<div class="col-md-12"><!-- COL 12 DIV START-->   
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->   
					<div class="panel-body"><!-- PANEL BODY DIV START-->   
							<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU START-->   
								<a href="?page=gmgt_activity&tab=activitylist" class="nav-tab <?php echo $active_tab == 'activitylist' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Activity List', 'gym_mgt'); ?></a>
								
								<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{?>
								<a href="?page=gmgt_activity&tab=addactivity&&action=edit&activity_id=<?php echo $_REQUEST['activity_id'];?>" class="nav-tab <?php echo $active_tab == 'addactivity' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Edit Activity', 'gym_mgt'); ?></a>  
								<?php 
								}
								else
								{?>
									<a href="?page=gmgt_activity&tab=addactivity" class="nav-tab <?php echo $active_tab == 'addactivity' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Activity', 'gym_mgt'); ?></a>
									
								<?php  }
								 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
								{?>
								<a href="?page=gmgt_activity&tab=view_membership&&action=view&activity_id=<?php echo $_REQUEST['activity_id'];?>" class="nav-tab <?php echo $active_tab == 'view_membership' ? 'nav-tab-active' : ''; ?>"> <?php echo '<span class="dashicons dashicons-menu"></span> '.__('View Membership', 'gym_mgt'); ?>
								</a>  
								<?php 
								}
								?>
							</h2><!-- NAV TAB WRAPPER MENU END-->  
							 <?php 							
							if($active_tab == 'activitylist')
							{ 
							?>	
								<script type="text/javascript">
									$(document).ready(function() {
										jQuery('#activity_list').DataTable({
											"responsive": true,
											"order": [[ 1, "asc" ]],
											"aoColumns":[
														  {"bSortable": false},
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
								<form name="wcwm_report" action="" method="post"><!-- ACTIVITY FORM START-->
									<div class="panel-body"><!-- PANEL BODY DIV START-->
										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
											<table id="activity_list" class="display" cellspacing="0" width="100%"><!-- TABLE ACTIVITY LIST START-->
												<thead>
													<tr>
														<th><input type="checkbox" class="select_all"></th>
														<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
													   <th></th>
														<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
												<?php 
												$activitydata=$obj_activity->MJ_get_all_activity();
												if(!empty($activitydata))
												{
													foreach ($activitydata as $retrieved_data){ ?>
													<tr>
													   <td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->activity_id; ?>"></td>
														<td class="activityname"><a href="?page=gmgt_activity&tab=addactivity&action=edit&activity_id=<?php echo $retrieved_data->activity_id;?>"><?php echo $retrieved_data->activity_title;?></a></td>
														<td class="category"><?php echo get_the_title($retrieved_data->activity_cat_id);?></td>
														<td class="productquentity"><?php $user=get_userdata($retrieved_data->activity_assigned_to);
														echo $user->display_name;?></td>
														
														<td class="action"> <a href="?page=gmgt_activity&tab=view_membership&action=view&activity_id=<?php echo $retrieved_data->activity_id?>" class="btn btn-success"> <?php _e('View Membership', 'gym_mgt' ) ;?></a>
														<a href="?page=gmgt_activity&tab=addactivity&action=edit&activity_id=<?php echo $retrieved_data->activity_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
														<a href="?page=gmgt_activity&tab=activitylist&action=delete&activity_id=<?php echo $retrieved_data->activity_id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
														<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
														</td>
													</tr>
													<?php } 
												}?>
												</tbody>
											</table><!-- TABLE ACTIVITY LIST END-->
											<div class="print-button pull-left">
												 <input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
											</div>
										</div><!-- TABLE RESPONSIVE DIV END-->
									</div><!-- PANEL BODY DIV END-->
								</form><!-- ACTIVITY FORM END-->
							 <?php 
							}
							if($active_tab == 'addactivity')
							{
							   require_once GMS_PLUGIN_DIR. '/admin/activity/add_activity.php';
							}
							if($active_tab == 'view_membership')
							{
							   require_once GMS_PLUGIN_DIR. '/admin/activity/view_membership.php';
							}
							?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div><!-- COL 12 DIV END-->
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->