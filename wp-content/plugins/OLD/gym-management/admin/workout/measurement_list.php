<script type="text/javascript">
$(document).ready(function()
{
	jQuery('#measurement_list').DataTable({
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
<div class="panel-body"><!--PANEL BODY DIV STRAT-->
    <div class="table-responsive"><!--TABLE RESPONSIVE  DIV STRAT-->
        <table id="measurement_list" class="display" cellspacing="0" width="100%"><!--Measurement LIST TABLE STRAT-->
        	<thead>
				<tr>
					<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Measurement', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Result', 'gym_mgt' ) ;?></th>			
					<th><?php  _e( 'Record Date', 'gym_mgt' ) ;?></th>			
					<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
				</tr>
			</thead> 
			<tfoot>
				<tr>
					<th><?php  _e( 'Member Name', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Measurement', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Result', 'gym_mgt' ) ;?></th>			
					<th><?php  _e( 'Record Date', 'gym_mgt' ) ;?></th>			
					<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
				</tr>
			</tfoot>			
			<tbody>
			<?php 
			//GET ALL Measurement DATA
			$measurement_data=$obj_workout->MJ_gmgt_get_all_measurement();
			if(!empty($measurement_data))
			{
				foreach ($measurement_data as $retrieved_data)
				{
					?>
					<tr>
						<td class="workoutname">
						<?php $user=get_userdata($retrieved_data->user_id);
						$display_label=$user->display_name;
						$memberid=get_user_meta($retrieved_data->user_id,'member_id',true);
							if($memberid)
								$display_label.=" (".$memberid.")";
							echo $display_label;?></td>
						<td class="recorddate"><?php echo $retrieved_data->result_measurment;?></td>
						<td class="duration"><?php echo $retrieved_data->result;?></td>
						<td class="result"><?php echo $retrieved_data->result_date;?></td>
						<td class="action"> 
							<a href="?page=gmgt_workout&tab=addmeasurement&action=edit&measurment_id=<?php echo $retrieved_data->measurment_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
							<a href="?page=gmgt_workout&tab=measurement_list&action=delete&measurment_id=<?php echo $retrieved_data->measurment_id;?>" class="btn btn-danger" 
							onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
							<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>						
						</td>					   
					</tr>
					<?php 
				} 
			}
			?>
			</tbody>
        </table><!--Measurement LIST TABLE END-->
    </div><!--TABLE RESPONSIVE DIV END-->
</div><!--PANEL BODY DIV END-->