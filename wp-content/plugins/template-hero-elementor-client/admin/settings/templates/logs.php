<?php
/**
* General Options
*/
if ( ! defined( 'ABSPATH' ) ) exit;
use TemplateHero\Plugin_Client\Logs\Template_Hero_Logger as Template_Hero_Logger;
global $wp_filesystem, $wpdb;
$pad_spaces = 45;
$template_hero_elementor_options = get_option( 'template_hero_elementor_log_options', array() );

$logs_list = glob( Template_Hero_Logger::get_logs_folder()."*.log" );
$file      = !empty( $template_hero_elementor_options['template_hero_log_select'] ) ? $template_hero_elementor_options['template_hero_log_select'] : '';
$contents  = $file && file_exists( $file ) ? file_get_contents( $file ) : '--';

?>

<div id="template-hero-elementor-general-options" class="card">
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="template_hero_elementor_admin_logs_settings">
		<?php wp_nonce_field( 'template_hero_elementor_admin_settings_action', 'template_hero_elementor_admin_settings_field' ); ?>
        <?php echo str_pad( __( 'Logs Directory', 'template-hero-elementor') . ":", $pad_spaces ); ?><?php echo is_writable( Template_Hero_Logger::get_logs_folder() ) ? 'Writable' : 'Not Writable' . "\n"; ?>
        <div class="alignright">
         <?php do_action( 'template_hero_elementor_logs_before_select' ); ?>
            <select name="template_hero_log_select" style="">

                <option><?php _e( 'Select a Log File', 'template-hero-elementor'); ?></option>

                <?php foreach( $logs_list as $file_path ) : ?>
                <option value="<?php echo $file_path; ?>" <?php selected( $file == $file_path ); ?>><?php echo $file_path; ?></option>
                <?php endforeach; ?>

            </select>

            <button class="button-primary" id="template_hero_see_log" name="template_hero_see_log" value="see" type="submit"><?php _e( 'See Log File', 'template-hero-elementor'); ?></button>
            <?php do_action( 'template_hero_elementor_logs_after_button' ); ?>
        </div>
            <div class="clear"></div>

        <br>
    </form>

    <textarea  onclick="this.focus();this.select()" readonly="readonly" wrap="off" style="width: 100%; height: 600px; font-family: monospace;"><?php echo $contents; ?></textarea>
    <?php do_action( 'template_hero_elementor_logs_after_textarea' ); ?>
</div>