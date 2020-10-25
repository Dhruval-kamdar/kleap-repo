<?php 
if(isset($_REQUEST['attendance_report']))
{
    global $wpdb;
	$table_attendance = $wpdb->prefix .'gmgt_attendence';
	$table_class = $wpdb->prefix .'gmgt_class_schedule';
	$sdate =MJ_gmgt_get_format_for_db($_REQUEST['sdate']);
	$edate = MJ_gmgt_get_format_for_db($_REQUEST['edate']);
	$report_2 =$wpdb->get_results("SELECT  at.class_id, 
	SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
	SUM(case when `status` ='Absent' then 1 else 0 end) as Absent 
	from $table_attendance as at,$table_class as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.class_id = cl.class_id AND at.role_name = 'member' GROUP BY at.class_id") ;
		$chart_array[] = array(__('Class','gym_mgt'),__('Present','gym_mgt'),__('Absent','gym_mgt'));
	if(!empty($report_2))
		foreach($report_2 as $result)
		{
			$class_id =MJ_gmgt_get_class_name($result->class_id);
			$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
		}
	$options = Array(
	 		'title' => __('Member Attendance Report','gym_mgt'),
			'titleTextStyle' => Array('color' => '#66707e'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#66707e')),
				
			'hAxis' => Array(
					'title' =>  __('Class','gym_mgt'),
					 'format' => '#',
					'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'maxAlternation' => 2


			),
			'vAxis' => Array(
					'title' =>  __('No of Member','gym_mgt'),
					'minValue' => 0,
					'maxValue' => 6,
					'format' => '#',
					'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans')
			),
			'colors' => array('#22BAA0','#f25656')
	);
}
require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.sdate').datepicker({dateFormat: '<?php echo get_option('gmgt_datepicker_format');?>'}); 
	$('.edate').datepicker({dateFormat: '<?php echo get_option('gmgt_datepicker_format');?>'}); 
} );
</script>

    <div class="panel-body">
	    <form method="post">  
		<div class="form-group col-md-3">
			<label for="exam_id"><?php _e('Start Date','gym_mgt');?></label>
		   
						
					<input type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  class="form-control sdate" name="sdate" 
					value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo MJ_gmgt_getdate_in_input_box(date('Y-m-d'));?>" readonly>
					
		</div>
		<div class="form-group col-md-3">
			<label for="exam_id"><?php _e('End Date','gym_mgt');?></label>
				<input type="text"  class="form-control edate" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>"  name="edate" 
				value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo MJ_gmgt_getdate_in_input_box(date('Y-m-d'));?>" readonly>
					
		</div>
		<div class="form-group col-md-3 button-possition">
			<label for="subject_id">&nbsp;</label>
			<input type="submit" name="attendance_report" Value="<?php _e('Go','gym_mgt');?>"  class="btn btn-success"/>
		</div>
			
		</form>
	</div>
    	<?php if(isset($report_2) && count($report_2) >0)
		{
    		$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    	   ?>
		  <div id="chart_div"  class="chart_div">
		  
		 <?php  if(isset($report_2) && empty($report_2)) 
 {?>
    <div class="clear col-md-12"><?php _e("There is not enough data to generate report.",'gym_mgt');?></div>
  <?php 
 } ?>
  </div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
 </script>
  <?php }
 if(isset($report_2) && empty($report_2)) 
 {?>
    <div class="clear col-md-12"><?php _e("There is not enough data to generate report.",'gym_mgt');?></div>
  <?php 
 } ?>