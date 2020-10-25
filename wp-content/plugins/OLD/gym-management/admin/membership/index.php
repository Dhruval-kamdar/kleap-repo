<?php 
$obj_membership=new MJ_Gmgtmembership;
$obj_activity=new MJ_Gmgtactivity;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'membershiplist';
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
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNNER DIV START-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 	
	//SAVE MEMBERSHIP DATA
	if(isset($_POST['save_membership']))
	{	
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_membership_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$txturl=$_POST['gmgt_membershipimage'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{	
					$result=$obj_membership->MJ_gmgt_add_membership($_POST,$_POST['gmgt_membershipimage']);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_membership_type&tab=membershiplist&message=2');
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
				$txturl=$_POST['gmgt_membershipimage'];
				$ext=MJ_gmgt_check_valid_extension($txturl);
				if(!$ext == 0)
				{
					$result=$obj_membership->MJ_gmgt_add_membership($_POST,$_POST['gmgt_membershipimage']);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=gmgt_membership_type&tab=membershiplist&message=1&membershipid='.$result);
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
	//DELETE SELECTED MEMBERSHIP DATA
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_membership=$obj_membership->MJ_gmgt_delete_membership($id);
				if($delete_membership)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_membership_type&tab=membershiplist&message=3');
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
	//ADD Activity DATA
	if(isset($_POST['add_activities']))
	{
		$membershipid='&tab=membershiplist&message=1';
		if(isset($_POST['membership_id']))
			$membershipid="&tab=view-activity&membership_id=".$_POST['membership_id']."&message=1";
		$result=$obj_activity->MJ_gmgt_add_membership_activities($_POST);	
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_membership_type'.$membershipid);
		}
	}
	//DELETE MEMBERSHIP DATA	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_membership->MJ_gmgt_delete_membership($_REQUEST['membership_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_membership_type&tab=membershiplist&message=3');
		}
	}
	//Delete Activity DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete-activity')
	{
		$membershipid='&tab=membershiplist&message=3';
		if(isset($_REQUEST['membership_id']))
			$membershipid="&tab=view-activity&membership_id=".$_REQUEST['membership_id']."&message=3";
		$result=$obj_activity->MJ_gmgt_delete_membership_activity($_REQUEST['assign_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_membership_type'.$membershipid);
		}
	}	
	$valtemp=0;
	$newmembershipid=0;
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{ 
			$valtemp=$_REQUEST['message'];
			$newmembershipid=isset($_REQUEST['membershipid'])?$_REQUEST['membershipid']:0;
			?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					_e('Membership inserted successfully.','gym_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Membership updated successfully.",'gym_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Membership deleted successfully.','gym_mgt');
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
							<a href="?page=gmgt_membership_type&tab=membershiplist" class="nav-tab <?php echo $active_tab == 'membershiplist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Membership List', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=gmgt_membership_type&tab=addmembership&&action=edit&membership_id=<?php echo $_REQUEST['membership_id'];?>" class="nav-tab <?php echo $active_tab == 'addmembership' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Membership', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_membership_type&tab=addmembership" class="nav-tab <?php echo $active_tab == 'addmembership' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Membership', 'gym_mgt'); ?></a>  
							<?php  }
							if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'view-activity'){ ?>
							 <a href="?page=gmgt_membership_type&tab=view-activity&membership_id=<?php echo $_REQUEST['membership_id'];?>" class="nav-tab <?php echo $active_tab == 'view-activity' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('View Activity', 'gym_mgt'); ?></a>
							<?php } ?>
						</h2><!--NAV TAB WRAPPER MENU END-->
						 <?php 
						//Membership List//
						if($active_tab == 'membershiplist')
						{ 
							?>	
							<script type="text/javascript">
							jQuery(document).ready(function() {
								//-- Data table list ---//
								jQuery('#membership_list').DataTable({
									"responsive": true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[
												  {"bSortable": false},
												  {"bSortable": false},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": false}],
											language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
									});
									//-- add activity pop up --- //
									var tempval=<?php echo $valtemp;?>;
									if(tempval==1){
									swal({
													title: "Successfully inserted!",
													text: "Do you Want to Add New Activity?",
													type: "warning",
													showCancelButton: true,
													confirmButtonColor: '#22baa0',
													confirmButtonText: 'Yes',
													cancelButtonText: "No",
													closeOnConfirm: false,
													closeOnCancel: true
												},
													function(isConfirm){
													if (isConfirm){
														window.location.href = "<?php echo admin_url().'admin.php?page=gmgt_activity&tab=addactivity&membership_id='.$newmembershipid; ?>";
													} else {
														tempval=0;
													 window.location.href = "<?php echo admin_url().'admin.php?page=gmgt_membership_type&tab=membershiplist';?>";
													}
												});
									}
									
									jQuery('.select_all').on('click', function(e)
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
									jQuery('.sub_chk').change(function($)
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
							<form name="memership_list" id="memership_list" action="" method="post"><!--MEMBERSHIP LIST FORM START-->
								<div class="panel-body"><!--PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="membership_list" class="display" cellspacing="0" width="100%"><!--MEMBERSHIP LIST TABLE START-->
											<thead>
												<tr>													
													<th><input type="checkbox" class="select_all"></th>
													<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership Short Code', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Membership Amount', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
													<th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
													<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
													<th> <?php _e( 'Tax', 'gym_mgt' ) ;?>(%)</th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Membership Short Code', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
													<th><?php _e( 'Membership Amount', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
													<th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
													<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
													<th> <?php _e( 'Tax', 'gym_mgt' ) ;?>(%)</th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											 <?php $membershipdata=$obj_membership->MJ_gmgt_get_all_membership();
											
											 if(!empty($membershipdata))
											 {
												foreach ($membershipdata as $retrieved_data){
													
													if($retrieved_data->install_plan_id == 0)
													{
														$plan_id="";
													}
													else
													{
														$plan_id=get_the_title( $retrieved_data->install_plan_id );
													}
														?>
												<tr>												  
													<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->membership_id; ?>"></td>
													<td class="user_image"><?php $userimage=$retrieved_data->gmgt_membershipimage;
																
															if(empty($userimage))
															{
																	echo '<img src='.get_option( 'gmgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
															}
															else
															{
																echo '<img src='.$userimage.' height="25px" width="25px" class="img-circle"/>';
															}
													?></td>
													<td class="membershipname"><a href="?page=gmgt_membership_type&tab=addmembership&action=edit&membership_id=<?php echo $retrieved_data->membership_id;?>"><?php echo $retrieved_data->membership_label;?></a></td>
													<td class="membershipshortcode"><?php echo "[MembershipCode id=".$retrieved_data->membership_id."]";?></td>
													<td class="membershiperiod"><?php echo $retrieved_data->membership_length_id;?></td>
													<td class=""><?php echo $retrieved_data->membership_amount;?></td>
													<td class="installmentplan"><?php   echo $retrieved_data->installment_amount." ".$plan_id;?></td>
													<td class="signup_fee"><?php echo $retrieved_data->signup_fee;?></td>
													<td class=""><?php if(!empty($retrieved_data->tax)) { echo MJ_gmgt_tax_name_by_tax_id_array($retrieved_data->tax); }else{ echo '-'; } ?></td>
													<td class="action"> 
													<a href="?page=gmgt_membership_type&tab=addmembership&action=edit&membership_id=<?php echo $retrieved_data->membership_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
													<a href="?page=gmgt_membership_type&tab=membershiplist&action=delete&membership_id=<?php echo $retrieved_data->membership_id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
													<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->membership_id?>" type="<?php echo 'view_membership';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
													<a href="?page=gmgt_membership_type&tab=view-activity&membership_id=<?php echo $retrieved_data->membership_id?>" class="btn btn-success"> 
													<?php _e('View Activities', 'gym_mgt' );?></a>
													
													</td>
												   
												</tr>
												<?php } 
												
											}?>
											</tbody>
										</table><!--MEMBERSHIP LIST TABLE END-->
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY DIV END-->
							</form><!--MEMBERSHIP LIST FORM END-->
						<?php 
						}						
						if($active_tab == 'addmembership')
						{
							require_once GMS_PLUGIN_DIR. '/admin/membership/add_membership.php';
						}
						if($active_tab == 'view-activity')
						{
						   require_once GMS_PLUGIN_DIR. '/admin/membership/view-activity.php';
						} 
						?>
					</div><!--PANEL BODY DIV END-->
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
	</div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNNER DIV END-->