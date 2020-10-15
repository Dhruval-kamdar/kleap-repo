<?php 
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'membership_report';
?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('.sdate').datepicker({dateFormat: '<?php echo get_option('gmgt_datepicker_format');?>'}); 
	$('.edate').datepicker({dateFormat: '<?php echo get_option('gmgt_datepicker_format');?>'}); 
} );
</script>
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV START-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->
		<div class="row"><!--ROW DIV START-->
			<div class="col-md-12"><!--COL 12 DIV START-->
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->
					<div class="panel-body"><!--PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU START-->
			    			<a href="?page=gmgt_report&tab=membership_report" class="nav-tab <?php echo $active_tab == 'membership_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Membership', 'gym_mgt'); ?></a>
							<a href="?page=gmgt_report&tab=attendance_report" class="nav-tab <?php echo $active_tab == 'attendance_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Attendance', 'gym_mgt'); ?></a>
							<a href="?page=gmgt_report&tab=member_status_report" class="nav-tab <?php echo $active_tab == 'member_status_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Membership Status', 'gym_mgt'); ?></a>
							<a href="?page=gmgt_report&tab=payment_report" class="nav-tab <?php echo $active_tab == 'payment_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Income Payment', 'gym_mgt'); ?></a>
							<a href="?page=gmgt_report&tab=feepayment_report" class="nav-tab <?php echo $active_tab == 'feepayment_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Fee Payment', 'gym_mgt'); ?></a>
							<a href="?page=gmgt_report&tab=sell_product_report" class="nav-tab <?php echo $active_tab == 'sell_product_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Sells Product', 'gym_mgt'); ?></a>
						</h2> <!--NAV TAB WRAPPER MENU END-->
						<div class="clearfix"></div>
						<?php 
						if($active_tab == 'membership_report')
							require_once GMS_PLUGIN_DIR. '/admin/report/membership_report.php';
						if($active_tab == 'attendance_report')
							require_once GMS_PLUGIN_DIR. '/admin/report/attendance_report.php';
						if($active_tab == 'member_status_report')
							require_once GMS_PLUGIN_DIR. '/admin/report/membership_status_report.php';
						if($active_tab == 'payment_report')
							require_once GMS_PLUGIN_DIR. '/admin/report/income_report.php';
						if($active_tab == 'feepayment_report')
							require_once GMS_PLUGIN_DIR. '/admin/report/feepayment_report.php';
						if($active_tab == 'sell_product_report')
							require_once GMS_PLUGIN_DIR. '/admin/report/sell_product_report.php';								
						?>
			        </div><!--PANEL BODY DIV END-->
	            </div><!--PANEL WHITE DIV END-->
            </div><!--COL 12 DIV END-->
        </div><!--ROW DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->