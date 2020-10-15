<?php 
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_group=new MJ_Gmgtgroup;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'grouplist';
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
//SAVE GROUP DATA
if(isset($_POST['save_group']))
{ 
    $nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_group_nonce' ) )
	{
	if(isset($_FILES['gmgt_groupimage']) && !empty($_FILES['gmgt_groupimage']) && $_FILES['gmgt_groupimage']['size'] !=0)
	{
			
		if($_FILES['gmgt_groupimage']['size'] > 0)
		{
			 $member_image=MJ_gmgt_load_documets($_FILES['gmgt_groupimage'],'gmgt_groupimage','pimg');
			 $member_image_url=content_url().'/uploads/gym_assets/'.$member_image;
		}
						
	}
	else
	{			
		if(isset($_REQUEST['hidden_upload_user_avatar_image']))
		{
			$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
		    $member_image_url=$member_image;
		}
	}
	$ext=MJ_gmgt_check_valid_extension($member_image_url);
		
	if(!$ext == 0)
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_group->MJ_gmgt_add_group($_POST,'');
			$returnans=$obj_group->MJ_gmgt_update_groupimage( $_REQUEST['group_id'],$member_image_url);
			if($returnans)
			{
				wp_redirect ( home_url().'?dashboard=user&page=group&tab=grouplist&message=2');
			}
			elseif($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=group&tab=grouplist&message=2');
			}
				
		}
		else
		{
			$result=$obj_group->MJ_gmgt_add_group($_POST,$member_image_url);
			if($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=group&tab=grouplist&message=1');
			}
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
//DELETE GROUP DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{	
	$result=$obj_group->MJ_gmgt_delete_group($_REQUEST['group_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=group&tab=grouplist&message=3');
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
				_e('Group inserted successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Group updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Group deleted successfully.','gym_mgt');
	?></div></p><?php
	}
}
?>
<script type="text/javascript">
$(document).ready(function() 
{
	jQuery('#group_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
					  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
		
					{"bSortable": false}],
					language:<?php echo MJ_gmgt_datatable_multi_language();?>	
		});
		$('#group_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
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
		<li class="<?php if($active_tab=='grouplist'){?>active<?php }?>">
			<a href="?dashboard=user&page=group&tab=grouplist" class="tab <?php echo $active_tab == 'grouplist' ? 'active' : ''; ?>">
			 <i class="fa fa-align-justify"></i> <?php _e('Group List', 'gym_mgt'); ?></a>
		  </a>
		</li>
	   <li class="<?php if($active_tab=='addgroup'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['group_id']))
			{?>
			<a href="?dashboard=user&page=group&tab=addgroup&&action=edit&group_id=<?php echo $_REQUEST['group_id'];?>" class="nav-tab <?php echo $active_tab == 'addgroup' ? 'nav-tab-active' : ''; ?>">
			 <i class="fa fa"></i> <?php _e('Edit Group', 'gym_mgt'); ?></a>
			 <?php }
			else
			{	
				if($user_access['add']=='1')
				{
				?>
					<a href="?dashboard=user&page=group&tab=addgroup&&action=insert" class="tab <?php echo $active_tab == 'addgroup' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Add Group', 'gym_mgt'); ?></a>
				<?php 	
				} 
			}
			?>
		</li>
	</ul><!--NAV TABS MENU END-->
	<div class="tab-content"><!--TAB CONTENT DIV START-->
		<?php 
		if($active_tab == 'grouplist')
		{ ?>	
			<div class="panel-body"><!--PANEL BODY DIV START-->
				<div class="table-responsive"><!--TABLE RESPONSIVE START-->
					<table id="group_list" class="display" cellspacing="0" width="100%"><!--TABLE GROUP LIST START-->
						<thead>
							<tr>
								<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Group Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Group Description', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Total Group Members', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Group Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Group Description', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Total Group Members', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
							</tr>
						</tfoot>
						<tbody>
						<?php
						if($obj_gym->role == 'member')
						{	
							if($user_access['own_data']=='1')
							{
								$user_id=get_current_user_id();
								$groupdata=$obj_group->MJ_gmgt_get_member_all_groups($user_id);			
							}
							else
							{
								$groupdata=$obj_group->MJ_gmgt_get_all_groups();
							}	
						}
						elseif($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
						{
							if($user_access['own_data']=='1')
							{
								$user_id=get_current_user_id();							
								$groupdata=$obj_group->MJ_gmgt_get_all_groups_by_created_by($user_id);			
							}
							else
							{
								$groupdata=$obj_group->MJ_gmgt_get_all_groups();
							}
						}
						if(!empty($groupdata))
						{
							foreach ($groupdata as $retrieved_data)
							{
							 ?>
								<tr>
									<td class="user_image"><?php $userimage=$retrieved_data->gmgt_groupimage;
												if(empty($userimage))
												{
													echo '<img src='.get_option( 'gmgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
												}
												else
													echo '<img src='.$userimage.' height="25px" width="25px" class="img-circle"/>';
										?>
									</td>
									<td class="membershipname">
									<?php if($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
										   {?>
										<a href="?dashboard=user&page=group&tab=addgroup&action=edit&group_id=<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->group_name;?></a>
										   <?php }
										   else
										   {?>
											  <?php echo $retrieved_data->group_name;?>
										   <?php }?>
									</td>
									<td class=""><?php if(!empty($retrieved_data->group_description)) { echo $retrieved_data->group_description; }else{ echo '-'; } ?></td>
									<td class="allmembers"><?php echo $obj_group->MJ_gmgt_count_group_members($retrieved_data->id);?>
									</td>
									<td class="action">
										<a href="#" class="btn btn-success view_details_popup" id="<?php echo $retrieved_data->id?>" type="view_group"> <?php _e('View', 'gym_mgt' ) ;?></a>
										<?php
										if($user_access['edit']=='1')
										{
										?>								
											<a href="?dashboard=user&page=group&tab=addgroup&action=edit&group_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
										 <?php
										}
										if($user_access['delete']=='1')
										{
										?>	
											<a href="?dashboard=user&page=group&tab=grouplist&action=delete&group_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
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
					</table><!--TABLE GROUP LIST END-->
				</div><!--TABLE RESPONSIVE DIV END-->
			</div><!--PANEL BODY DIV END-->
			<?php 
		}
		if($active_tab == 'addgroup')
		{
			$group_id=0;
			if(isset($_REQUEST['group_id']))
				$group_id=$_REQUEST['group_id'];
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_group->MJ_gmgt_get_single_group($group_id);
				}
				?>
				<div class="panel-body"><!--PANEL BODY DIV START-->
					<form name="group_form" action="" method="post" class="form-horizontal" id="group_form" enctype="multipart/form-data"><!--GROUP FORM START-->
						<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
						<input type="hidden" name="action" value="<?php echo $action;?>">
						<input type="hidden" name="group_id" value="<?php echo $group_id;?>"  />
						<div class="form-group">
							<label class="col-sm-2 control-label" for="group_name"><?php _e('Group Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="group_name" class="form-control validate[required,custom[popup_category_validation]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $result->group_name;}elseif(isset($_POST['group_name'])) echo $_POST['group_name'];?>" name="group_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for=""><?php _e('Group Description','gym_mgt');?></label>
							<div class="col-sm-8">
							<textarea name="group_description" class="form-control validate[custom[address_description_validation]]" maxlength="500" ><?php if($edit){ echo $result->group_description;}?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="photo"><?php _e('Group Image','gym_mgt');?></label>
							<div class="col-sm-2">
								<input type="text" id="gmgt_user_avatar_url" class="form-control" name="gmgt_groupimage" readonly 
								value="<?php if($edit)echo esc_url( $result->gmgt_groupimage );elseif(isset($_POST['gmgt_groupimage'])) echo $_POST['gmgt_groupimage']; ?>" />
							</div>	
							<div class="col-sm-3">
								<input type="hidden" name="hidden_upload_user_avatar_image" value="<?php if($edit){ echo $result->gmgt_groupimage;}elseif(isset($_POST['gmgt_groupimage'])) echo $_POST['gmgt_groupimage'];?>">
									 <input id="upload_user_avatar_image" name="gmgt_groupimage" onchange="fileCheck(this);" type="file" class="form-control file image_upload_change" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" />
							</div>
							<div class="clearfix"></div>
							<div class="col-sm-offset-2 col-sm-8">
								<div id="upload_user_avatar_preview" >
										 <?php if($edit) 
											{
											if($result->gmgt_groupimage == "")
											{?>
											<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
											<?php }
											else {
												?>
											<img class="image_preview_css" src="<?php if($edit)echo esc_url( $result->gmgt_groupimage ); ?>" />
											<?php 
											}
											}
											else {
												?>
												<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
												<?php 
											}?>
								</div>
							</div>
						</div>
						<!--nonce-->
						<?php wp_nonce_field( 'save_group_nonce' ); ?>
						<!--nonce-->
						<div class="col-sm-offset-2 col-sm-8">
							<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_group" class="btn btn-success"/>
						</div>
					</form><!--GROUP FORM END-->
				</div><!--PANEL BODY DIV END-->
			<?php 
		}
		?>
    </div><!--TAB CONTENT DIV END-->
</div><!--PANEL WHITE DIV END-->  
<script type="text/javascript">
function fileCheck(obj) 
{
	var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("<?php _e("Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.",'gym_mgt');?>");
		$(obj).val('');
	}	
}
</script>