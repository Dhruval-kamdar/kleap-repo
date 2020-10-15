<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#activity_id').multiselect();
	$('#acitivity_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	jQuery('#activity_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
			language:<?php echo MJ_gmgt_datatable_multi_language();?>			  
		});
} );
</script>
<?php 	
if($active_tab == 'view-activity')
{        	
	$membership_id=0;
	if(isset($_REQUEST['membership_id']))
		$membership_id=$_REQUEST['membership_id'];
	$activity_result = $obj_membership->MJ_gmgt_get_membership_activities($membership_id);
	?>		
    <form name="wcwm_report" action="" method="post">   <!--ACTIVITY LIST FORM START--> 
        <div class="panel-body"><!--PANEL BODY DIV START-->
        	<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
				<table id="activity_list" class="display" cellspacing="0" width="100%"><!--ACTIVITY LIST TABLE START-->
					<thead>
						<tr>
						<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
						   <th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
						   <th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
						</tr>
					</thead>			 
					<tfoot>
						<tr>
						<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
						</tr>
					</tfoot>			 
					<tbody>
						 <?php 
						if(!empty($activity_result))
						{							 
							foreach ($activity_result as $activities)
							{ 							
								$retrieved_data=$obj_activity->MJ_gmgt_get_single_activity($activities->activity_id);?>
								<tr>
									<td class="activityname"><a href="?page=gmgt_activity&tab=addactivity&action=edit&activity_id=<?php echo $retrieved_data->activity_id;?>"><?php echo $retrieved_data->activity_title;?></a></td>
									<td class="category"><?php echo get_the_title($retrieved_data->activity_cat_id);?></td>
									<td class="productquentity"><?php $user=get_userdata($retrieved_data->activity_assigned_to);
									echo $user->display_name;?></td>
									<td class="membership"><?php echo MJ_gmgt_get_membership_name($activities->membership_id);?></td>
									 <td class="action"><a href="?page=gmgt_membership_type&tab=membershiplist&action=delete-activity&membership_id=<?php echo $membership_id;?>&assign_id=<?php echo $activities->id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
									<?php _e( 'Delete', 'gym_mgt' ) ;?> </a> </td>
								 </tr>
							<?php
							} 			
						} 
						?>			 
					</tbody>        
				</table><!--ACTIVITY LIST TABLE END-->
			</div><!--TABLE RESPONSIVE DIV END-->
        </div><!--PANEL BODY DIV END-->       
	</form>  <!--ACTIVITY LIST FORM END -->   
<?php 
}
?>