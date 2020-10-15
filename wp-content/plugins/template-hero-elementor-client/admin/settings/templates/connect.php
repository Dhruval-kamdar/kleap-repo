<?php
/**
* General Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;


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

<div class="template-hero-elementor-api-tokens card">
    

    <table class="table th-api-tokens-table" id="th-api-tokens-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><?php _e( 'Title', 'template-hero-elementor' ); ?></th>
                <th scope="col"><?php _e( 'Url', 'template-hero-elementor' ); ?></th>
                <th scope="col"><?php _e( 'Actions', 'template-hero-elementor' ); ?></th>
                <?php do_action( 'template_hero_elementor_connect_after_actions' ); ?>
          
            </tr>
        </thead>

		<tbody>
            <?php foreach( $libraries as $library ) { 
				if( in_array( $library->id, $active_libraries ) ) {
                    $status = "Deactivate";
                    $class  = 'btn-danger';
                } else {
                    $status = 'Activate';
                    $class  = 'btn-success';
                }
                $id = "th-activate-library". $library->id;
				?>
                <tr valign="top" id="th-client-row-<?php echo $library->id; ?>" class="th-remote-url-row th-table-row" style="width:100%"> 
                    <th scope="row" class="th-table-row-id"><?php echo $library->id; ?></th>
                    <td class="th-table-token-row"><p class="th-table-token"><?php echo $library->library_name; ?></p></td>
                    <td class="th-table-token-row"><p class="th-table-token"><?php echo $library->library_url; ?></p></td>
                    <?php if( $context == 'default' ) {?>
                    <td class="th-table-token-button">
                        <button id=<?php echo $id;?> value = "<?php echo $status ?>"  class="btn th-delete-client-btn <?php echo $class; ?>"  data-id="<?php echo $library->id; ?>" onclick="the_wu_activateLibrary(event);" ><?php echo $status ?> </button> 
                    </td>
                    <?php } else {

                        $content = 'Add custom field here.';
                        $content = apply_filters( 'template_hero_activate_library_button_content', $content, $id, $status, $plan_id, $library->id  );
                        ?>
                        <td class="th-table-token-button">
                            <?php echo $content ;?>
                        </td>
                    <?php } ?>
                    <?php do_action( 'template_hero_elementor_connect_after_button' ); ?>
                </tr>
                <?php do_action( 'template_hero_elementor_connect_after_table_row' ); ?>
           <?php } ?>
        </tbody>
    </table>  
</div>