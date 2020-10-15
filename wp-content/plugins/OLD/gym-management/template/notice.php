<?php $curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_class=new MJ_Gmgtclassschedule;
$obj_notice=new MJ_Gmgtnotice;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'noticelist';
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
//SAVE NOTICE DATA
if(isset($_POST['save_notice']))
{
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{			
		$result=$obj_notice->MJ_gmgt_add_notice($_POST);
		
		if($result)
		{
			wp_redirect ( home_url().'?dashboard=user&page=notice&tab=noticelist&message=2');
		}
	}
	else
	{
		$result=$obj_notice->MJ_gmgt_add_notice($_POST);

		if($result)
		{
			wp_redirect ( home_url().'?dashboard=user&page=notice&tab=noticelist&message=1');
		}		
	}
}
//DELETE NOTICE DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{		
	$result=$obj_notice->MJ_gmgt_delete_notice($_REQUEST['notice_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=notice&tab=noticelist&message=3');
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
			_e('Notice added successfully.','gym_mgt');
		?></p></div>
		<?php 
		
	}
	elseif($message == 2)
	{?>
		<div id="message" class="updated below-h2 "><p><?php
			_e("Notice updated successfully.",'gym_mgt');
			?></p>
			</div>
		<?php 		
	}
	elseif($message == 3) 
	{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Notice deleted successfully.','gym_mgt');
		?></div></p>
		<?php			
	}
}
?>
<script type="text/javascript">
$(document).ready(function()
{
	jQuery('#notice_list').DataTable({
		"responsive": true,
		language:<?php echo MJ_gmgt_datatable_multi_language();?>	
		});
		$('#notice_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
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
<div class="panel-body panel-white"><!--PANEL BODY DIV START -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV TABS MENU START -->
	  	<li class="<?php if($active_tab=='noticelist'){?>active<?php }?>">
			<a href="?dashboard=user&page=notice&tab=noticelist" class="tab <?php echo $active_tab == 'noticelist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Notice List', 'gym_mgt'); ?></a>
          </a>
      </li>	
    </ul><!--NAV TABS MENU END -->
	<div class="tab-content"><!--TAB CONTENT START -->
	<?php 
	if($active_tab == 'noticelist')
	{ 
		?>	
    	<div class="panel-body"><!-- PANEL BODY DIV START -->
            <div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
				<table id="notice_list" class="display" cellspacing="0" width="100%"><!-- TABLE NOTICE LIST START -->
					<thead>
						<tr>
							<th><?php  _e( 'Notice Title', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Notice Comment', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Notice For', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Class', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Start Date', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'End Date', 'gym_mgt' ) ;?></th>
							  <?php if($obj_gym->role == 'member' || $obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
								   {?>
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
							   <?php }?>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php  _e( 'Notice Title', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Notice Comment', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Notice For', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Class', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Start Date', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'End Date', 'gym_mgt' ) ;?></th>
							  <?php if($obj_gym->role == 'member' ||$obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
								   {?>
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
								   <?php }?>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						if($user_access['own_data']=='1')
						{
							$noticedata =$obj_notice->MJ_gmgt_get_notice($obj_gym->role);
						}
						else	
						{
							$noticedata =$obj_notice->MJ_gmgt_get_all_notice();
						}	
						if(!empty($noticedata))
						{
							foreach ($noticedata as $retrieved_data)
							{
								$class_id=get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true);
								if($class_id!="")
								{
									$ClassArr=MJ_gmgt_get_current_user_classis($curr_user_id);
									$staff_classes=$obj_class->MJ_gmgt_getClassesByStaffmeber($curr_user_id);
									if($obj_gym->role=="member" && in_array($class_id,$ClassArr))
									{
									?>
										<tr>
											<td class="noticetitle"><a href=""><?php echo $retrieved_data->post_title;?></a></td>
											
											<td class="noticecontent"><?php $strlength= strlen($retrieved_data->post_content);
												if($strlength > 60)
													echo substr($retrieved_data->post_content, 0,60).'...';
												else
													echo $retrieved_data->post_content;?></td>
											<td class="productquentity"><?php echo ucwords(str_replace("_"," ",get_post_meta( $retrieved_data->ID, 'notice_for',true)));?></td>
											<td>
											 <?php 
											 if(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="" && get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) =="all")
											 {
												 _e('All','gym_mgt');
											 }
											 elseif(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="")
											 {
												echo MJ_gmgt_get_class_name(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true));
											 }
											 else
											 {
												 echo '-';
											 }
											 ?></td>
											  <td><?php echo get_post_meta($retrieved_data->ID,'gmgt_start_date',true);?></td>
											 <td><?php echo get_post_meta($retrieved_data->ID,'gmgt_end_date',true);?></td>
											<td class="action"> 
											<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->ID?>" type="<?php echo 'view_notice';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
											</td>
										</tr>
									<?php
									}	
									if($obj_gym->role=="staff_member" && !empty($staff_classes) && in_array($class_id,$staff_classes))
									{
									?>
										<tr>
											<td class="noticetitle"><a href=""><?php echo $retrieved_data->post_title;?></a></td>
											
											<td class="noticecontent"><?php $strlength= strlen($retrieved_data->post_content);
												if($strlength > 60)
													echo substr($retrieved_data->post_content, 0,60).'...';
												else
													echo $retrieved_data->post_content;?></td>
											<td class="productquentity"><?php echo ucwords(str_replace("_"," ",get_post_meta( $retrieved_data->ID, 'notice_for',true)));?></td>
											<td>
											 <?php 
											 if(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="" && get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) =="all")
											 {
												 _e('All','gym_mgt');
											 }
											 elseif(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="")
											 {
												echo MJ_gmgt_get_class_name(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true));
											 }
											 else
											 {
												 echo '-';
											 }
											 ?></td>
											  <td><?php echo get_post_meta($retrieved_data->ID,'gmgt_start_date',true);?></td>
											 <td><?php echo get_post_meta($retrieved_data->ID,'gmgt_end_date',true);?></td>
											
											<td class="action"> 
											<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->ID?>" type="<?php echo 'view_notice';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
											</td>
										</tr>
									<?php 
									}
										
								}
								else
								{
								?>
									<tr>
										<td class="noticetitle"><a href=""><?php echo $retrieved_data->post_title;?></a></td>
										<td class="noticecontent"><?php $strlength= strlen($retrieved_data->post_content);
											if($strlength > 60)
												echo substr($retrieved_data->post_content, 0,60).'...';
											else
												echo $retrieved_data->post_content;?></td>
										<td class="productquentity"><?php echo ucwords(str_replace("_"," ",get_post_meta( $retrieved_data->ID, 'notice_for',true)));?></td>
										<td>
										 <?php 
										 if(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="" && get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) =="all")
										 {
											 _e('All','gym_mgt');
										 }
										 elseif(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="")
										 {
											echo MJ_gmgt_get_class_name(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true));
										}
										else
										{
											echo '-';
										}
										?></td>
										  <td><?php echo get_post_meta($retrieved_data->ID,'gmgt_start_date',true);?></td>
										 <td><?php echo get_post_meta($retrieved_data->ID,'gmgt_end_date',true);?></td>
										
										<?php if($obj_gym->role == 'member' || $obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
										   {?>
										<td class="action"> 
										<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->ID?>" type="<?php echo 'view_notice';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
										
										</td>
										   <?php } ?>
									</tr>
									<?php 
								}	
							}
							
						}?>
					</tbody>
				</table><!-- TABLE NOTICE LIST END -->
			</div><!--TABLE RESPONSIVE DIV END -->
		</div><!-- PANEL BODY DIV END -->
		<?php 
	}
	?>	
	</div><!-- TAB CONTENT DIV END -->
</div><!-- PANEL BODY DIV END -->