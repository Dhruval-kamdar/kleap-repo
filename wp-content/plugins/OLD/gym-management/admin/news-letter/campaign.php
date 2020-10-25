<?php 
$retval = $api->campaigns();
$api->useSecure(true);
$retval1 = $api->lists();
?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#setting_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
} );
</script>
<div class="panel-body"><!--PANEL BODY DIV STRAT-->
    <form name="student_form" action="" method="post" class="form-horizontal" id="setting_form"><!--Campaign FORM STRAT-->
	    <div class="form-group">
			<label class="col-sm-2 control-label" for="quote_form"><?php _e('MailChimp list','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="list_id" id="quote_form"  class="form-control validate[required]">
					<option value=""><?php _e('Select list','gym_mgt');?></option>
					<?php 
					foreach ($retval1['data'] as $list)
					{						
						echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<!--nonce-->
		<?php wp_nonce_field( 'send_campign_nonce' ); ?>
		<!--nonce-->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="quote_form"><?php _e('Campaign list','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="camp_id" id="quote_form"  class="form-control validate[required]">
					<option value=""><?php _e('Select Campaign','gym_mgt');?></option>
					<?php 
					foreach ($retval['data'] as $c)
					{						
						echo '<option value="'.$c['id'].'">'.$c['title'].'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
			<input type="submit" value="<?php _e('Send Campaign', 'gym_mgt' ); ?>" name="send_campign" class="btn btn-success"/>
		</div>
    </form><!--Campaign FORM END-->
</div><!--PANEL BODYDIV END-->