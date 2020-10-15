<?php
/**
* General Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;

$template_hero_elementor_options = get_option( 'template_hero_elementor_options', array() );
$current_site_url  = trim ( get_option( 'template_hero_current_url', '' ) );
$client_token      = !empty( $template_hero_elementor_options['template_hero_elementor_remote_token'] ) ? $template_hero_elementor_options['template_hero_elementor_remote_token'] : '';
$client_screte_key = !empty( $template_hero_elementor_options['template_hero_elementor_private_key'] ) ? $template_hero_elementor_options['template_hero_elementor_private_key'] : '';
$remote_site_name  = !empty( $template_hero_elementor_options['template_hero_elementor_remote_name'] ) ? $template_hero_elementor_options['template_hero_elementor_remote_name'] : '';
$network_wide = 'no';
$make_live    = 'no';
?>
<div id="template-hero-elementor-general-options" class="card">

	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="template_hero_elementor_admin_settings">
		<?php wp_nonce_field( 'template_hero_elementor_admin_settings_action', 'template_hero_elementor_admin_settings_field' ); ?>
		<table class="form-table">
			<tbody>
			<?php if( $make_live == 'on' ) { ?>
				<tr valign="top"  class="th-current-url-row th-table-row" style="width:100%">
					<th scope="row">
						<label for="template_hero_elementor_current_url">
							<?php _e( 'Cloud Template Library Url', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td>
						
						<p class="description"><?php echo htmlspecialchars( get_bloginfo( 'url' ) ); ?></p>
						<p class="description"><?php _e( 'Use this URL on remote wordpress sites.', 'template-hero-elementor'); ?></p>
					</td>
				</tr>
				<?php } else {?>
				<tr valign="top" class="th-remote-url-row th-table-row" style="width:100%">
					<th scope="row">
						<label for="template_hero_elementor_remote_name">
							<?php _e( 'Cloud Library Name', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td>
						<input style="width:300px" type="text" name="template_hero_elementor_remote_name" id="template_hero_elementor_remote_name" value="<?php echo $remote_site_name; ?>"  />
						<p class="description"><?php _e( 'Enter your cloud template library Name.', 'template-hero-elementor'); ?></p>
					</td>
				</tr>

				<tr valign="top" class="th-remote-url-row th-public-key-row th-table-row" >
					<th scope="row">
						<label for="template_hero_elementor_private_key">
							<?php _e( 'Cloud Library Api Private Key', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td>
						<input style="width:300px" type="text" name="template_hero_elementor_private_key" id="template_hero_elementor_private_key" value="<?php echo $client_screte_key; ?>"  />
						<p class="description"><?php _e( 'Enter your cloud template library secret key.', 'template-hero-elementor'); ?></p>
					</td>
				</tr>

				<tr valign="top" class="th-remote-url-row th-table-row" style="width:100%">
					<th scope="row">
						<label for="template_hero_elementor_remote_token">
							<?php _e( 'Cloud Library Token', 'template-hero-elementor' ); ?>
						</label>
					</th>
					<td>
						<textarea  style="width:100%" name="template_hero_elementor_remote_token" id="template_hero_elementor_remote_token" ><?php echo $client_token; ?></textarea>
						
						<p class="description"><?php _e( 'Enter your cloud template library token.', 'template-hero-elementor'); ?></p>
					</td> 

				</tr>

				<?php } ?>
				
				<?php do_action( 'template_hero_elementor_settings', $template_hero_elementor_options ); ?>
			</tbody>
			
		</table>
		<p>
			<?php 
			if( $make_live != 'on' ) { 
				submit_button( __( 'Create Library', 'template-hero-elementor' ), 'primary', 'template_hero_elementor_settings_submit' );
			}
			?>
		</p>
	</form>
</div>

<?php
global $wpdb;

$templatehero_libs = $wpdb->prefix . "templatehero_libraries";

$libraries         = $wpdb->get_results( "SELECT id, library_name, library_url, client_id FROM $templatehero_libs" );
$templatehero_libs = $wpdb->prefix . "templatehero_libraries";
$args       = array(
    'public' => true,
);
$post_types     = get_post_types( $args, 'objects' );
$network_wide   = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
if ( $libraries ) :
?>

<div class="template-hero-elementor-api-tokens card">
    

    <table class="table th-api-tokens-table" id="th-api-tokens-table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col"><?php _e( 'Title', 'template-hero-elementor' ); ?></th>
            <th scope="col"><?php _e( 'Url', 'template-hero-elementor' ); ?></th>
            <th scope="col"><?php _e( 'Public Key', 'template-hero-elementor' ); ?></th>
            <th scope="col"><?php _e( 'Private Key', 'template-hero-elementor' ); ?></th>
            <th scope="col"><?php _e( 'Actions', 'template-hero-elementor' ); ?></th>
          
            </tr>
        </thead>

		<tbody>
            <?php foreach( $libraries as $library ) { 
				
				?>
                <tr valign="top" id="th-client-row-<?php echo $library->id; ?>" class="th-remote-url-row th-table-row" style="width:100%"> <th scope="row" class="th-table-row-id"><?php echo $library->id; ?></th><td class="th-table-token-row"><p class="th-table-token"><?php echo $library->library_name; ?></p></td><td class="th-table-token-row"><p class="th-table-token"><?php echo $library->library_url; ?></p></td><td class="th-table-token-row"><p class="th-table-token"><?php echo $library->client_id; ?></p></td><td><p class="th-table-secret"> ***************** <i data-id="<?php echo $library->id; ?>" class="th-get-client-scr-btn dashicons dashicons-visibility" onclick="getClientLibrarySecret(event);"></i></p></td>
				
				<td>
					<button class="btn btn-danger th-delete-client-btn" data-id="<?php echo $library->id; ?>" onclick="thbb_deleteLibrary(event);" >Delete</button> 
				</td></tr>

           <?php } ?>
        </tbody>
    </table>
      
</div>
<?php
endif;