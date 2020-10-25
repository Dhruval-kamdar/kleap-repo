<?php 
$obj_group=new MJ_Gmgtgroup;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'grouplist';
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
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNNER DIV START-->
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE GROUP DATA
	if(isset($_POST['save_group']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_group_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$txturl=$_POST['gmgt_groupimage'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{	
					$result=$obj_group->MJ_gmgt_add_group($_POST,$_POST['gmgt_groupimage']);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_group&tab=grouplist&message=2');
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
				$txturl=$_POST['gmgt_groupimage'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$result=$obj_group->MJ_gmgt_add_group($_POST,$_POST['gmgt_groupimage']);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_group&tab=grouplist&message=1');
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
		}
	}
	//DELETE GROUP DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_group->MJ_gmgt_delete_group($_REQUEST['group_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_group&tab=grouplist&message=3');
		}
	}
	//DELETE SELECTED GROUP DATA
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_group=$obj_group->MJ_gmgt_delete_group($id);
				
			}
			if($delete_group)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_group&tab=grouplist&message=3');
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
		{
			?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Group added successfully.','gym_mgt');
			?></p></div>
			<?php 
		}
		elseif($message == 2)
		{?>
			<div id="message" class="updated below-h2 "><p><?php
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
			?></div></p>
			<?php				
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12"><!-- COL 12 DIV START-->
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper"><!-- NAV TAB WRAPPER MENU  START-->
							<a href="?page=gmgt_group&tab=grouplist" class="nav-tab <?php echo $active_tab == 'grouplist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Group List', 'gym_mgt'); ?>
							</a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
								<a href="?page=gmgt_group&tab=addgroup&&action=edit&group_id=<?php echo $_REQUEST['group_id'];?>" class="nav-tab <?php echo $active_tab == 'addgroup' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Edit Group', 'gym_mgt'); ?></a>  
								<?php 
							}
							else
							{?>
							  <a href="?page=gmgt_group&tab=addgroup" class="nav-tab <?php echo $active_tab == 'addgroup' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Group', 'gym_mgt'); ?></a>
							<?php  }?>
						</h2><!-- NAV TAB WRAPPER MENU END-->
						<?php 						
						if($active_tab == 'grouplist')
						{ 
						?>	
							<script type="text/javascript">
							$(document).ready(function() {
								jQuery('#group_list').DataTable({
									"responsive": true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[
												  {"bSortable": false},
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
							<form name="wcwm_report" action="" method="post"><!-- GROUP LIST FORM START-->
								<div class="panel-body"><!-- PANEL BODY DIV START-->
									<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
										<table id="group_list" class="display" cellspacing="0" width="100%"><!-- GROUP LIST TABLE START-->
											<thead>
												<tr>
													<th><input type="checkbox" class="select_all"></th>
													<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Group Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Group Description', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Total Group Members', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Group Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Group Description', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Total Group Members', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
												<?php 
												$groupdata=$obj_group->MJ_gmgt_get_all_groups();
												if(!empty($groupdata))
												{
													foreach ($groupdata as $retrieved_data)
													{
													?>
														<tr>
															<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->id; ?>"></td>
															<td class="user_image">
															<?php 
																if($retrieved_data->gmgt_groupimage == '')
																{
																  echo '<img src='.get_option( 'gmgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
																}
																else
																{
																 echo '<img src='.$retrieved_data->gmgt_groupimage.' height="25px" width="25px" class="img-circle"/>';
																}
															?>
															</td>	
															<td class="membershipname"><a href="?page=gmgt_group&tab=addgroup&action=edit&group_id=<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->group_name;?></a></td>
															<td class=""><?php if(!empty($retrieved_data->group_description)) { echo $retrieved_data->group_description; }else{ echo '-'; } ?></td>
															<td class="allmembers"><?php echo $obj_group->MJ_gmgt_count_group_members($retrieved_data->id);?></td>
															
															<td class="action"> 
																<a href="#" class="btn btn-success view_details_popup" id="<?php echo $retrieved_data->id?>" type="<?php echo 'view_group';?>"> <?php _e('View', 'gym_mgt' ) ;?></a>
																<a href="?page=gmgt_group&tab=addgroup&action=edit&group_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
																<a href="?page=gmgt_group&tab=grouplist&action=delete&group_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
																onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
																<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
															</td>
														   
														</tr>
													<?php 
													} 
												}?>
											</tbody>
										</table><!-- GROUP TABLE END-->
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!-- TABLE RESPONSIVE DIV END-->
								</div><!-- PANEL BODY DIV END-->
							</form><!-- GROUP LIST FORM END-->
						 <?php 
						}
						if($active_tab == 'addgroup')
						{
						  require_once GMS_PLUGIN_DIR. '/admin/group/add_group.php';
						}						
						?>
					</div><!-- PANEL BODY DIV END-->
	            </div><!-- PANEL WHITE DIV END-->
	        </div><!-- COL 12 DIV END-->
        </div><!-- ROW DIV END-->
    </div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNNER DIV END-->