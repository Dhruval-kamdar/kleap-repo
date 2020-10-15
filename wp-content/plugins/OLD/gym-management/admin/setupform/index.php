<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'setup';
?>
<div id="cmgt_imgSpinner1">	
</div>
<div class="gmgt_ajax-ani"></div>
<div class="gmgt_ajax-img">
	<img src="<?php echo GMS_PLUGIN_URL.'/assets/images/loading.gif';?>" height="50px" width="50px">
</div>
<div class="page-inner" style="min-height:1088px !important"><!--PAGE INNER DIV START-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?>
		</h3>
	</div>	 
	<?php 
	if(isset($_REQUEST['varify_key']))
	{
		$verify_result = MJ_gmgt_submit_setupform($_POST);
		if($verify_result['gmgt_verify'] != '0')
		{
			echo '<div id="message" class="updated notice notice-success is-dismissible"><p>'.$verify_result['message'].'</p>
			<button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
			
		}
	}
	?>
	<script type="text/javascript">
	$(document).ready(function() {
	$('#verification_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	});
	</script>
	<?php 
	if(isset($_SESSION['gmgt_verify']) && $_SESSION['gmgt_verify'] == '3')
	{
		?>
	<div id="message" class="updated notice notice-success">
	<?php _e('There seems to be some problem please try after sometime or contact us on sales@dasinfomeida.com','gym_mgt');?>
		</div>
	<?php 
	}
	elseif(isset($_SESSION['gmgt_verify']) && $_SESSION['gmgt_verify'] == '1')
	{
		?>
	<div id="message" class="updated notice notice-success">
	<?php _e('Please provide correct Envato purchase key.','gym_mgt');?>
	</div>
	<?php 
	}
	else
	{
	?>
	<div id="message" class="updated notice notice-success" style="display:none;"></div>
	<?php }?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->
		<div class="row"><!--ROW DIV START-->
			<div class="col-md-12"><!--COL 12 DIV START-->
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->
					<div class="panel-body"><!--PANEL BODY DIV START-->	
					  <form name="verification_form" action="" method="post" class="form-horizontal" id="verification_form"><!--VERIFICATION FORM START-->
							<div class="form-group">
								<label class="col-sm-2 control-label" for="domain_name">
								<?php _e('Domain','gym_mgt');?> <span class="require-field">*</span></label>
								<div class="col-sm-8">
									<input id="server_name" class="form-control validate[required]" type="text" 
									value="<?php echo $_SERVER['SERVER_NAME'];?>" name="domain_name" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="licence_key"><?php _e('Envato License key','gym_mgt');?> <span class="require-field">*</span></label>
								<div class="col-sm-8">
									<input id="licence_key" class="form-control validate[required]" type="text"  value="" name="licence_key">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="enter_email"><?php _e('Email','gym_mgt');?> <span class="require-field">*</span></label>
								<div class="col-sm-8">
									<input id="enter_email" class="form-control validate[required,custom[email]]" type="text"  value="" name="enter_email">
								</div>
							</div>
							<div class="col-sm-offset-2 col-sm-8">
								<input type="submit" value="<?php _e('Submit','gym_mgt');?>" name="varify_key" id="varify_key" class="btn btn-success"/>
							</div>
						</form>	<!--VERIFICATION FORM END-->
					</div><!--PANEL BODY DIV END-->		
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
	</div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->