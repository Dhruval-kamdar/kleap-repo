<?php 
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_membership=new MJ_Gmgtmembership;
$obj_class=new MJ_Gmgtclassschedule;
$obj_group=new MJ_Gmgtgroup;
$obj_member=new MJ_Gmgtmember;
$role="member";
$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
//access right
$user_access=MJ_gmgt_get_userrole_wise_page_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_gmgt_access_right_page_not_access_message();
		die;
	}
}
//SAVE MEMBER DATA
if(isset($_POST['save_member']))		
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_member_nonce' ) )
	{
	if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
	{
		
		if($_FILES['upload_user_avatar_image']['size'] > 0)
		{
		  $member_image=MJ_gmgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
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
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
			
		$result=$obj_member->MJ_gmgt_gmgt_add_user($_POST);	
			$returnans=update_user_meta( $result,'gmgt_user_avatar',$member_image_url);
		if($result)
		{
			wp_redirect ( home_url().'?dashboard=user&page=member&tab=memberlist&message=2');
		}	
	}
	else
	{
		if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) 
		{	
			$result=$obj_member->MJ_gmgt_gmgt_add_user($_POST);
				$returnans=update_user_meta( $result,'gmgt_user_avatar',$member_image_url);
			if($result>0)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=member&tab=memberlist&message=1');
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
//DELETE MEMBER DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$result=$obj_member->MJ_gmgt_delete_usedata($_REQUEST['member_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=member&tab=memberlist&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{  ?>
	<div id="message" class="updated below-h2 "><p>
		<?php 	_e('Member added successfully.','gym_mgt');?></p></div>
	
	<?php 
	}
	elseif($message == 2){ ?>
		<div id="message" class="updated below-h2 ">
			<p>	<?php	_e("Member updated successfully.",'gym_mgt');?></p>
		</div>
<?php }
	elseif($message == 3) { ?>
		<div id="message" class="updated below-h2"><p>
		<?php 	_e('Member deleted successfully.','gym_mgt');?></div></p>
<?php		
		}
}
?>

<script type="text/javascript">
$(document).ready(function() 
{
	$('#member_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$('#add_staff_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		 
		$('#membership_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	 
		$('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		 
		$("#group_form").validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$('#group_id').multiselect({
		nonSelectedText :'<?php _e('Select Group','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('.classis_ids').multiselect({
		nonSelectedText :'<?php _e('Select Class','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('#specialization').multiselect({
		nonSelectedText :'<?php _e('Select Specialization','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('#day').multiselect({
		nonSelectedText :'<?php _e('Select Day','gym_mgt');?>',
		includeSelectAllOption: true
		 });
		$('#class_membership_id').multiselect(
		{
			nonSelectedText :'<?php _e('Select Membership','gym_mgt');?>',
			includeSelectAllOption: true
		});
		
	
		$(".specialization_submit").click(function()
		{	
			 checked = $(".multiselect_validation_staff .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast one specialization','gym_mgt');?>");
			  return false;
			}	
		});
			
		$(".class_submit").click(function()
		{	
			checked = $(".multiselect_validation_member .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast one class','gym_mgt');?>");
			  return false;
			}	
		}); 
		
		$(".day_validation_submit").click(function()
		{	
		checked = $(".day_validation_member .dropdown-menu input:checked").length;
			if(!checked)
			{
			  alert("<?php _e('Please select atleast One Day','gym_mgt');?>");
			  return false;
			}	  
		}); 
		
		$.fn.datepicker.defaults.format ="<?php echo get_option('gmgt_datepicker_format');?>";
		$('.birth_date').datepicker(
		{
			endDate: '+0d',
			autoclose: true
		}); 
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		 $('#inqiury_date').datepicker({	
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
			autoclose: true
	   });
	   
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		 $('#triel_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   });	   
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('#begin_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   });
	   
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('#first_payment_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   });

		//------ADD GROUP AJAX----------
		
		$('#group_form').on('submit', function(e)
		{
			e.preventDefault();
			var form = $(this).serialize();
			var valid = $("#group_form").validationEngine('validate');
			if (valid == true)
			{
				$('.modal').modal('hide');
			}
			var categCheck_group = $('#group_id').multiselect();	
			$.ajax(
			{
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data){
					if(data!=""){ 
						$('#group_form').trigger("reset");
						$('#group_id').append(data);
						categCheck_group.multiselect('rebuild');	
					}
				},
				error: function(data){
				}
			})
		});
		

		//------ADD CLASS AJAX----------
		$('#class_form').on('submit', function(e) {
			e.preventDefault(); 
			var form = $(this).serialize();
			
			var categCheck_class = $('#classis_id').multiselect();	
			var categCheck_day = $('#day').multiselect();	
			var categCheck_class_membership = $('#class_membership_id').multiselect();	
			var valid = $('#class_form').validationEngine('validate');
			if (valid == true)
			{
				$('.modal').modal('hide');
			}
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data){
					if(data!=""){ 
						
						$('#class_form').trigger("reset");
						$('#classis_id').append(data);
						categCheck_class.multiselect('rebuild');	
						categCheck_day.multiselect('rebuild');	
						categCheck_class_membership.multiselect('rebuild');	
					}
				},
					error: function(data){
				}
			})
		});
		
		jQuery('#members_list').DataTable({
		"responsive": true,
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
				language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
		}); 
	
			
} );
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>		 
		</div>
    </div>     
</div>
<!-- End POP-UP Code -->
<?php 
if(isset($_REQUEST['attendance']) && $_REQUEST['attendance'] == 1)
{ ?>
	<script type="text/javascript">
	$(document).ready(function() {	
		$('.sdate').datepicker({dateFormat: '<?php echo get_option('gmgt_datepicker_format');?>'}); 
		$('.edate').datepicker({dateFormat: '<?php echo get_option('gmgt_datepicker_format');?>'}); 
	} );
	</script>
	<div class="panel-body panel-white"><!-- PANEL BODY DIV START -->
		<ul class="nav nav-tabs panel_tabs" role="tablist">
			<li class="active">			 
				<a href="#child" role="tab" data-toggle="tab">
					<i class="fa fa-align-justify"></i> <?php _e('Attendance', 'gym_mgt'); ?></a>
				</a>
			</li>
		</ul>
		<div class="tab-content"><!-- TAB CONTENT DIV START -->
			<div class="panel-body"><!-- PANEL BODY DIV START -->
				<form name="wcwm_report" action="" method="post">
					<input type="hidden" name="attendance" value=1> 
					<input type="hidden" name="user_id" value=<?php echo $_REQUEST['member_id'];?>>       
					<div class="form-group col-md-3">
						<label for="exam_id"><?php _e('Start Date','gym_mgt');?></label>
							<input type="text"  class="form-control sdate" name="sdate" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];
								else echo MJ_gmgt_getdate_in_input_box(date('Y-m-d'));?>" readonly>            	
					</div>
					<div class="form-group col-md-3">
						<label for="exam_id"><?php _e('End Date','gym_mgt');?></label>
							<input type="text"  class="form-control edate" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="edate" 
							value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];
							else echo MJ_gmgt_getdate_in_input_box(date('Y-m-d'));?>" readonly>
								
					</div>
					<div class="form-group col-md-3 button-possition">
						<label for="subject_id">&nbsp;</label>
						<input type="submit" name="view_attendance" Value="<?php _e('Go','gym_mgt');?>"  class="btn btn-info"/>
					</div>	
				</form>
				<div class="clearfix"></div>
				<?php 
				//VIEW Attendance DATA
				if(isset($_REQUEST['view_attendance']))
				{
					$start_date = MJ_gmgt_get_format_for_db($_REQUEST['sdate']);
					$end_date = MJ_gmgt_get_format_for_db($_REQUEST['edate']);
					
					if($start_date > $end_date )
					{
						echo '<script type="text/javascript">alert("'.__('End Date should be greater than the Start Date','gym_mgt').'");</script>';
					}
					else
					{
						$user_id = $_REQUEST['user_id'];
						$attendance = MJ_gmgt_view_member_attendance($start_date,$end_date,$curr_user_id);
						$curremt_date =$start_date;
						?>
						<table class="table col-md-12">
						<tr>
							<th width="200px"><?php _e('Date','gym_mgt');?></th>
							<th><?php _e('Day','gym_mgt');?></th>
							<th><?php _e('Attendance','gym_mgt');?></th>
						</tr>
						<?php 
						while ($end_date >= $curremt_date)
						{
							echo '<tr>';
							echo '<td>';
							echo MJ_gmgt_getdate_in_input_box($curremt_date);
							echo '</td>';
							
							$attendance_status = MJ_gmgt_get_attendence($user_id,$curremt_date);
							echo '<td>';
							echo date("D", strtotime($curremt_date));
							echo '</td>';
							
							if(!empty($attendance_status))
							{
								echo '<td>';
								echo MJ_gmgt_get_attendence($user_id,$curremt_date);
								echo '</td>';
							}
							else 
							{
								echo '<td>';
								echo __('Absent','gym_mgt');
								echo '</td>';
							}
							
							echo '</tr>';
							$curremt_date = strtotime("+1 day", strtotime($curremt_date));
							$curremt_date = date("Y-m-d", $curremt_date);
						}
						?>
						</table>
						<?php 
					}					
				}
				?>
			</div>
		</div><!-- TAB CONTENT DIV END -->
	</div><!-- PANEL BODY DIV END -->
	<?php 
}
else
{ ?>
	<div class="panel-body panel-white"><!--PANEL BODY DIV START-->   
		<ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV TABS MENU START-->   
			<li class="<?php if($active_tab == 'memberlist') echo "active";?>">
				  <a href="?dashboard=user&page=member&tab=memberlist">
					 <i class="fa fa-align-justify"></i> <?php _e('Member List', 'gym_mgt'); ?></a>
				  </a>
			</li>
			<?php 
			if(isset($_REQUEST['action']) && $_REQUEST['action'] =='view')
			{ ?>
				<li class="<?php if($active_tab == 'viewmember') echo "active";?>">
					<a href="?dashboard=user&page=member&tab=addmember">
					<i class="fa fa-align-justify"></i> <?php		
					_e('View Member', 'gym_mgt'); 		
					?></a> 
				</li>
			<?php 
			} 
			?>
			<li class="<?php if($active_tab=='addmember'){?>active<?php }?>">
				<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['member_id']))
				{?>
				<a href="?dashboard=user&page=member&tab=addmember&&action=edit&member_id=<?php echo $_REQUEST['member_id'];?>" class="nav-tab <?php echo $active_tab == 'addmember' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Member', 'gym_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{
					?>
					<a href="?dashboard=user&page=member&tab=addmember&&action=insert" class="tab <?php echo $active_tab == 'addmember' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Add Member', 'gym_mgt'); ?></a>
					<?php 	
					} 
				}
			?>	  
			</li>
		</ul><!--NAV TABS MENU END-->  
<?php 
}
?>	
<div class="tab-content"><!--TAB CONTENT DIV START-->   
	<?php 
	if($active_tab == 'memberlist')
	{
		?>
    	<div class="tab-pane <?php if($active_tab == 'memberlist') echo "fade active in";?>" ><!--TAB pane DIV START-->   
			<div class="panel-body">    <!--PANEL BODY DIV START-->   
				<form method="post">  
					<div class="form-group col-md-3" style="padding-right:0px;">
						<label class=""><?php _e('Member type','gym_mgt');?></label>			
						<select name="member_type" class="form-control validate[required]" id="member_type">
						<option value=""><?php  _e('Select Member Type','gym_mgt');?></option>
						<?php
							  if(isset($_POST['member_type']))
								$mtype=$_POST['member_type'];
							  else
								$mtype="";
							$membertype_array=MJ_gmgt_member_type_array();
							
							if(!empty($membertype_array))
							{
								foreach($membertype_array as $key=>$type)
								{							
									echo '<option value='.$key.' '.selected($mtype,$type).'>'.$type.'</option>';
								}
							}
						?>
						</select>			
					</div>
					 <div class="form-group col-md-3 button-possition" style="padding-left:0px;">
						<label for="subject_id">&nbsp;</label>
						<input type="submit" value="<?php _e('Go','gym_mgt');?>" name="filter_membertype"  class="member_filter btn btn-info"/>
					</div>
					 <?php 
						if(isset($_REQUEST['filter_membertype']))
						{
							if(isset($_REQUEST['member_type']) && $_REQUEST['member_type'] != "")
							{
								$member_type= $_REQUEST['member_type'];					
								
								if($obj_gym->role == 'member')
								{	
									if($user_access['own_data']=='1')
									{
										$user_id=get_current_user_id();
										$user_membershiptype= get_user_meta( $user_id, 'member_type',true ); 
										if($user_membershiptype==$member_type)
										{
											$membersdata=array();
											$membersdata[] = get_userdata($user_id);		
										}	
									}
									else
									{
										$membersdata = get_users(array('meta_key' => 'member_type', 'meta_value' =>$member_type,'role'=>'member'));
									}	
								}
								elseif($obj_gym->role == 'staff_member')
								{
									if($user_access['own_data']=='1')
									{
										$membersdata = get_users(array('meta_key' => 'member_type', 'meta_value' =>$member_type,'meta_query'=> array(array('key' => 'staff_id','value' =>$curr_user_id ,'compare' => '=')),'role'=>'member'));	
									}
									else
									{
										$membersdata = get_users(array('meta_key' => 'member_type', 'meta_value' =>$member_type,'role'=>'member'));
									}
								}
								else
								{
									$membersdata = get_users(array('meta_key' => 'member_type', 'meta_value' =>$member_type,'role'=>'member'));
								}
							}
						}	
						else 
						{
							if($obj_gym->role == 'member')
							{	
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();
									$membersdata=array();
									$membersdata[] = get_userdata($user_id);			
								}
								else
								{
									$membersdata =get_users( array('role' => 'member'));
								}	
							}
							elseif($obj_gym->role == 'staff_member')
							{
								if($user_access['own_data']=='1')
								{
									$membersdata = get_users(array('meta_key' => 'staff_id', 'meta_value' =>$curr_user_id ,'role'=>'member'));		
								}
								else
								{
									$membersdata =get_users( array('role' => 'member'));
								}
							}
							else
							{
								$membersdata =get_users( array('role' => 'member'));
							}				
						}
						?>				   
				</form>
			</div><!--PANEL BODY DIV END-->   
			<div class="panel-body"><!--PANEL BODY DIV START-->   
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->   
					<table id="members_list" class="display" cellspacing="0" width="100%"><!--MEMBERSHIP LIST TABLE START-->   
						<thead>
							<tr>
								<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Member Name', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Member Id', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Member Type', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Joining Date', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Expire Date', 'gym_mgt' ) ;?></th>
								<th style="width: 50px;"><?php _e( 'Membership Status', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>							
							</tr>
						</thead>						 
						<tfoot>
							<tr>
								<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Member Name', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Member Id', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Member Type', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Joining Date', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Expire Date', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Membership Status', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>							
							</tr>						   
						</tfoot>
						<tbody>
							<?php 		
							if(!empty($membersdata))
							{
								foreach ($membersdata as $retrieved_data)
								{	
									if($obj_gym->role == 'member')
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
												?></td>
												<td class="name">
												<?php if($obj_gym->role == 'staff_member'){?>	
												
												<?php echo $retrieved_data->display_name;?>
												<?php }
												else
												{?>
													<a href="#"><?php echo $retrieved_data->display_name;?></a>
												<?php }?></td>
												<td class="memberid"><?php echo $retrieved_data->member_id;?></td>
												<td class="memberid"><?php if(isset($retrieved_data->member_type))  echo $membertype_array[$retrieved_data->member_type];  else echo __('Not Selected','gym_mgt');?></td>
												<td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box($retrieved_data->begin_date); }else{ echo "--"; }?></td>
												<td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box(MJ_gmgt_check_membership($retrieved_data->ID)); }else{ echo "--"; }?></td>
											   <td class="status"><?php if($retrieved_data->member_type!='Prospect'){  _e($retrieved_data->membership_status,'gym_mgt'); }else{ _e('Prospect','gym_mgt');}?></td>
												<td class="action">
												<?php 
												if($obj_gym->role == 'staff_member' || ($obj_gym->role == 'member' && $retrieved_data->ID==$curr_user_id))
												{
													?>
												<a class="btn btn-success" href="?dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo $retrieved_data->ID;?>"><?php _e('View','gym_mgt');?></a>
												<a href="?dashboard=user&page=member&tab=add_attendence&member_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default"  idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Attendance','gym_mgt');?> </a>
												<?php }?>
												</td>
											</tr>
										<?php 					
									}
									elseif($obj_gym->role == 'staff_member')
									{					
										$havemeta = get_user_meta($retrieved_data->ID, 'gmgt_hash', true);
										
										if(!$havemeta) 
										{ ?>
											<tr>
												<td class="user_image"><?php $uid=$retrieved_data->ID;
															$userimage=get_user_meta($uid, 'gmgt_user_avatar', true);
														if(empty($userimage))
														{
															echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
														}
														else
															echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
												?></td>
												<td class="name">
												<?php echo $retrieved_data->display_name;?></td>
												<td class="memberid"><?php echo $retrieved_data->member_id;?></td>
												<td class="membertype"><?php echo $retrieved_data->member_type;?></td>
											   <td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box($retrieved_data->begin_date); }else{ echo "--"; }?></td>
												<td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box(MJ_gmgt_check_membership($retrieved_data->ID));}else{ echo "--"; }?></td>
												
												<td class="status"><?php if($retrieved_data->member_type!='Prospect'){  _e($retrieved_data->membership_status,'gym_mgt'); }else{ _e('Prospect','gym_mgt'); }?></td>
												
												<td class="action">
												
												<?php 
												if($user_access['edit']=='1')
												{ ?>
												<a href="?dashboard=user&page=member&tab=addmember&action=edit&member_id=<?php echo $retrieved_data->ID?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
												
												<?php } ?>
												<a class="btn btn-success" href="?dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo $retrieved_data->ID;?>"><?php _e('View','gym_mgt');?></a>
												<a href="?dashboard=user&page=member&tab=add_attendence&member_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default"  idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Attendance','gym_mgt');?> </a>
												
												</td>
											</tr>
										<?php 
										}					
									}
									else
									{
										$havemeta = get_user_meta($retrieved_data->ID, 'gmgt_hash', true);
										if(!$havemeta) 
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
												?></td>
												<td class="name">
												<?php if($obj_gym->role == 'staff_member')
												{
													echo $retrieved_data->display_name;							
												}
												else
												{?>
													<?php echo $retrieved_data->display_name;?>
												<?php }?></td>
												<td class="memberid"><?php echo $retrieved_data->member_id;?></td>
												<td class="memberid"><?php if(isset($retrieved_data->member_type))  echo $membertype_array[$retrieved_data->member_type];  else echo __('Not Selected','gym_mgt');?></td>
											   <td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box($retrieved_data->begin_date); }else{ echo "--"; }?></td>
												<td class="joining date"><?php if($retrieved_data->member_type!='Prospect'){ echo MJ_gmgt_getdate_in_input_box(MJ_gmgt_check_membership($retrieved_data->ID)); }else{ echo "--"; }?></td>
											   
												<td class="status"><?php if($retrieved_data->member_type!='Prospect'){  _e($retrieved_data->membership_status,'gym_mgt'); }else{ _e('Prospect','gym_mgt');}?></td>
												
												<td class="action">
												<a class="btn btn-success" href="?dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo $retrieved_data->ID;?>"><?php _e('View','gym_mgt');?></a>
												<?php 
												if($obj_gym->role == 'staff_member' || ($obj_gym->role == 'member' && $retrieved_data->ID==$curr_user_id)){?>
												
												<a href="?dashboard=user&page=member&tab=add_attendence&member_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default"  idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Attendance','gym_mgt');?> </a>
												<?php }?>
												</td>
											</tr>
										<?php 
										}
									}
								}			
							} ?>     
						</tbody>
					</table><!--MEMBERSHIP LIST TABLE END--> 
				</div><!--TABLE RESPONSIVE DIV END--> 
			</div><!--PANEL BODY DIV END--> 
		</div>	<!--TAB PANE DIV END--> 
		<!--Member Step one information-->
		<?php 
	}
	if($active_tab == 'addmember')
	{
		$member_id=0;
		if(isset($_REQUEST['member_id']))
			$member_id=$_REQUEST['member_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$user_info = get_userdata($member_id);
				if($user_info->gmgt_hash)
				{
					$lastmember_id=MJ_gmgt_get_lastmember_id($role);
					$nodate=substr($lastmember_id,0,-4);
					$memberno=substr($nodate,1);
					$memberno+=1;
					$newmember='M'.$memberno.date("my");
				}
			}
			else
			{
				$lastmember_id=MJ_gmgt_get_lastmember_id($role);
				$nodate=substr($lastmember_id,0,-4);
				$memberno=substr($nodate,1);
				$memberno+=1;
				$newmember='M'.$memberno.date("my");
			}
			?>
		<div class="tab-pane <?php if($active_tab == 'addmember') echo "fade active in";?>" ><!--TAB PANE START--> 
			<div class="panel-body"><!--PANEL BODY START--> 
				<form name="member_form" action="" method="post" class="form-horizontal" id="member_form"><!--MEMBER FORM START--> 
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="role" value="<?php echo $role;?>"  />
					<input type="hidden" name="user_id" value="<?php  echo $member_id;?>"  />
					<input type="hidden" name="gmgt_hash" value="<?php if($edit){ if($user_info->gmgt_hash){ echo $user_info->gmgt_hash;}}?>"  />
					<div class="header col-sm-12">	
						<h3><?php _e('Personal Information','gym_mgt');?></h3>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="member_id"><?php _e('Member Id','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="member_id" class="form-control" type="text" 
								value="<?php if($edit){  echo $user_info->member_id;}else echo $newmember;?>"  readonly name="member_id" tabindex="1">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="first_name"><?php _e('First Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name" tabindex="2">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="middle_name"><?php _e('Middle Name','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter] " type="text" maxlength="50"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name" tabindex="3">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="last_name"><?php _e('Last Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name" tabindex="4">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="birth_date"><?php _e('Date of birth','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="birth_date" class="form-control validate[required] birth_date" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="birth_date" 
								value="<?php if($edit){  echo MJ_gmgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])) echo MJ_gmgt_getdate_in_input_box($_POST['birth_date']);?>" readonly tabindex="5">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="gender"><?php _e('Gender','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
							<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
								<label class="radio-inline">
								 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?> tabindex="6"/><?php _e('Male','gym_mgt');?>
								</label>
								<label class="radio-inline">
								  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','gym_mgt');?> 
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="group_id"><?php _e('Group','gym_mgt');?></label>
							<div class="col-sm-7">
								<?php 
								$joingroup_list = $obj_member->MJ_gmgt_get_all_joingroup($member_id);
								$groups_array = $obj_member->MJ_gmgt_convert_grouparray($joingroup_list);
								?>
								<?php if($edit){ $group_id=$user_info->group_id; }elseif(isset($_POST['group_id'])){$group_id=$_POST['group_id'];}else{$group_id='';}?>
								<select id="group_id"  name="group_id[]" multiple="multiple" tabindex="7">
								
								<?php $groupdata=$obj_group->MJ_gmgt_get_all_groups();
								 if(!empty($groupdata))
								 {
									foreach ($groupdata as $group){?>
										<option value="<?php echo $group->id;?>" <?php if(in_array($group->id,$groups_array)) echo 'selected';  ?>><?php echo $group->group_name; ?> </option>
							<?php } } ?>
							</select>
							</div>
						</div>
					</div>
					<div class="header col-sm-12">	
						<hr>
						<h3><?php _e('Contact Information','gym_mgt');?></h3>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="address"><?php _e('Address','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
								value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>" tabindex="8">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="city_name"><?php _e('City','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
								value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>" tabindex="9">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="state_name"><?php _e('State','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
								value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>" tabindex="10">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="zip_code"><?php _e('Zip Code','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" 
								value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>" tabindex="11">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label " for="mobile"><?php _e('Mobile Number','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-2">
							
							<input type="text" readonly value="+<?php echo MJ_gmgt_get_countery_phonecode(get_option( 'gmgt_contry' ));?>"  class="form-control" name="phonecode">
							</div>
							<div class="col-sm-5">
								<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input phone_validation"  type="text" minlength="6" name="mobile" maxlength="15"
								value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" tabindex="12">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label " for="phone"><?php _e('Phone','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="phone" class="form-control text-input phone_validation validate[custom[phone_number]]"  type="text" minlength="6" maxlength="15"  name="phone" 
								value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" tabindex="13">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label " for="email"><?php _e('Email','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input type="hidden"  name="hidden_email" value="<?php if($edit){ echo $user_info->user_email; } ?>">
								<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>" tabindex="14">
							</div>
						</div>
					</div>
					<div class="header col-sm-12">	
						<hr>
						<h3><?php _e('Physical Information','gym_mgt');?></h3>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="weight"><?php _e('Weight','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="weight" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01"
								value="<?php if($edit){ echo $user_info->weight;}elseif(isset($_POST['weight'])) echo $_POST['weight'];?>" 
								name="weight" placeholder="<?php echo get_option( 'gmgt_weight_unit' );?>" tabindex="15">
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="height"><?php _e('Height','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="height" class="form-control text-input decimal_number"type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" value="<?php if($edit){ echo $user_info->height;}elseif(isset($_POST['height'])) echo $_POST['height'];?>" 
								name="height" placeholder="<?php echo get_option( 'gmgt_height_unit' );?>" tabindex="16">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="Chest"><?php _e('Chest','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="Chest" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" 
								value="<?php if($edit){ echo $user_info->chest;}elseif(isset($_POST['chest'])) echo $_POST['chest'];?>" name="chest" 
								placeholder="<?php echo get_option( 'gmgt_chest_unit' );?>" tabindex="17">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="Waist"><?php _e('Waist','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="waist" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" 
								value="<?php if($edit){ echo $user_info->waist;}elseif(isset($_POST['waist'])) echo $_POST['waist'];?>" name="waist" 
								placeholder="<?php echo get_option( 'gmgt_waist_unit' );?>" tabindex="18">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="thigh"><?php _e('Thigh','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="thigh" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01" 
								value="<?php if($edit){ echo $user_info->thigh;}elseif(isset($_POST['thigh'])) echo $_POST['thigh'];?>" name="thigh" 
								placeholder="<?php echo get_option( 'gmgt_thigh_unit' );?>" tabindex="19">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="arms"><?php _e('Arms','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="arms" class="form-control text-input decimal_number" type="number" min="0"  onkeypress="if(this.value.length==6) return false;" step="0.01"
								value="<?php if($edit){ echo $user_info->arms;}elseif(isset($_POST['arms'])) echo $_POST['arms'];?>" name="arms" 
								placeholder="<?php echo get_option( 'gmgt_arms_unit' );?>" tabindex="20">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="fat"><?php _e('Fat','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="fat" class="form-control text-input decimal_number" type="number" min="0" max="100" onkeypress="if(this.value.length==6) return false;" step="0.01"
								value="<?php if($edit){ echo $user_info->fat;}elseif(isset($_POST['fat'])) echo $_POST['fat'];?>" name="fat" 
								placeholder="<?php echo get_option( 'gmgt_fat_unit' );?>" tabindex="21">
							</div>
						</div>
					</div>
					<div class="header col-sm-12">
						<hr>
						<h3><?php _e('Login Information','gym_mgt');?></h3>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="username"><?php _e('User Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<input id="username" class="form-control validate[required,custom[username_validation]] space_validation" type="text" maxlength="50"  name="username" 
								value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?> tabindex="22">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="password"><?php _e('Password','gym_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
							<div class="col-sm-7">
								<input id="password" class="form-control space_validation <?php if(!$edit) echo 'validate[required]';?>" minlength="8" maxlength="12" type="password"  name="password" value="" tabindex="23">
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="photo"><?php _e('Image','gym_mgt');?></label>
							
							<input type="text" id="gmgt_user_avatar_url" class="form-control" name="gmgt_user_avatar"  readonly
								value="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar );elseif(isset($_POST['gmgt_user_avatar'])) echo $_POST['gmgt_user_avatar']; ?>" style="display:none;" />
							
							<div class="col-sm-7">
								<input type="hidden" name="hidden_upload_user_avatar_image" value="<?php if($edit){ echo $user_info->gmgt_user_avatar;}elseif(isset($_POST['upload_user_avatar_image'])) echo $_POST['upload_user_avatar_image'];?>">
									 <input id="upload_user_avatar_image" name="upload_user_avatar_image" type="file" class="form-control file" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" tabindex="24"/>
							</div>
							<div class="clearfix"></div>					
							<div class="col-sm-offset-4 col-sm-7">
								 <div id="upload_user_avatar_preview" >
									 <?php if($edit) 
										{
										if($user_info->gmgt_user_avatar == "")
										{?>
										<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
										<?php }
										else {
											?>
										<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->gmgt_user_avatar ); ?>" />
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
					</div>
					<div class="header col-sm-12">	
						<hr>
						<h3><?php _e('More Information','gym_mgt');?></h3>
					</div>
					<div class="col-sm-6 padding_left_right_0">		
						<div class="form-group">
							<label class="col-sm-4 control-label" for="refered"><?php _e('Member type','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<select name="member_type" class="form-control validate[required]" id="member_type" tabindex="25">
									<option value=""><?php  _e('Select Member Type','gym_mgt');?></option>
									<?php if($edit)
											$mtype=$user_info->member_type;
										elseif(isset($_POST['member_type']))
											$mtype=$_POST['member_type'];
										else
											$mtype="";
										$membertype_array=MJ_gmgt_member_type_array();
										if(!empty($membertype_array))
										{
											foreach($membertype_array as $key=>$type)
											{
												echo '<option value='.$key.' '.selected($mtype,$key).'>'.$type.'</option>';
											}
										} ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="staff_name"><?php _e('Select Staff Member','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-7">
								<?php $get_staff = array('role' => 'Staff_member');
									$staffdata=get_users($get_staff);
								?>
								<select name="staff_id" class="form-control validate[required] " id="staff_id" tabindex="26">
									<option value=""><?php  _e('Select Staff Member','gym_mgt');?></option>
									<?php if($edit)
											$staff_data=$user_info->staff_id;
										elseif(isset($_POST['staff_id']))
											$staff_data=$_POST['staff_id'];
										else
											$staff_data="";
										if(!empty($staffdata))
										{
										foreach($staffdata as $staff)
										{
											
											echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
										}
										}
										?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="intrest"><?php _e('Interest Area','gym_mgt');?></label>
							<div class="col-sm-4">
								<select class="form-control" name="intrest_area" id="intrest_area" tabindex="27">
									<option value=""><?php _e('Select Interest','gym_mgt');?></option>
									<?php 
									
									if(isset($_REQUEST['intrest']))
										$category =$_REQUEST['intrest'];  
									elseif($edit)
										$category =$user_info->intrest_area;
									else 
										$category = "";
									
									$role_type=MJ_gmgt_get_all_category('intrest_area');
									if(!empty($role_type))
									{
										foreach ($role_type as $retrive_data)
										{
											echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
										}
									}
								   ?>
								</select>
							</div>
							<div class="col-sm-3 add_category_padding_0" ><button id="addremove" model="intrest_area" tabindex="28"><?php _e('Add Or Remove','gym_mgt');?></button></div>
						</div>
					</div>				
					<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
						<div class="row">	
							<div class="col-sm-6 padding_left_right_0">	
								<div class="form-group">
									<label class="col-sm-4 control-label" for="member_convert"><?php  _e(' Convert into Staff Member','gym_mgt');?></label>
										<div class="col-sm-7">
										<input type="checkbox"  name="member_convert" value="staff_member">
										</div>
								</div>
							</div>
						</div>
					<?php }?>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="Source"><?php _e('Referral Source','gym_mgt');?></label>
							<div class="col-sm-4">
								<select class="form-control" name="source" id="source" tabindex="29">
									<option value=""><?php _e('Select Referral Source','gym_mgt');?></option>
									<?php 
									
									if(isset($_REQUEST['source']))
										$category =$_REQUEST['source'];  
									elseif($edit)
										$category =$user_info->source;
									else 
										$category = "";
									
									$role_type=MJ_gmgt_get_all_category('source');
									if(!empty($role_type))
									{
										foreach ($role_type as $retrive_data)
										{
											echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="col-sm-3 add_category_padding_0"><button id="addremove" model="source" tabindex="30"><?php _e('Add Or Remove','gym_mgt');?></button></div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="refered"><?php _e('Referred By','gym_mgt');?></label>
							<div class="col-sm-7">
								<?php
									$staffdata=get_users([ 'role__in' => ['Staff_member', 'member']]);
								?>
								<select name="reference_id" class="form-control" id="reference_id" tabindex="31">
									<option value=""><?php  _e('Select Referred Member','gym_mgt');?></option>
									<?php if($edit)
											$staff_data=$user_info->reference_id;
										elseif(isset($_POST['reference_id']))
											$staff_data=$_POST['reference_id'];
										else
											$staff_data="";					
										
										if(!empty($staffdata))
										{
											foreach($staffdata as $staff)
											{						
												echo '<option value='.$staff->ID.' '.selected($staff_data,$staff->ID).'>'.$staff->display_name.'</option>';
											}
										}
										?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0" <?php if($edit){ ?> style="clear: both;" <?php } ?>>	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="inqiury_date"><?php _e('Inquiry Date','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="inqiury_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="inqiury_date" 
								value="<?php if($edit){ if($user_info->inqiury_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->inqiury_date); } }elseif(isset($_POST['inqiury_date'])){ echo $_POST['inqiury_date']; }?>" tabindex="32" readonly>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">	
						<div class="form-group">
							<label class="col-sm-4 control-label" for="triel_date"><?php _e('Trial End Date','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="triel_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="triel_date" 
								value="<?php if($edit){ if($user_info->triel_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->triel_date); } }elseif(isset($_POST['triel_date'])){ echo $_POST['triel_date']; }?>" tabindex="33" readonly>
							</div>
						</div>
					</div>				
					<div id="non_prospect_area">
						<div class="col-sm-6 padding_left_right_0">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="membership"><?php _e('Membership','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-7">
									<input type="hidden" name="membership_hidden" class="membership_hidden" value="<?php if($edit){ if(!empty($user_info->membership_id)) { echo $user_info->membership_id; }else{ echo '0'; } }else{ echo '0';}?>">
									<?php 	$membershipdata=$obj_membership->MJ_gmgt_get_all_membership(); ?>
									<select name="membership_id" class="form-control validate[required]" id="membership_id" tabindex="34">	
										<option value=""><?php  _e('Select Membership','gym_mgt');?></option>
										<?php 
											if($edit)
											{
											  $staff_data=$user_info->membership_id;
											}
											if(!empty($membershipdata))
												
											{
												foreach ($membershipdata as $membership)
												{						
													echo '<option value='.$membership->membership_id.' '.selected($staff_data,$membership->membership_id).'>'.$membership->membership_label.'</option>';
												}
											}
											?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6 padding_left_right_0" <?php if(!$edit){ ?> style="clear: both;" <?php } ?>>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="class_id"><?php _e('Class','gym_mgt');?><span class="require-field">*</span></label>			
								<div class="col-sm-7 multiselect_validation_member">				
									<!--<?php if($edit){ $class_id=$user_info->class_id; }elseif(isset($_POST['class_id'])){$class_id=$_POST['class_id'];}else{$class_id='';}?>-->
									<select id="classis_id" class="form-control validate[required] classis_ids" multiple="multiple" name="class_id[]" tabindex="35">
											<?php 
											if($edit)					
											{	
												$obj_class=new MJ_Gmgtclassschedule;										
												$MemberShipClass = MJ_gmgt_get_class_id_by_membership_id($user_info->membership_id);
												$userclass  = MJ_gmgt_get_current_user_classis($member_id);
												
												foreach($MemberShipClass as $key=>$class_id)
												{ 
													$class_data=$obj_class->MJ_gmgt_get_single_class($class_id);
													?>
													<option value="<?php echo $class_id ;?>" <?php if(in_array($class_id,$userclass)){ print "Selected"; }  ?>><?php echo MJ_gmgt_get_class_name($class_id); ?> ( <?php echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->start_time).' - '.MJ_gmgt_timeremovecolonbefoream_pm($class_data->end_time);?>)</option>
											<?php }
											}
											?>
										
									</select>
								</div>
							</div>
						</div>
						<?php 
						if($edit)
						{ ?>
							<div class="col-sm-6 padding_left_right_0" style="clear: both;">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="membership_status"><?php _e('Membership Status','gym_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-7">
									<?php $membership_statusval = "Continue"; if($edit){ $membership_statusval=$user_info->membership_status; }elseif(isset($_POST['membership_status'])) {$membership_statusval=$_POST['membership_status'];}?>
										<label class="radio-inline">
										 <input type="radio" value="Continue" class="tog validate[required]" name="membership_status"  <?php  checked( 'Continue', $membership_statusval);  ?>/><?php _e('Continue','gym_mgt');?>
										</label>
										<label class="radio-inline">
										 <input type="radio" value="Expired" class="tog validate[required]" name="membership_status"  <?php  checked( 'Expired', $membership_statusval);  ?>/><?php _e('Expired','gym_mgt');?>
										</label>
										<label class="radio-inline">
										  <input type="radio" value="Dropped" class="tog validate[required]" name="membership_status"  <?php  checked( 'Dropped', $membership_statusval);  ?>/><?php _e('Dropped','gym_mgt');?> 
										</label>
									</div>
								</div>
							</div>
						<?php } ?>				
						<input type="hidden" name="auto_renew" value="No">
						<div class="col-sm-6 padding_left_right_0" style="clear: both;">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="begin_date"><?php _e('Membership Valid From','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-7">
									<input id="begin_date" class="form-control validate[required] begin_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text"  name="begin_date" value="<?php if($edit){ if($user_info->begin_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->begin_date); } }elseif(isset($_POST['begin_date'])) echo $_POST['begin_date'];?>" tabindex="36" readonly>
								</div>							
							</div>
						</div>
						<div class="col-sm-6 padding_left_right_0">
							<div class="form-group">							
								<label class="col-sm-4 control-label" for=""><?php _e('Membership Valid To','gym_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-7">
									<input id="end_date" class="form-control validate[required]" type="text"  name="end_date" 
									value="<?php if($edit){ 
									if($user_info->end_date!=""){
									echo MJ_gmgt_getdate_in_input_box($user_info->end_date); }
									}
									elseif(isset($_POST['end_date'])) echo $_POST['end_date'];?>" tabindex="37" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 padding_left_right_0">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="first_payment_date"><?php _e('First Payment Date','gym_mgt');?></label>
							<div class="col-sm-7">
								<input id="first_payment_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="first_payment_date" 
								value="<?php if($edit){ if($user_info->first_payment_date!=""){ echo MJ_gmgt_getdate_in_input_box($user_info->first_payment_date); } }elseif(isset($_POST['first_payment_date'])){ echo $_POST['first_payment_date']; }?>" tabindex="38" readonly>
							</div>
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_member_nonce' ); ?>
					<!--nonce-->
					<div id="no_of_class"></div>
					<div class="col-sm-offset-2 col-sm-8">        	
						<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save Member','gym_mgt');}?>" name="save_member" class="btn btn-success class_submit "/>
					</div>
				</form><!--MEMBER FORM END--> 
			</div><!--PANEL BODY DIV END--> 
		</div><!--TAB PANE DIV END--> 
		<?php } ?>
		<!--Member Step two information-->
		<?php if($active_tab == 'viewmember')
		{?>
			<div class="tab-pane <?php if($active_tab == 'viewmember') echo "fade active in";?>" >
				<?php require_once GMS_PLUGIN_DIR. '/template/view_member.php';?>
			</div>
		<?php 
		}
		?>
	</div><!--TAB CONTENT DIV END-->   
</div><!--PANEL BODY DIV END-->   