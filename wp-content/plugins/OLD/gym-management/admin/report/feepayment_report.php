<?php 
$month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",'9'=>"September",'10'=>"October",'11'=>"November",'12'=>"December",);
$year =isset($_POST['year'])?$_POST['year']:date('Y');
global $wpdb;

$table_name = $wpdb->prefix."gmgt_membership_payment_history";
$q="SELECT EXTRACT(MONTH FROM paid_by_date) as date,sum(amount) as count FROM ".$table_name." WHERE YEAR(paid_by_date) =".$year." group by month(paid_by_date) ORDER BY paid_by_date ASC";

$result=$wpdb->get_results($q);
$chart_array = array();
$chart_array[] = array(__('Month','gym_mgt'),__('Fee Payment','gym_mgt'));
foreach($result as $r)
{
$chart_array[]=array( $month[$r->date],(int)$r->count);
}
$options = Array(
			'title' => __('Fee Payment Report By Month','gym_mgt'),
			'titleTextStyle' => Array('color' => '#66707e'),
			'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e')),

			'hAxis' => Array(
				'title' => __('Month','gym_mgt'),
				 'format' => '#',
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'maxAlternation' => 2

				),
			'vAxis' => Array(
				'title' => __('Fee Payment','gym_mgt'),
				 'minValue' => 0,
				'maxValue' => 6,
				 'format' => '#',
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans')
				),
 		'colors' => array('#22BAA0')
			);
require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 
} );
</script>
<div id="chart_div" class="chart_div">
<?php 
 if(empty($result)) 
 {?>
    <div class="clear col-md-12"><h3><?php _e("There is not enough data to generate report.",'gym_mgt');?> </h3></div>
  <?php 
 } ?>
</div>
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php if(!empty($result))
						echo $chart;?>
  </script>