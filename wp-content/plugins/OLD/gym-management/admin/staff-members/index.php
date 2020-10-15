<?php 
$obj_user=new MJ_Gmgtmember;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'staff_memberlist';
?>
<!-- POP up code -->
<div class="popup-bg" style="min-height:1631px !important">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			 </div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV STRAT-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>	
	<?php 
	//SAVE STAFF MEMBER DATA
	if(isset($_POST['save_staff']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_staff_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			if($_POST['email'] == $_POST['hidden_email'])
			{
				$txturl=$_POST['gmgt_user_avatar'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$result=$obj_user->MJ_gmgt_gmgt_add_user($_POST);
			
					if($result)
					{
						wp_redirect ( admin_url() . 'admin.php?page=gmgt_staff&tab=staff_memberlist&message=2');
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
				if( !email_exists( $_POST['email'] ))
				{
					$txturl=$_POST['gmgt_user_avatar'];
					$ext=MJ_gmgt_check_valid_extension($txturl);
					if(!$ext == 0)
					{
						$result=$obj_user->MJ_gmgt_gmgt_add_user($_POST);
				
						if($result)
						{
							wp_redirect ( admin_url() . 'admin.php?page=gmgt_staff&tab=staff_memberlist&message=2');
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
					?>
				<div id="message" class="updated below-h2">
					<p><p><?php _e('Email id exists already.','gym_mgt');?></p></p>
				</div>							
				<?php 
				}
			}
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] ))
			{
				$txturl=$_POST['gmgt_user_avatar'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$result=$obj_user->MJ_gmgt_gmgt_add_user($_POST);
						
					if($result)
					{
						wp_redirect ( admin_url() . 'admin.php?page=gmgt_staff&tab=staff_memberlist&message=1');
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
			{?>
				<div id="message" class="updated below-h2">
				<p><p><?php _e('Username Or Email id exists already.','gym_mgt');?></p></p>
				</div>
						
	  <?php }
		}
			
	}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_user->MJ_gmgt_delete_usedata($_REQUEST['staff_member_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=gmgt_staff&tab=staff_memberlist&message=3');
		}
	}
	
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_staff_member=$obj_user->MJ_gmgt_delete_usedata($id);
				
			}
			if($delete_staff_member)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_staff&tab=staff_memberlist&message=3');
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
					_e('Staff Member Added successfully.','gym_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Staff Member updated successfully.",'gym_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Staff Member deleted successfully.','gym_mgt');
		?></div></p><?php
				
		}
	}
		
	?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->
		<div class="row"><!--ROW DIV START-->
			<div class="col-md-12"><!--COL 12 DIV START-->
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->
					<div class="panel-body"><!--PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU START-->
							<a href="?page=gmgt_staff&tab=staff_memberlist" class="nav-tab <?php echo $active_tab == 'staff_memberlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Staff Member List', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=gmgt_staff&tab=add_staffmember&&action=edit&staff_member_id=<?php echo $_REQUEST['staff_member_id'];?>" class="nav-tab <?php echo $active_tab == 'add_staffmember' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Staff Member', 'gym_mgt'); ?></a>  
							<?php 
							}
							elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
							{ ?>
								
							<a href="?page=gmgt_staff&tab=view_staffmember&action=view&staff_member_id=<?php echo $_REQUEST['staff_member_id'];?>" class="nav-tab <?php echo $active_tab == 'view_staffmember' ? 'nav-tab-active' : ''; ?>">
							<?php _e('View Staff Member', 'gym_mgt'); ?></a>  
								<?php 
							}
							else
							{?>
								<a href="?page=gmgt_staff&tab=add_staffmember" class="nav-tab <?php echo $active_tab == 'add_staffmember' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Staff Member', 'gym_mgt'); ?></a>  
							<?php  }?>
							
						</h2><!--NAV TAB WRAPPER MENU END-->
						<?php						
						if($active_tab == 'staff_memberlist')
						{ 
							?>	
							<script type="text/javascript">
								$(document).ready(function()
								{
								 jQuery('#staff_list').DataTable({
									"responsive": true,
									 "order": [[ 1, "asc" ]],
									 "aoColumns":[
												  {"bSortable": false},
												  {"bSortable": false},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bVisible": true},	                 
												  {"bSortable": false}
											   ],
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
							<form name="wcwm_report" action="" method="post"><!--Staff MEMBER LIST FORM START-->
								<div class="panel-body"><!--PANEL BODY DIV START-->
										<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
											<table id="staff_list" class="display" cellspacing="0" width="100%"><!--Staff MEMBER LIST TABLE START-->
												<thead>
													<tr>
													   <th><input type="checkbox" class="select_all"></th>
														<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
														<th><?php _e( 'Staff Member Name', 'gym_mgt' ) ;?></th>
														<th><?php _e( 'Role', 'gym_mgt' ) ;?></th>
														<th> <?php _e( 'Staff Member Email', 'gym_mgt' ) ;?></th>
														<th> <?php _e( 'Mobile No', 'gym_mgt' ) ;?></th>
														<th> <?php _e( 'Specialization', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th></th>
														<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
														<th><?php _e( 'Staff Member Name', 'gym_mgt' ) ;?></th>
														<th><?php _e( 'Role', 'gym_mgt' ) ;?></th>
														<th> <?php _e( 'Staff Member Email', 'gym_mgt' ) ;?></th>
														<th> <?php _e( 'Mobile No', 'gym_mgt' ) ;?></th>
														<th> <?php _e( 'Specialization', 'gym_mgt' ) ;?></th>
														<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
													 <?php 
													 //GET Staff MEMBER DATA
													 $get_staff = array('role' => 'Staff_member');
														$staffdata=get_users($get_staff);
													if(!empty($staffdata))
													{
														foreach ($staffdata as $retrieved_data)
														{
															?>
															<tr>
															   <td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->membership_id; ?>"></td>
																<td class="user_image"><?php $uid=$retrieved_data->ID;
																			$userimage=get_user_meta($uid, 'gmgt_user_avatar', true);
																		if(empty($userimage))
																		{
																						echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
																		}
																		else
																			echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
																?></td>
																<td class="name"><a href="?page=gmgt_staff&tab=add_staffmember&action=edit&staff_member_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
																<td class="department"><?php 
																$postdata=get_post($retrieved_data->role_type);
																if(isset($postdata))
																	echo $postdata->post_title;?>
																</td>
																<td class="email"><?php echo $retrieved_data->user_email;?></td>
																<td class="mobile"><?php echo $retrieved_data->mobile;?></td>
																<?php
																$specilization_array=explode(',',$retrieved_data->activity_category);
																$specilization_name_array=array();
																if(!empty($specilization_array))
																{
																	foreach ($specilization_array as $data)
																	{
																		$specilization_name_array[]=get_the_title($data);
																	}	
																}
																?>
																<td class=""><?php echo implode(',',$specilization_name_array); ?></td>
																<td class="action"> 
																	<a href="?page=gmgt_staff&tab=view_staffmember&action=view&staff_member_id=<?php echo $retrieved_data->ID?>" class="btn btn-success"> <?php _e('View', 'gym_mgt' ) ;?></a>
																	<a href="?page=gmgt_staff&tab=add_staffmember&action=edit&staff_member_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
																	<a href="?page=gmgt_staff&tab=staff_memberlist&action=delete&staff_member_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
																	onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
																	<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
																</td>
															   
															</tr>
															<?php 
														} 
													}?>
												</tbody>
											</table><!--Staff MEMBER LIST TABLE END-->
											<div class="print-button pull-left">
												<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
											</div>
										</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY DIV END-->
							</form><!--Staff MEMBER LIST FORM END-->
							 <?php 
						}
						if($active_tab == 'add_staffmember')
						{
						   require_once GMS_PLUGIN_DIR. '/admin/staff-members/add_staff.php';
						}						
						if($active_tab == 'view_staffmember')
						{
						   require_once GMS_PLUGIN_DIR. '/admin/staff-members/view_staffmember.php';
						}
						
						?>
					</div><!--PANEL BODY DIV END-->
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
	</div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->