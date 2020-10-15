<?php 
$month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",'9'=>"September",'10'=>"October",'11'=>"November",'12'=>"December",);
$year =isset($_POST['year'])?$_POST['year']:date('Y');
global $wpdb;

$table_name = $wpdb->prefix."gmgt_store";
$q="SELECT * FROM ".$table_name." WHERE YEAR(sell_date) =".$year." ORDER BY sell_date ASC";

$result=$wpdb->get_results($q);
$month_wise_count=array();
foreach($result as $key=>$value)
{
	$total_quantity=0;
	$all_entry=json_decode($value->entry);
	foreach($all_entry as $entry)
	{
		$total_quantity+=$entry->quentity;
	}	
	$sell_date = date_parse_from_format("Y-m-d",$value->sell_date);
	
	$month_wise_count[]=array('sell_date'=>$sell_date["month"],'quentity'=>$total_quantity);
}
$sumArray = array(); 
foreach ($month_wise_count as $value1) 
{ 
	$value2=(object)$value1;
	if(isset($sumArray[$value2->sell_date]))
	{
		$sumArray[$value2->sell_date] = $sumArray[$value2->sell_date] + (int)$value2->quentity;
	}
	else
	{
		$sumArray[$value2->sell_date] = (int)$value2->quentity; 
	}		
} 
$chart_array = array();
$chart_array[] = array('Month','Sells Product');
foreach($sumArray as $month_value=>$quentity)
{
	$chart_array[]=array( $month[$month_value],(int)$quentity);
}
$options = Array(
			'title' => __('Sells Product Report By Month','gym_mgt'),
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
				'title' => __('Sells Product','gym_mgt'),
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
<div id="chart_div" class="chart_div"></div>
<!-- Javascript --> 
<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
<script type="text/javascript">
		<?php if(!empty($result))
					echo $chart;?>
</script>