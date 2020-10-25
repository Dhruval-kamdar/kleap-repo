<?php $curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_activity=new MJ_Gmgtactivity;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'activitylist';
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
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
	}
}
//SAVE ACTIVITY DATA
if(isset($_POST['save_activity']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_activity_nonce' ) )
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
				
			$result=$obj_activity->MJ_gmgt_add_activity($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=activity&tab=activitylist&message=2');
			}
		}
		else
		{
			$result=$obj_activity->MJ_gmgt_add_activity($_POST);
				if($result)
				{
					wp_redirect ( home_url().'?dashboard=user&page=activity&tab=activitylist&message=1');
				}
		}
	}
}
//DELETE ACTIVITY DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=$obj_activity->MJ_gmgt_delete_activity($_REQUEST['activity_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=activity&tab=activitylist&message=3');
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
<script type="text/javascript">
$(document).ready(function() 
{
	jQuery('#activity_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	
					  {"bSortable": false}],
			language:<?php echo MJ_gmgt_datatable_multi_language();?>			  
		});
		$('#acitivity_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
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
<div class="panel-body panel-white"><!--PANEL WHITE DIV START-->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV TABS MENU START-->
		<li class="<?php if($active_tab=='activitylist'){?>active<?php }?>">
			<a href="?dashboard=user&page=activity&tab=activitylist" class="tab <?php echo $active_tab == 'activitylist' ? 'active' : ''; ?>">
			 <i class="fa fa-align-justify"></i> <?php _e('Activity List', 'gym_mgt'); ?></a>
		  </a>
		</li>	 
		<li class="<?php if($active_tab=='addactivity'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['activity_id']))
			{?>
				<a href="?dashboard=user&page=activity&tab=addactivity&action=edit&activity_id=<?php echo $_REQUEST['activity_id'];?>" class="nav-tab <?php echo $active_tab == 'addactivity' ? 'nav-tab-active' : ''; ?>">
				<i class="fa fa"></i> <?php _e('Edit  Activity', 'gym_mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{
				?>
				<a href="?dashboard=user&page=activity&tab=addactivity&&action=insert" class="tab <?php echo $active_tab == 'addactivity' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Activity', 'gym_mgt'); ?></a>
		 <?php } 
			}
		?>	  
	   </li>
	   <li class="<?php if($active_tab=='view_membership'){?>active<?php }?>">
		  <?php 
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view' && isset($_REQUEST['activity_id']))
			{
				?>
				<a href="?dashboard=user&page=activity&tab=view_membership&action=edit&activity_id=<?php echo $_REQUEST['activity_id'];?>" class="nav-tab <?php echo $active_tab == 'view_membership' ? 'nav-tab-active' : ''; ?>">
				<i class="fa fa-align-justify"></i> <?php _e('View Membership', 'gym_mgt'); ?></a>
			 <?php
			}
			 ?>	  
	   </li>
    </ul><!--NAV TABS MENU END-->
	<div class="tab-content"><!--TAB CONTENT DIV END-->
	<?php 
	if($active_tab == 'activitylist')
	{
		?>	
		<form name="wcwm_report" action="" method="post"><!--ACTIVITY LIST FORM START-->
			<div class="panel-body"><!--PANEL BODY DIV START-->
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
					<table id="activity_list" class="display" cellspacing="0" width="100%"><!--ACTIVITY LIST TABLE START-->
						<thead>
							<tr>
								<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
									
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
									   
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
								
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
								   
							</tr>
						</tfoot>
						<tbody>
							<?php 						
							if($user_access['own_data']=='1')
							{
								$user_id=get_current_user_id();							
								$activitydata=$obj_activity->MJ_gmgt_get_all_activity_by_activity_added_by($user_id);
							}
							else
							{
								$activitydata=$obj_activity->MJ_get_all_activity();
							}
													
							if(!empty($activitydata))
							{
								foreach ($activitydata as $retrieved_data)
								{
								?>
								<tr>
									<td class="activityname">
									<?php 
										if($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
										{
										?>
										<a href="?dashboard=user&page=activity&tab=addactivity&action=edit&activity_id=<?php echo $retrieved_data->activity_id;?>"><?php echo $retrieved_data->activity_title;?></a>
									   <?php 
									   }
									   else
									   {
										  ?>
										  <?php echo $retrieved_data->activity_title;?>
										<?php  
										} 
										?>
									</td>
									<td class="category"><?php echo get_the_title($retrieved_data->activity_cat_id);?></td>
									<td class="productquentity"><?php $user=get_userdata($retrieved_data->activity_assigned_to);
									echo $user->display_name;?></td>
									
									<td class="action"> 
									 <a href="?dashboard=user&page=activity&tab=view_membership&action=view&activity_id=<?php echo $retrieved_data->activity_id?>" class="btn btn-success"> <?php _e('View Membership', 'gym_mgt' ) ;?></a>
									<?php							
									if($user_access['edit']=='1')
									{
									?>
										<a href="?dashboard=user&page=activity&tab=addactivity&action=edit&activity_id=<?php echo $retrieved_data->activity_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{
									?>	
										<a href="?dashboard=user&page=activity&tab=activitylist&action=delete&activity_id=<?php echo $retrieved_data->activity_id;?>" class="btn btn-danger" 
										onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
										<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
									<?php
									}
									?>	
									</td>								  
								</tr>
								<?php
								} 
							}?>
						</tbody>
					</table><!--ACTIVITY LIST TABLE END-->
				</div><!--TABLE RESPONSIVE DIV END-->
			</div><!--PANEL BODY DIV END-->
		</form><!--ACTIVITY LIST FORM END-->
		<?php 
	}
	if($active_tab == 'addactivity')
	{
		$activity_id=0;
		if(isset($_REQUEST['activity_id']))
			$activity_id=$_REQUEST['activity_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_activity->MJ_gmgt_get_single_activity($activity_id);
				
			}?>
			<div class="panel-body"><!--PANEL BODY DIV START-->
				<form name="acitivity_form" action="" method="post" class="form-horizontal" id="acitivity_form"><!--ACTIVITY FORM START-->
					<?php 
					$action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="activity_id" value="<?php echo $activity_id;?>"  />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_category"><?php _e('Activity Category','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control activity_cat_to_staff" name="activity_cat_id" id="activity_category">
								<option value=""><?php _e('Select Activity Category','gym_mgt');?></option>
								<?php 
								if(isset($_REQUEST['activity_cat_id']))
									$category =$_REQUEST['activity_cat_id'];  
								elseif($edit)
									$category =$result->activity_cat_id;
								else 
									$category = "";
								
								$activity_category=MJ_gmgt_get_all_category('activity_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								}
								?>
							</select>
						</div>
						<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="activity_category"><?php _e('Add Or Remove','gym_mgt');?></button></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_title"><?php _e('Activity Title','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="activity_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->activity_title;}elseif(isset($_POST['activity_title'])) echo $_POST['activity_title'];?>" name="activity_title">
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_activity_nonce' ); ?>
					<!--nonce-->
					<div class="form-group">
						<label class="col-sm-2 control-label" for="staff_name"><?php _e('Assign to Staff Member','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">				
							<select name="staff_id" class="form-control validate[required] category_to_staff_list" id="staff_id">
								<option value=""><?php  _e('Select Staff Member ','gym_mgt');?></option>
								<?php 
								if($edit)
								{	
									$get_staff = array('role' => 'Staff_member');
									$staffdata=get_users($get_staff);
									$staff_data=$result->activity_assigned_to;
									if(!empty($staffdata))
									{
										foreach($staffdata as $staff)
										{
											$staff_specialization=explode(',',$staff->activity_category);
											if(in_array($result->activity_cat_id,$staff_specialization))
											{
												echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
											}
										}
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_activity" class="btn btn-success"/>
					</div>
				</form><!--ACTIVITY FORM END-->
			</div><!--PANEL BODY DIV END-->
     <?php 
	}	
	if($active_tab == 'view_membership')
	{
		?>
		<script type="text/javascript">
		$(document).ready(function() 
		{	
			jQuery('#membership_list').DataTable({
				"responsive": true,
				"order": [[ 1, "asc" ]],
				"aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true}],
						language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
				});
		});
		</script>
		<form name="wcwm_report" action="" method="post"> <!--MEMBERSHIP LIST FORM START-->   
			<div class="panel-body"><!--PANEL BODY DIV START-->   
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->   
					<table id="membership_list" class="display" cellspacing="0" width="100%"><!--MEMBERSHIP LIST TABLE START-->   
						<thead>
							<tr>
							<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Membership Amount', 'gym_mgt' ) ;?></th>
							  <th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
							  <th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?></th>
								<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?></th>
							   
							</tr>
						</thead>
						<tfoot>
							<tr>
							<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Membership Amount', 'gym_mgt' ) ;?></th>
							  <th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
							  <th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?></th>
								<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?></th>              
							</tr>
						</tfoot>
				 
						<tbody>
						 <?php 
						 if(isset($_REQUEST['activity_id']))
							$activity_id=$_REQUEST['activity_id'];
							$activity_membership_list = $obj_activity->MJ_gmgt_get_activity_membership($activity_id);
							
						 if(!empty($activity_membership_list))
						 {
							foreach ($activity_membership_list as $retrieved_data)
							{
								$obj_membership=new MJ_Gmgtmembership;
								$membership_data = $obj_membership->MJ_gmgt_get_single_membership($retrieved_data);				
								?>
								<tr>
									<td class="user_image"><?php $userimage=$membership_data->gmgt_membershipimage;
											if(empty($userimage))
											{
												echo '<img src='.get_option( 'gmgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
											}
											else
												echo '<img src='.$userimage.' height="25px" width="25px" class="img-circle"/>';
										?>
									</td>
									<td class="membershipname"><a href="?page=gmgt_membership_type&tab=addmembership&action=edit&membership_id=<?php echo $membership_data->membership_id;?>"><?php echo $membership_data->membership_label;?></a></td>
									<td class=""><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $membership_data->membership_amount; ?></td>
									<td class="membershiperiod"><?php echo $membership_data->membership_length_id;?></td>
									<td class="installmentplan"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $membership_data->installment_amount." ".get_the_title( $membership_data->install_plan_id );?></td>
									<td class="signup_fee"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $membership_data->signup_fee;?></td>             
								</tr>
								<?php 
							}			
						}
						?>     
						</tbody>        
					</table><!--MEMBERSHIP LIST TABLE END-->   
				</div><!--TABLE RESPONSIVE DIV END-->   
			</div><!--PANEL BODY DIV END-->   
		</form><!--MEMBERSHIP LIST FORM END-->   
		<?php
	}
	?>
  </div><!--TAB CONTENT DIV END-->
</div><!--PANEL WHITE DIV END-->