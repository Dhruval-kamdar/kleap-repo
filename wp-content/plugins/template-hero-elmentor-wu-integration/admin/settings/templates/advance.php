<?php
/**
* General Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;

$network_title    = !empty( get_site_option( 'the_wu_network_menu_title', 'THE Wp Ultimo' ) ) ? get_site_option( 'the_wu_network_menu_title', 'THE Wp Ultimo' )  : 'THE Wp Ultimo';

?>
<div id="template-hero-elementor-general-options" class="card">
<div style="color:red;"><h4>Heads Up! Make the edits only if you have enough knowledge about that.</h4></div>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="the_wu_admin_advance_settings">
		<?php wp_nonce_field( 'the_wu_admin_advance_settings_action', 'the_wu_admin_advance_settings_action' ); ?>
		<table class="form-table">
			<tbody>

			<?php if( is_multisite() && is_network_admin() ) { ?>
				<tr valign="top">
					<th scope="row" >
						<label for="the_wu_network_menu_title">
							<?php _e( 'Network menu title', 'the-wu-integration' ); ?>
						</label>
					</th>
					<td class="templatehero-make-library-box">
					<label  for="the_wu_network_menu_title">
						<input type="text" name="the_wu_network_menu_title" placeholder = "<?php echo $network_title; ?>"  value="<?php echo $network_title; ?>" class="th-del-lib" id="the_wu_network_menu_title"/>
					</label>
                        <br>
						<p class="make-lib-description"><?php _e( 'Use this field to white label network admin menu title.', 'the-wu-integration' ); ?></p>
					</td>
				</tr>
			<?php } ?>
					
				<?php do_action( 'the_wu_advance_settings' ); ?>
			</tbody>
		</table>
		
		<p>
		<?php
			submit_button( __( 'Save Settings', 'the-wu-integration' ), 'primary', 'the_wu_advance_settings_submit' );
		?>
		</p>
			
	</form>
</div>


<?php
