<?php
/**
* License Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;

$license = get_option( '_template_hero_license_key' );
$status = !empty( get_option( '_template_hero_license_key' ) ) ? get_option( '_template_hero_license_key' ) : '';
$status = !empty( get_option( '_template_hero_license_key_status' ) ) ? get_option( '_template_hero_license_key_status' ) : '';
if ( is_multisite() && ( $license == '' || $status == '' ) ) {
	switch_to_blog(1);
	$license = trim( get_option( '_template_hero_license_key' ) );
	$status  = !empty( get_option( '_template_hero_license_key_status' ) ) ? get_option( '_template_hero_license_key_status' ) : '';
	restore_current_blog();
	update_option( '_template_hero_license_key', $license, true );
	update_option( '_template_hero_license_key_status', $status, true );
}
?>
<div id="template-hero-elementor-license-options" class="card">

	<form class="">
		<input type="hidden" name="action" value="">
		<?php wp_nonce_field( 'th_create_token_security' ); ?>
		<table class="form-table">
			<tbody>
				
                <tr valign="top" class="th-remote-url-row th-table-row" style="width:100%">
					<th scope="row">
						<label for="template_hero_elementor_license_key">
							<?php _e( 'License Key', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td>
						<input style="width:300px" type="password" name="template_hero_elementor_license_key" id="template_hero_elementor_license_key" value="<?php echo esc_attr($license); ?>"  />
						<p class="description"><?php _e( 'Enter your license key.', 'template-hero-elementor' ) ?></p>
					</td>
				</tr>

                <tr valign="top" class="th-remote-url-row th-table-row" style="width:100%">
					<th scope="row">

                    <?php if( $status ) { ?>          
                    <span class="template-hero-license active"><?php _e('License Active', 'template-hero-elementor'); ?></span>
                    <?php } else { ?>
                        <span class="template-hero-license inactive"><?php _e('License Inactive', 'template-hero-elementor' ); ?></span>
                    <?php }?>

                    </td>
				</tr>
                
				<?php do_action( 'template_hero_elementor_license_after_status' ); ?>
			</tbody>
		</table>
		<p>
		<button class="th-submit-btn btn btn-primary" id="th-submit-btn-license" onClick="th_save_activate_license(event);">Activate License</button>
		</p>
		<?php do_action( 'template_hero_elementor_license_after_button' ); ?>
    </form>
    
</div>
