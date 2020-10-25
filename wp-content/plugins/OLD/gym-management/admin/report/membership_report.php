<?php 
 global $wpdb;
 $table_name = $wpdb->prefix."gmgt_membershiptype";
 $q="SELECT * From $table_name";
 $member_ship_array = array();
 $result=$wpdb->get_results($q);
	foreach($result as $retrive)
	{
		$membership_id = $retrive->membership_id;		
		$member_ship_count =  count(get_users(array('role'=>'member','meta_key' => 'membership_id', 'meta_value' => $retrive->membership_id)));
		$member_ship_array[] = array('member_ship_id'=>$membership_id,
									 'member_ship_count'=>	$member_ship_count							
									);
	}
$chart_array = array();
$chart_array[] = array(__('Membership','gym_mgt'),__('Number Of Member','gym_mgt'));	
foreach($member_ship_array as $r)
{
	$chart_array[]=array( MJ_gmgt_get_membership_name($r['member_ship_id']),$r['member_ship_count']);
}
$options = Array(
		'title' => __('Membership Report','gym_mgt'),
		'titleTextStyle' => Array('color' => '#66707e'),
		'legend' =>Array('position' => 'right',
				'textStyle'=> Array('color' => '#66707e')),

		'hAxis' => Array(
				'title' =>  __('Membership Name','gym_mgt'),
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
				'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				
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