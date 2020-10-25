<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#group_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		
});
</script>
<?php 	
if($active_tab == 'schedulelist')
{
?>
	<div class="panel-body"><!-- PANEL BODY DIV START-->
		<table class="table table-bordered"><!-- TABLE CLASS SCHEDULE START-->
			<?php		   
			foreach(MJ_gmgt_days_array() as $daykey => $dayname)
			{
			?>			  
				<tr>
					<th width="100"><?php echo $dayname;?></th>
					<td>
						 <?php
							$period = $obj_class->MJ_gmgt_get_schedule_byday($daykey);
							if(!empty($period))
							{
								foreach($period as $period_data)
								{
									if(!empty($period_data))
									{
										echo '<div class="btn-group m-b-sm">';
										echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
										echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).'- '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
										
										echo '</span></span><span class="caret"></span></button>';
										echo '<ul role="menu" class="dropdown-menu">
											<li><a href="?page=gmgt_class&tab=addclass&action=edit&class_id='.$period_data['class_id'].'">'.__('Edit','gym_mgt').'</a></li>
											<li><a href="?page=gmgt_class&tab=schedulelist&action=delete&class_id='.$period_data['class_id'].'" onclick="return confirm(\'Are you sure, you want to delete?\')">'.__('Delete','gym_mgt').'</a></li>
										</ul>';
										echo '</div>';
									}									
								}
							}
						 ?>
					</td>
				</tr>
			<?php	
			}
			?>
        </table><!-- TABLE CLASS SCHEDULE END-->
    </div><!-- PANEL BODY DIV END-->
<?php 
}
?>