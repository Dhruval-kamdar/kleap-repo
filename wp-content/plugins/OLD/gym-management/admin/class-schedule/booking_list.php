<?php
if($active_tab == 'booking_list')
{
	  $obj_class=new MJ_Gmgtclassschedule;
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
	<script type="text/javascript">
		$(document).ready(function() {
			jQuery('.booking_list').DataTable({
				"responsive": true,
				"order": [[ 2, "asc" ]],
				"aoColumns":[
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
		} );
	</script>
    <div class="panel-body"> <!-- PANEL BODY DIV START-->
		<div class="table-responsive"> <!-- TABLE RESPONSIVE DIV START-->
		    <table id="booking_list113" class="display booking_list" cellspacing="0" width="100%"> <!-- Booking LIST TABEL START-->
				<thead>
					<tr>
						<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Class Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Class Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Booking Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Day', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Starting Time', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Ending Time', 'gym_mgt' ) ;?></th>            
						<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>            
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Class Name', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Class Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Booking Date', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Day', 'gym_mgt' ) ; ?></th>
						<th><?php  _e( 'Starting Time', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Ending Time', 'gym_mgt' ) ;?></th>  
						<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>       
					</tr>
				</tfoot>
				<tbody>
					<?php 
					$bookingdata=$obj_class->MJ_gmgt_get_all_booked_class();
					if(!empty($bookingdata))
					{
						foreach ($bookingdata as $retrieved_data)
						{
						?>
							<tr>
								<td class="membername"><a href="#"><?php echo MJ_gmgt_get_display_name($retrieved_data->member_id);?></a></td>
								<td class="class_name"><?php print  $obj_class->MJ_gmgt_get_class_name($retrieved_data->class_id);?></td>
								<td class="class_name"><?php print  str_replace('00:00:00',"",$retrieved_data->class_booking_date)?></td>
								<td class="class_name"><?php print  str_replace('00:00:00',"",$retrieved_data->booking_date)?></td>
								<td class="starttime"><?php echo $retrieved_data->booking_day;?></td>
								<?php $class_data = $obj_class->MJ_gmgt_get_single_class($retrieved_data->class_id); ?>
								<td class="starttime"><?php echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->start_time);?></td>
								<td class="endtime"><?php echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->end_time);?></td>
								<td class="action">
									<a href="?page=gmgt_class&tab=booking_list&action=delete&class_booking_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
									<?php _e( 'Delete', 'gym_mgt' ) ;?> 
									</a>
								</td>

							</tr>
						<?php 
						} 
					}?>     
				</tbody>        
			</table><!-- Booking LIST TABEL END-->
        </div><!-- TABLE RESPONSIVE DIV END-->
	</div><!-- PANEL BODY DIV END-->
  <?php 
}
?>