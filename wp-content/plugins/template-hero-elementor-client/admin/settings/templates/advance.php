<?php
/**
* General Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;

$template_hero_elementor_options = get_option( 'template_hero_elementor_advance_options', array() );
$delete_all                      = !empty( $template_hero_elementor_options['template_hero_elementor_delete_data'] ) ? $template_hero_elementor_options['template_hero_elementor_delete_data'] : '';
$allow_lib_creation              = !empty( $template_hero_elementor_options['template_hero_elementor_admin_create_lib'] ) ? $template_hero_elementor_options['template_hero_elementor_admin_create_lib'] : '';
$network_wide                    = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
$allow_dev                       = !empty( $template_hero_elementor_options['template_hero_elementor_allowed_extensions'] ) ? $template_hero_elementor_options['template_hero_elementor_allowed_extensions'] : '';
$tab_title        = !empty( get_site_option( 'th_cl_tab_title', 'Custom Templates' ) ) ? get_site_option( 'th_cl_tab_title', 'Custom Templates' ) : 'Custom Templates';
$admin_menu_title = !empty( get_site_option( 'th_cl_admin_menu_title', 'Template Hero Client' ) ) ? get_site_option( 'th_cl_admin_menu_title', 'Template Hero Client' ) : 'Template Hero Client' ;
$network_title    = !empty( get_site_option( 'th_cl_network_menu_title', 'Template Hero Client' ) ) ? get_site_option( 'th_cl_network_menu_title', 'Template Hero Client' )  : 'Template Hero Client';

?>
<div id="template-hero-elementor-general-options" class="card">
	<div style="color:red;"><h4>Heads up! Make the edits only if you know what you are doing!</h4><a href="https://docs.waashero.com/docs/template-hero-for-elementor/"> Please refer to our docs. </a></div>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="template_hero_elementor_admin_advance_settings">
		<?php wp_nonce_field( 'template_hero_elementor_admin_advance_settings_action', 'template_hero_elementor_admin_advance_settings_action' ); ?>
		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row" >
						<label for="th_cl_tab_title">
							<?php _e( 'Templates tab title', 'th-elementor-server-custom-template-tab' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label  for="th_cl_tab_title">
						<input type="text" name="th_cl_tab_title" value="<?php echo $tab_title; ?>" placeholder = "<?php echo $tab_title; ?>" class="th-del-lib" id="th_cl_tab_title"/>
					</label>
						<p class="make-lib-description"><?php _e( 'Use this field to white label Templates Tab title.', 'th-elementor-server-custom-template-tab' ); ?></p>
						<span></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" >
						<label for="th_cl_admin_menu_title">
							<?php _e( 'Admin menu title', 'th-elementor-server-custom-template-tab' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label  for="th_cl_admin_menu_title">
						<input type="text" name="th_cl_admin_menu_title" placeholder = "<?php echo $admin_menu_title; ?>" value="<?php echo $admin_menu_title; ?>" class="th-del-lib" id="th_cl_admin_menu_title"/>
					</label>
						<p class="make-lib-description"><?php _e( 'Use this field to white label admin menu title.', 'template-hero-elementor' ); ?></p>
					</td>
				</tr>
			<?php if( is_multisite() && is_network_admin() ) { ?>
				<tr valign="top">
					<th scope="row" >
						<label for="th_cl_network_menu_title">
							<?php _e( 'Network menu title', 'the-wu-integration' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label  for="th_cl_network_menu_title">
						<input type="text" name="th_cl_network_menu_title" placeholder = "<?php echo $network_title; ?>"  value="<?php echo $network_title; ?>" class="th-del-lib" id="th_cl_network_menu_title"/>
					</label>
						<p class="make-lib-description"><?php _e( 'Use this field to white label network admin menu title.', 'template-hero-elementor' ); ?></p>
					</td>
				</tr>
				
			<?php } ?>
				<tr valign="top">
					<th scope="row" >
						<label for="template_hero_elementor_delete_data">
							<?php _e( 'Delete data on uninstall', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label class="switch" for="template_hero_elementor_delete_data">
						<input type="checkbox" name="template_hero_elementor_delete_data" class="th-del-lib" id="template_hero_elementor_delete_data"<?php if( $delete_all == 'on' ) { ?>checked="checked"<?php } ?> />
						<div class="slider round"></div>
					</label>
						<p class="make-lib-description"><?php _e( 'If checked It Will Delete All The Data On Uninstall.', 'template-hero-elementor' ); ?></p>
					</td>
				</tr>
                <tr valign="top">
					<th scope="row" >
						<label for="template_hero_elementor_sync_library">
							<?php _e( 'Sync library', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label class="" for="template_hero_elementor_sync_library">
					<button type="button" id="reset-library" data-nonce="<?php echo  wp_create_nonce( 'elementor_reset_library' ); ?>" class="button elementor-button-spinner">Sync Library</button>
					</label>
                        <br>
						<p class="make-lib-description"><?php _e( 'Elementor Library automatically updates on a daily basis. You can also manually update it by clicking on the sync button.', 'template-hero-elementor' ); ?></p>
						<span></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" >
						<label for="template_hero_elementor_allowed_extensions">
							<?php _e( 'Allowed 3rd party integration', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label class="switch" for="template_hero_elementor_allowed_extensions">
						<input type="checkbox" name="template_hero_elementor_allowed_extensions" class="th-allow-extension" id="template_hero_elementor_allowed_extensions"<?php if( $allow_dev == 'on' ) { ?>checked="checked"<?php } ?> />
						<div class="slider round"></div>
					</label>
						<br>
						<p class="make-lib-description"><?php _e( 'Warning! If checked it will allow 3rd parties to access Core functions.', 'template-hero-elementor' ); ?></p>
					</td>
				</tr>
					
				<?php do_action( 'template_hero_elementor_advance_settings', $template_hero_elementor_options ); ?>
			</tbody>
		</table>
		
		<p>
		<?php
			submit_button( __( 'Save Settings', 'template-hero-elementor' ), 'primary', 'template_hero_elementor_advance_settings_submit' );
		?>
		</p>
			
	</form>
</div>


<?php
