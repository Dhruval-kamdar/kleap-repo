<?php
/**
* License Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;

$license = get_option( '_the_wu_license_key' );
$status = !empty( get_option( '_the_wu_license_key' ) ) ? get_option( '_the_wu_license_key' ) : '';
$status = !empty( get_option( '_the_wu_license_key_status' ) ) ? get_option( '_the_wu_license_key_status' ) : '';

?>
<div id="template-hero-elementor-license-options" class="card">

<form class="">
	<input type="hidden" name="action" value="">
	<?php wp_nonce_field( 'th_create_token_security' ); ?>
	<table class="form-table">
		<tbody>
			
			<tr valign="top" class="th-remote-url-row th-table-row" style="width:100%">
				<th scope="row">
					<label for="the_wu_license_key">
						<?php _e( 'License Key', 'the-wu-integration' ); ?>
					</label>
				</th>
				<td>
					<input style="width:300px" type="password" name="the_wu_license_key" id="the_wu_license_key" value="<?php echo esc_attr($license); ?>"  />
					<p class="description"><?php _e( 'Enter your license key.', 'the-wu-integration' ); ?></p>
				</td>
			</tr>

			<tr valign="top" class="th-remote-url-row th-table-row" style="width:100%">
				<th scope="row">

				<?php if( $status ) { ?>          
				<span class="template-hero-license active"><?php _e( 'License Active', 'the-wu-integration' ); ?></span>
				<?php } else { ?>
					<span class="template-hero-license inactive"><?php _e( 'License Inactive', 'the-wu-integration' ); ?></span>
				<?php }?>

				</td>
			</tr>
			

		</tbody>
	</table>
	<p>
	<button class="th-submit-btn btn btn-primary" id="th-submit-btn-license" onClick="the_wu_save_activate_license(event);">Activate License</button>
	</p>
</form>

</div>

