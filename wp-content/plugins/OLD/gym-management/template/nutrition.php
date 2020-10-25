<?php 
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_nutrition=new MJ_Gmgtnutrition;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'nutritionlist';
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
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='view'))
		{
			if($user_access['add']=='0')
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
//SAVE Nutrition DATA
if(isset($_POST['save_nutrition']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_nutrition_nonce' ) )
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{		
			$result=$obj_nutrition->MJ_gmgt_add_nutrition($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=nutrition&tab=nutritionlist&message=2');
			}	
		}
		else
		{
			$result=$obj_nutrition->MJ_gmgt_add_nutrition($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=nutrition&tab=nutritionlist&message=1');
			}
		}
	}	
}
//DELETE Nutrition DATA	
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=$obj_nutrition->MJ_gmgt_delete_nutrition($_REQUEST['nutrition_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=nutrition&tab=nutritionlist&message=3');
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
				_e('Nutrition added successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Nutrition updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Nutrition deleted successfully.','gym_mgt');
	?></div></p><?php
			
	}
}
?>
<script type="text/javascript">
$(document).ready(function() {
	jQuery('#nutrition_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
					{"bSortable": false},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": false}]
		});
		$('#nutrition_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$(".display-members").select2();
		//date picker	
		var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('.start_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   }).on('changeDate', function(){
			$('.end_date').datepicker('setStartDate', new Date($(this).val()));
		});
	   
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('.end_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   }).on('changeDate', function(){
			$('.start_date').datepicker('setEndDate', new Date($(this).val()));
		});	
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
<div class="panel-body panel-white"><!-- PANEL BODY DIV START -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!-- NAV TABS MENU START -->
	  	<li class="<?php if($active_tab=='nutritionlist'){?>active<?php }?>">
			<a href="?dashboard=user&page=nutrition&tab=nutritionlist" class="tab <?php echo $active_tab == 'nutritionlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Nutrition Schedule List', 'gym_mgt'); ?></a>
          </a>
        </li>
	  <?php		
		if($user_access['add']=='1')
		{
			?>
				<li class="<?php if($active_tab=='addnutrition'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
				{				
					?>
					<a href="?dashboard=user&page=nutrition&tab=addnutrition&&action=view&workoutmember_id=<?php echo $_REQUEST['workoutmember_id'];?>" class="nav-tab <?php echo $active_tab == 'addnutrition' ? 'nav-tab-active' : ''; ?>">
					<i class="fa fa-align-justify"></i> <?php _e('View  Nutrition Schedule', 'gym_mgt'); ?></a>
				 <?php
				
				}
				else
				{					
					?>
					<a href="?dashboard=user&page=nutrition&tab=addnutrition&&action=insert" class="tab <?php echo $active_tab == 'addnutrition' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Add Nutrition Schedule', 'gym_mgt'); ?></a>
				<?php 
				} 
			
			?>	  
			</li>
		<?php 
		}
		?>
    </ul><!-- NAV TABS MENU END -->
	<div class="tab-content"><!-- TAB CONTENT DIV START -->
	<?php 
	if($active_tab == 'nutritionlist')
	{ ?>	
		<form name="wcwm_report" action="" method="post"><!-- Nutrition LIST FORM START -->
			<div class="panel-body"><!-- PANEL BODY DIV START -->
				<?php
				if($obj_gym->role=='member')
				{
					$nutrition_logdata=MJ_gmgt_get_user_nutrition(get_current_user_id());
					if(empty($nutrition_logdata)) 
					{?>
						<div class="clear col-md-12"><h3><?php _e("There is no any nutrition schedule data.",'gym_mgt');?> </h3></div>
					  <?php 
					} 
					if(isset($nutrition_logdata))
						foreach($nutrition_logdata as $row)
						{
							$all_logdata=MJ_gmgt_get_nutritiondata($row->id); 
							$arranged_workout=MJ_gmgt_set_nutrition_array($all_logdata);
							?>
							 <div class="workout_<?php echo $row->id;?> workout-block"><!-- WORKOUT BLOCK DIV START -->
								<div class="panel-heading">
									<h3 class="panel-title"><i class="fa fa-calendar"></i> 
									<?php 
									_e('Start From ','gym_mgt');
									echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box( $row->start_date )."</span>";
									_e(' To ','gym_mgt');
									echo "<span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->expire_date ); 
									?></h3>						
								</div>											
								<div class="panel panel-white"><!-- PANEL WHITE DIV START -->
									<?php
										if(!empty($arranged_workout))
										{ ?>
											<div class="work_out_datalist_header">
												<div class="col-md-4 col-sm-4 col-xs-4">  
													<strong><?php _e('Day Name','gym_mgt');?></strong>
												</div>
												<div class="col-md-8 col-sm-8 col-xs-8">
													<span class="col-md-3 hidden-xs"><?php _e('Time','gym_mgt');?></span>
													<span class="col-md-3"><?php _e('Description','gym_mgt');?></span>
												</div>
											</div>
											<?php 
											foreach($arranged_workout as $key=>$rowdata)
											{?>
												<div class="work_out_datalist">
													<div class="col-md-4 col-sm-4 col-xs-12 day_name"><?php echo $key;?></div>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php 
														foreach($rowdata as $row)
														{
																echo $row."<br>";
														} ?>
													</div>
												</div>
											<?php 
											}
										} ?>											
								</div><!-- PANEL WHITE DIV END -->
							</div><!-- WORKOUT BLOCK DIV END -->
						<?php 
						}	
				}
				else
				{
					?>
					<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
						<table id="nutrition_list" class="display" cellspacing="0" width="100%"><!-- TABLE Nutrition LIST START -->
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
							$get_members = array('role' => 'member');
							$membersdata=get_users($get_members);
							if(!empty($membersdata))
							{
								foreach ($membersdata as $retrieved_data)
								{?>
								<tr>
									<td class="user_image"><?php $uid=$retrieved_data->ID;
										$userimage=get_user_meta($uid, 'gmgt_user_avatar', true);
										if(empty($userimage)){
											echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
										}
										else
											echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
									?></td>
									<td class="member"><a href="#">
									<?php $user=get_userdata($retrieved_data->ID);
									$display_label=$user->display_name;
									$memberid=get_user_meta($retrieved_data->ID,'member_id',true);
										if($memberid)
											$display_label.=" (".$memberid.")";
										echo $display_label;?></a></td>
									<td class="member-goal"><?php $intrestid=get_user_meta($retrieved_data->ID,'intrest_area',true);
									echo get_the_title($intrestid);?></td>			
									<td class="action"> 
										<a href="?dashboard=user&page=nutrition&tab=addnutrition&action=view&workoutmember_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default">
										<i class="fa fa-eye"></i> <?php _e('View Nutrition', 'gym_mgt');?></a>
									</td>               
								</tr>
								<?php
								} 			
							}?>
							</tbody>             
						</table><!-- TABLE Nutrition LIST END -->
					</div><!-- TABLE RESPONSIVE DIV END -->
				<?php 
				}
				?>
			</div><!-- PANEL BODY END-->
	    </form>	<!-- Nutrition LIST FORM END -->	
		<?php 
	}
	if($active_tab == 'addnutrition')
	{
	 	$nutrition_id=0;
	 	$edit=0;
	 	if(isset($_REQUEST['workouttype_id']))
	 		$workouttype_id=$_REQUEST['workouttype_id'];
	 	if(isset($_REQUEST['workoutmember_id']))
		{
	 		$edit=0;
	 		$workoutmember_id=$_REQUEST['workoutmember_id'];
	 	
	 		$nutrition_logdata=MJ_gmgt_get_user_nutrition($workoutmember_id);
	 	
	 	}?>
        <div class="panel-body"><!-- PANEL BODY START-->
			<form name="nutrition_form" action="" method="post" class="form-horizontal" id="nutrition_form"><!--Nutrition FORM START-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="nutrition_id" value="<?php echo $nutrition_id;?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
					<div class="col-sm-8">
						<?php if($edit){ $member_id=$workoutmember_id; }elseif(isset($_POST['member_id'])){$member_id=$_POST['member_id'];}else{$member_id='';}?>
						<select id="member_list" class="display-members" name="member_id" required="true">
						<option value=""><?php _e('Select Member','gym_mgt');?></option>
							<?php $get_members = array('role' => 'member');
							$membersdata=get_users($get_members);
							 if(!empty($membersdata))
							 {
								foreach ($membersdata as $member){
									if( $member->membership_status == "Continue")
										{?>
									<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
								<?php }
								}
							 }?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Start Date','gym_mgt');?><span class="require-field">*</span></label>
					
					<div class="col-sm-3">
					<input id="Start_date" class="datepicker form-control validate[required] text-input start_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text" value="<?php if($edit){ echo $result->start_date;}elseif(isset($_POST['start_date'])){echo $_POST['start_date'];}?>" name="start_date" readonly>
						
					</div>
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('End Date','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input id="end_date" class="datepicker form-control validate[required] text-input end_date" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text" value="<?php if($edit){ echo $result->expire_date;}elseif(isset($_POST['end_date'])){echo $_POST['end_date'];}?>" name="end_date" readonly>
						
					</div>
				</div>
				<div class="form-group">				
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Select Days','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">			
					<?php
					foreach (MJ_gmgt_days_array() as $key=>$name)
					{
					?>
					<div class="col-md-3" style="padding-left: 0px;">
						<div class="checkbox">
						  <label><input type="checkbox" value="" name="day[]" value="<?php echo $key;?>" id="<?php echo $key;?>" data-val="day"><?php echo $name; ?> </label>
						</div>
					</div>
					<?php
					}
					?>
					</div>	
				</div>
				<div class="form-group">				
					<div class="col-sm-12">	
						<label class="col-sm-2 control-label" for="notice_content"><?php _e('Nutrition Details','gym_mgt');?><span class="require-field">*</span></label>
						<div class="col-md-8 activity_list">				
							<label class="activity_title checkbox">				  		
							<strong>
								<input type="checkbox" value="" name="avtivity_id[]" value="breakfast" class="nutrition_check" 
								id="breakfast"  activity_title = "" data-val="nutrition_time"><?php _e('Break Fast','gym_mgt');?></strong></label>	
								<div id="txt_breakfast"></div>
								<label class="activity_title checkbox">
								<strong>
								<input type="checkbox" value="" name="avtivity_id[]" value="lunch" class="nutrition_check" 
								id="lunch"  activity_title = "" data-val="nutrition_time"><?php _e('Lunch','gym_mgt');?></strong></label>
								<div id="txt_lunch"></div>
								<label class="activity_title checkbox"><strong>
								<input type="checkbox" value="" name="avtivity_id[]" value="dinner" class="nutrition_check" 
								id="dinner"  activity_title = "" data-val="nutrition_time"><?php _e('Dinner','gym_mgt');?></strong></label>	
								<div id="txt_dinner"></div>							
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_nutrition_nonce' ); ?>
				<!--nonce-->
				<div class="col-sm-offset-2 col-sm-8">
					<div class="form-group">
						<div class="col-md-8">
							<input type="button" value="<?php _e('Step-1 Add Nutrition','gym_mgt');?>" name="save_nutrition" id="add_nutrition" class="btn btn-success"/>
						</div>
					</div>
					</div>
				<div id="display_nutrition_list" style="clear: both;"></div>
				<div class="clear"></div>
				
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Step 2 Save Nutrition Plan','gym_mgt'); }else{ _e('Step 2 Save Nutrition Plan','gym_mgt');}?>" name="save_nutrition" class="btn btn-success"/>
				</div>
		    </form><!--Nutrition FORM END-->
        </div><!--PANEL BODY DIV END-->
		<?php 
		if(isset($nutrition_logdata))
     	foreach($nutrition_logdata as $row)
		{
			$all_logdata=MJ_gmgt_get_nutritiondata($row->id); //var_dump($workout_logdata);
			$arranged_workout=MJ_gmgt_set_nutrition_array($all_logdata);
			?>
			<div class="workout_<?php echo $row->id;?> workout-block"><!--WORKOUT BLOCK DIV START-->
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-calendar"></i> 
					<?php echo "Start From <span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->start_date)."</span> To <span class='work_date'>".MJ_gmgt_getdate_in_input_box($row->expire_date); ?>
					<?php
					if($user_access['delete']=='1')
					{
					?>		
						<span class="removenutrition badge badge-delete pull-right" id="<?php echo $row->id;?>">X</span>	
					<?php
					}
					?>
					</h3>						
				</div>
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->
					<?php
					if(!empty($arranged_workout))
					{
					?>
					<div class="work_out_datalist_header">
						<div class="col-md-3">  
						<strong><?php _e('Day Name','gym_mgt');?></strong>
						</div>
						<div class="col-md-9">
						<span class="col-md-3"><?php _e('Time','gym_mgt');?></span>
						<span class="col-md-6"><?php _e('Description','gym_mgt');?></span>
						</div>
					</div>
					<?php 
					foreach($arranged_workout as $key=>$rowdata)
					{?>
						<div class="work_out_datalist">
						<div class="col-md-3 day_name">  
							<?php echo $key;?>
						</div>
						<div class="col-md-9">
							<?php 
							foreach($rowdata as $row)
							{										
								echo $row."<br>";										
							} 
							?>
						</div>
						</div>
				<?php } 
					}?>
				</div><!--PANEL WHITE DIV END-->
			</div><!--WORKOUT BLOCK DIV END-->
     	 <?php
		}	
	}
	?>
	</div><!-- TAB CONTENT DIV END -->
</div><!-- PANEL BODY DIV END -->