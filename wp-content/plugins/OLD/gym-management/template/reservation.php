<?php $curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_reservation=new MJ_Gmgtreservation;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'reservationlist';
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
//SAVE Reservation DATA
if(isset($_POST['save_group']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_group_nonce' ) )
	{
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
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
				_e('End Time should be greater than Start Time.','gym_mgt');
			?></p>
			</div>
			<?php 
		}
		else
		{	
			$result=$obj_reservation->MJ_gmgt_add_reservation($_POST);
			
			if($result['msg']!='reserved')
			{
				wp_redirect ( home_url().'?dashboard=user&page=reservation&tab=reservationlist&message=2');
			}
			else
			{
				if(isset($result['msg'])){
					$_REQUEST['reservation_id']=$result['id'];
					?>
					<div id="message" class="updated below-h2">
						<p><p><?php _e('This Date is Already Reserved.','gym_mgt');?></p></p>
						</div>
					<?php }
				 
				
			}
		}	
	}
	else
	{
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
			$result=$obj_reservation->MJ_gmgt_add_reservation($_POST);
			if($result!="reserved")
			{
				wp_redirect ( home_url().'?dashboard=user&page=reservation&tab=reservationlist&message=1');
			}
			else
			{
			?>
				<div id="message" class="updated below-h2">
					<p><p><?php _e('This Date is Already Reserved.','gym_mgt');?></p></p>
					</div>
			<?php 
			}	
		}	
	}
}
}
//Delete Reservation DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=$obj_reservation->MJ_gmgt_delete_reservation($_REQUEST['reservation_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=reservation&tab=reservationlist&message=3');
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
				_e('Reservation added successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	   }
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Reservation updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Reservation deleted successfully.','gym_mgt');
	?></div></p><?php
			
	}
}
?>
<script type="text/javascript">
$(document).ready(function() {
	var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php  echo get_option('gmgt_datepicker_format'); ?>";
             $('#event_date').datepicker({
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
	jQuery('#reservation_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	
	                  {"bSortable": true},  
					  <?php
					  if($user_access['edit']=='1' || $user_access['delete']=='1')
					  {	
					?>
					{"bSortable": false}
					<?php
				  }
				  ?>
					],
			language:<?php echo MJ_gmgt_datatable_multi_language();?>			
		});
		$('#reservation_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
} );
</script>

<div class="panel-body panel-white"><!-- PANEL BODY DIV START-->
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!-- NAV TABS MENU START-->
	  	<li class="<?php if($active_tab=='reservationlist'){?>active<?php }?>">
			<a href="?dashboard=user&page=reservation&tab=reservationlist" class="tab <?php echo $active_tab == 'reservationlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Reservation List', 'gym_mgt'); ?></a>
          </a>
		</li>	
       <li class="<?php if($active_tab=='addreservation'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['reservation_id']))
			{?>
			<a href="?dashboard=user&page=reservation&tab=addreservation&action=edit&reservation_id=<?php echo $_REQUEST['reservation_id'];?>" class="nav-tab <?php echo $active_tab == 'addreservation' ? 'nav-tab-active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit Reservation', 'gym_mgt'); ?></a>
			 <?php }
			else
			{
				if($user_access['add']=='1')
				{
				?>
				<a href="?dashboard=user&page=reservation&tab=addreservation&&action=insert" class="tab <?php echo $active_tab == 'addreservation' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Reservation', 'gym_mgt'); ?></a>
			<?php 
				} 
			}
			?>  
		</li>	 
	</ul><!-- NAV TABS MENU END-->
	<div class="tab-content"><!-- TAB CONTENT DIV START-->
		<?php 
		if($active_tab == 'reservationlist')
		{ ?>	
			<form name="wcwm_report" action="" method="post"><!--Reservation LIST FORM START-->
				<div class="panel-body"><!--PANEL BODY DIV START-->
					<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
						<table id="reservation_list" class="display" cellspacing="0" width="100%"><!-- RESPONSIVE LIST TABLE START-->
							<thead>
							<tr>
							<th><?php  _e( 'Event Name', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Event Date', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Place', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Starting Time', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Ending Time', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Reserved By', 'gym_mgt' ) ;?></th>	
							<?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{	
							?>	
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>		
							<?php
							}
							?>		
							</tr>
							</thead>
							<tfoot>
								<tr>
								<th><?php  _e( 'Event Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Event Date', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Place', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Starting Time', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Ending Time', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Reserved By', 'gym_mgt' ) ;?></th>	
								<?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{	
							?>		
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>		
							<?php
							}
							?>			
								</tr>
							</tfoot>
							<tbody>
							<?php 
							if($user_access['own_data']=='1')
							{
								$reservationdata=$obj_reservation->MJ_gmgt_get_reservation_by_created_by();
							}
							else
							{
								$reservationdata=$obj_reservation->MJ_gmgt_get_all_reservation();
							}	
							if(!empty($reservationdata))
							{
								foreach ($reservationdata as $retrieved_data)
								{
							 ?>
								<tr>
									<td class="eventname">
									<?php if($obj_gym->role == 'staff_member')
									   {?>
									<a href="?dashboard=user&page=reservation&tab=addreservation&action=edit&reservation_id=<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->event_name;?></a>
									   <?php }
									   else
									   {?>
										   <?php echo $retrieved_data->event_name;?>
									   <?php }?></td>
									<td class="date"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->event_date );?></td>
									<td class="place"><?php echo  get_the_title( $retrieved_data->place_id );?></td>
									
									<td class="starttime"><?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->start_time);?></td>
									<td class="endtime"><?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->end_time);?></td>
									<td class="staff_id"><?php echo MJ_gmgt_get_display_name($retrieved_data->staff_id);?></td>
									<?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{	
								?>
									<td class="action">
									<?php
									if($user_access['edit']=='1')
									{
									?>
										<a href="?dashboard=user&page=reservation&tab=addreservation&action=edit&reservation_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{
									?>	
										<a href="?dashboard=user&page=reservation&tab=reservationlist&action=delete&reservation_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
										onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
										<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
									<?php
									}
									?>
									</td>
								<?php
								}
								?>	  
								</tr>
								<?php 
								} 
								
							}?>
							</tbody>
						</table><!-- RESPONSIVE LIST TABLE END-->
					</div><!-- TABLE RESPONSIVE DIV END-->
				</div><!-- PANEL BODY DIV END-->
			</form><!-- RESPONSIVE LIST FORM END-->
			<?php 
		}
		if($active_tab == 'addreservation')
		{
			$reservation_id=0;
			if(isset($_REQUEST['reservation_id']))
				$reservation_id=$_REQUEST['reservation_id'];
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					
					$edit=1;
					$result = $obj_reservation->MJ_gmgt_get_single_reservation($reservation_id);
					
				}?>
				<!-- POP up code -->
				<div class="popup-bg">
				   <div class="overlay-content">
						<div class="modal-content">
							<div class="category_list"></div>
						</div>
					</div> 
				 </div>
				<!-- End POP-UP Code -->
			<div class="panel-body"><!-- PANEL BODY DIV START -->
				<form name="reservation_form" action="" method="post" class="form-horizontal" id="reservation_form"><!-- Reservation FORM START -->
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="reservation_id" value="<?php echo $reservation_id;?>"  />
					<input type="hidden" name="staff_id" value="<?php echo get_current_user_id();?>"  />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="event_name"><?php _e('Event Name','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="event_name" class="form-control validate[required,custom[popup_category_validation]] text-input" type="text" maxlength="100" value="<?php if($edit){ echo $result->event_name;}elseif(isset($_POST['event_name'])) echo $_POST['event_name'];?>" name="event_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="event_date"><?php _e('Event Date','gym_mgt');?></label>
						<div class="col-sm-8">
							<input id="event_date" class="form-control" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="event_date" 
							value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->event_date );}
							elseif(isset($_POST['event_date'])){ echo $_POST['event_date'];}else{ echo MJ_gmgt_getdate_in_input_box(date('Y-m-d'));}?>" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="event_place"><?php _e('Event Place','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						
							<select class="form-control validate[required]" name="event_place" id="event_place">
							<option value=""><?php _e('Select Event Place','gym_mgt');?></option>
							<?php 
							
							if(isset($_REQUEST['event_place']))
								$category =$_REQUEST['event_place'];  
							elseif($edit)
								$category =$result->place_id;
							else 
								$category = "";
							
							$mambership_category=MJ_gmgt_get_all_category('event_place');
							if(!empty($mambership_category))
							{
								foreach ($mambership_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							}
							?>
							
							</select>
						</div>
						<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="event_place"><?php _e('Add Or Remove','gym_mgt');?></button></div>
					</div>
					
					
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="starttime"><?php _e('Start Time','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
						<?php 
						if($edit)
						{
							$start_time_data = explode(":", $result->start_time);
							
						}
						?>
							 <select name="start_time" class="form-control validate[required]">
							 <option value=""><?php _e('Start Time','gym_mgt');?></option>
							 <?php 
								for($i =0 ; $i <= 12 ; $i++)
								{
								?>
								<option value="<?php echo $i;?>" <?php  if($edit) selected($start_time_data[0],$i);  ?>><?php echo $i;?></option>
								<?php
								}
							 ?>
							 </select>
						</div>
						<div class="col-sm-2">
							 <select name="start_min" class="form-control validate[required]">
							 <?php 
								foreach(MJ_gmgt_minute_array() as $key=>$value)
								{?>
								<option value="<?php echo $key;?>" <?php  if($edit) selected($start_time_data[1],$key);  ?>><?php echo $value;?></option>
								<?php
								}
							 ?>
							 </select>
						</div>
						<div class="col-sm-2">
							 <select name="start_ampm" class="form-control validate[required]">
								<option value="am" <?php  if($edit) if(isset($start_time_data[2])) selected($start_time_data[2],'am');  ?>><?php _e('am','gym_mgt');?></option>
								<option value="pm" <?php  if($edit) if(isset($start_time_data[2])) selected($start_time_data[2],'pm');  ?>><?php _e('pm','gym_mgt');?></option>
							 </select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="weekday"><?php _e('End Time','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
						<?php 
						if($edit)
						{
							$end_time_data = explode(":", $result->end_time);
						}
						?>
							 <select name="end_time" class="form-control validate[required]">
							  <option value=""><?php _e('End Time','gym_mgt');?></option>
							 <?php 
								for($i =0 ; $i <= 12 ; $i++)
								{
								?>
								<option value="<?php echo $i;?>" <?php  if($edit) selected($end_time_data[0],$i);  ?>><?php echo $i;?></option>
								<?php
								}
							 ?>
							 </select>
						</div>
						<div class="col-sm-2">
							 <select name="end_min" class="form-control validate[required]">
							  <?php 
								foreach(MJ_gmgt_minute_array() as $key=>$value)
								{?>
								<option value="<?php echo $key;?>" <?php  if($edit) selected($end_time_data[1],$key);  ?>><?php echo $value;?></option>
								<?php
								} ?>
							 </select>
						</div>
						<div class="col-sm-2">
							  <select name="end_ampm" class="form-control validate[required]">
								<option value="am" <?php  if($edit) if(isset($end_time_data[2])) selected($end_time_data[2],'am');  ?> ><?php _e('am','gym_mgt');?></option>
								<option value="pm" <?php  if($edit) if(isset($end_time_data[2]))selected($end_time_data[2],'pm');  ?>><?php _e('pm','gym_mgt');?></option>
							 </select>
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_group_nonce' ); ?>
					<!--nonce-->					
					<div class="col-sm-offset-2 col-sm-8">						
						<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_group" class="btn btn-success"/>
					</div>
				</form><!-- Reservation FORM END -->
			</div><!-- PANEL BODY DIV END-->
			<?php 
		}
		?>
	</div><!-- TAB CONTENT DIV END-->
</div><!-- PANEL BODY DIV END-->