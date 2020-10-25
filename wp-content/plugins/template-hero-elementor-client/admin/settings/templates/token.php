<?php
/**
* Api Tokens
*/

if ( ! defined( 'ABSPATH' ) ) exit;
use TemplateHero\Plugin_Client\Api\Tokens as Admin_Info;
$template_hero_elementor_options = get_option( 'template_hero_elementor_options', array() );
$make_live         = !empty( $template_hero_elementor_options['template_hero_elementor_make_library'] ) ? $template_hero_elementor_options['template_hero_elementor_make_library'] : 'no';
$id_token          = !empty( get_transient( "token_".get_current_user_id() ) ) ? get_transient( "token_".get_current_user_id() ) : '';
$network_wide      = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
if ( ! $id_token ) {
    $admin_ids = Admin_Info::get_admin_user_ids();
    foreach( $admin_ids as $id  ) {
        $id_token = get_transient( "token_".$id  );
        if( $id_token ) {
            break;
        }
    }
}

if ( $network_wide == 'on' ) {
    switch_to_blog( 1 );
    $id_token = get_transient( "token_network_wide" );
    restore_current_blog();
}
global $wpdb;

$templatehero_libs = $wpdb->prefix . "templatehero_libraries";

$libraries         = $wpdb->get_results( "SELECT id, library_name, library_url, client_id FROM $templatehero_libs" );
$templatehero_libs = $wpdb->prefix . "templatehero_libraries";
$args       = array(
    'public' => true,
);

$network_wide   = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
if ( $network_wide == 'on' ) {
					
    $active_libraries    =  get_site_option( "active_libraries_ids", array() );
} else {
    $active_libraries    =  get_option( "active_libraries_ids", array() );
}


if( isset( $_GET['plan_id'] ) ) {
    $plan_id = $_GET['plan_id'];
} else {
    $plan_id = '';
}
$active_libraries = apply_filters( 'the_activated_libraries', $active_libraries, $plan_id );
$context = 'default';
$context = apply_filters( 'template_hero_elementor_change_activation_context', $context );

?>

<div id="template-hero-api-connect-box" class="card">
    <div class="th-row">
        <div class="th-column">
            <table class="table th-api-tokens-table" id="th-api-tokens-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php _e( 'Title', 'template-hero-elementor' ); ?></th>
                        <th scope="col"><?php _e( 'Token State', 'template-hero-elementor'); ?></th>
                        <th scope="col"><?php _e( 'Token Actions', 'template-hero-elementor'); ?></th>
                        <?php do_action( 'template_hero_elementor_tokens_after_actions' ); ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach( $libraries as $library ) { 
                        $id_token          = !empty( get_transient( "token_".get_current_user_id().$library->id ) ) ? get_transient( "token_".get_current_user_id().$library->id  ) : '';
                        
                        if ( ! $id_token ) {
                            $admin_ids = Admin_Info::get_admin_user_ids();
                            foreach( $admin_ids as $id  ) {
                                $id_token = get_transient( "token_".$id.$library->id  );
                                if( $id_token ) {
                                    break;
                                }
                            }
                        }
                        
                        if ( $network_wide == 'on' ) {
                            switch_to_blog( 1 );
                            $id_token = get_transient( "token_network_wide".$library->id );
                            restore_current_blog();
                        }
                        $id = "th-activate-library". $library->id;
                        ?>
                        <tr valign="top" id="th-client-row-<?php echo $library->id; ?>" class="th-remote-url-row th-table-row" style="width:100%"> 
                            <th scope="row" class="th-table-row-id"><?php echo $library->id; ?></th>
                            <td class="th-table-token-row"><p class="th-table-token"><?php echo $library->library_name; ?></p></td>
                            <?php if( $id_token && $make_live == 'no' ): ?>
                            <td class="th-table-token-row"><p class="th-table-token">Token Created</p></td><td><p class="th-table-secret"><a id="template-hero-api-token-refresh" data-library_id = "<?php echo $library->id; ?>" data-token="<?php echo $id_token; ?>" class="btn btn-primary tokenhero-tokenbox-refresh">Renew</a> 
                            <?php if( $network_wide !== 'on' ) { ?>
                                <a id="template-hero-api-token-remove" data-library_id= "<?php echo $library->id; ?>" class="btn btn-danger tokenhero-tokenbox-refresh">Remove</a></p></td>
                            <?php } elseif( is_multisite() && is_network_admin() ) {?>
                                <a id="template-hero-api-token-remove" data-library_id="<?php echo $library->id; ?>" class="btn btn-danger tokenhero-tokenbox-refresh">Remove</a></p></td>
                            <?php } ?>
                            <?php elseif(!$id_token && $make_live == 'no'): ?>
                            <td class="th-table-token-row"><p class="th-table-token">Save your api key to create a new token.</p></td><td><p class="th-table-secret"><button id="template-hero-api-token-refresh" data-library_id = "<?php echo $library->id; ?>" data-token="<?php echo $id_token; ?>" class="btn btn-primary tokenhero-tokenbox-refresh">Create</button><button id="template-hero-api-token-remove" data-library_id = "<?php echo $library->id; ?>" class="btn btn-danger tokenhero-tokenbox-refresh" disabled>Remove</button></p></td>
                          
                        <?php endif; ?>
                        <?php do_action( 'template_hero_elementor_token_after_token_message' ); ?>
                        </tr>

                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="th-column">
                <img src="<?php echo TEMPLATE_HERO_ELEMENTOR_ASSETS_URL; ?>images/token-security.png" class="tokenhero-tokenbox-img" />
        </div>
    </div>
</div>