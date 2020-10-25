<?php
namespace WeDevs\ERP\CRM\Deals;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\CRM\Deals\Helpers;

/**
 * Action and Filter hooks
 *
 * @since 1.0.0
 */
class Hooks {

    use Hooker;

    /**
     * The class constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->filter( 'upload_dir', 'upload_dir' );
        $this->filter( 'wp_get_attachment_image_attributes', 'get_attachment_image_attributes' );
        $this->filter( 'ajax_query_attachments_args', 'media_view_parmission' );
        $this->filter( 'wp_prepare_attachment_for_js', 'prepare_attachment_for_js' );
        $this->filter( 'admin_body_class', 'settings_page_admin_body_class' );
        $this->action( 'add_attachment', 'add_attachment_hash' );
        $this->action( 'erp_deals_change_stage', 'change_deal_people_life_stage' );
        $this->action( 'erp_crm_contact_inbound_email', 'check_emails_related_to_erp_deals', 10, 2 );

    }

    /**
     * Filter the directory for uploads.
     *
     * @since 1.0.0
     *
     * @param array $pathdata
     *
     * @return array
     */
    public function upload_dir( $pathdata ) {
        // Change upload dir for downloadable files
        if ( isset( $_POST['type'] ) && 'erp-deal-attachment' == $_POST['type'] ) {

            if ( empty( $pathdata['subdir'] ) ) {
                $pathdata['path']   = $pathdata['path'] . '/erp-deals-uploads';
                $pathdata['url']    = $pathdata['url']. '/erp-deals-uploads';
                $pathdata['subdir'] = '/erp-deals-uploads';
            } else {
                $new_subdir = '/erp-deals-uploads' . $pathdata['subdir'];

                $pathdata['path']   = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['path'] );
                $pathdata['url']    = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['url'] );
                $pathdata['subdir'] = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['subdir'] );
            }
        }

        return $pathdata;
    }


    /**
     * Get attachment image attributes.
     *
     * @since 1.0.0
     *
     * @param array $attr
     *
     * @return array
     */
    public function get_attachment_image_attributes( $attr ) {
        if ( strstr( $attr['src'], 'erp-deals-uploads/' ) ) {
            $attr['src'] = $this->placeholder_img_src();
        }

        return $attr;
    }

    /**
     * Filter media to decide which user should see which uploads
     *
     * CRM Manger can see their own and all agents' uploads
     * Agents can only see their own uploads.
     * See @wp_ajax_query_attachments method in wp-admin/includes/ajax-actions.php
     *
     * @since 1.0.0
     *
     * @param array $query An array of query variables for WP_Query
     *
     * @return array
     */
    public function media_view_parmission( $query ) {
        $current_user_id = get_current_user_id();

        // for managers
        if ( !current_user_can( 'administrator' ) && erp_crm_is_current_user_manager() ) {
            // get all agent ids
            $agents = get_users( [
                'fields'        => 'ids',
                'role__in'      => erp_crm_get_agent_role(),
                'role__not_in'  => erp_crm_get_manager_role()
            ] );

            // include current manager id
            array_push( $agents, $current_user_id );

            // add param
            $query['author__in'] = $agents;

        // for agents
        } else if ( erp_crm_is_current_user_crm_agent() ) {
            $query['author'] = $current_user_id;
        }

        return $query;
    }

    /**
     * Prepare attachment for JavaScript.
     *
     * Since we are blocking direct acceess to the deal uploads, this hook will filter
     * the url of images from erp-deals-uploads folder with a placeholder image.
     *
     * @since 1.0.0
     *
     * @param array $response
     *
     * @return array
     */
    public function prepare_attachment_for_js( $response ) {
        if ( isset( $response['url'] ) && strstr( $response['url'], 'erp-deals-uploads/' ) ) {

            // downloadable link
            $hash = get_post_meta( $response['id'], 'erp-deals-attachment-hash', true );

            if ( !empty( $hash ) ) {
                // http://www.example.com/?download-deal-attachment=HASHKEY
                $response['url'] = site_url('/') . '?download-deal-attachment=' . $hash;
            }

            $response['full']['url'] = $this->placeholder_img_src();
            if ( isset( $response['sizes'] ) ) {
                foreach( $response['sizes'] as $size => $value ) {
                    $response['sizes'][ $size ]['url'] = $this->placeholder_img_src();
                }
            }
        }

        return $response;
    }

    /**
     * Get the placeholder image URL for products etc.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function placeholder_img_src() {
        /**
         * Filter Hook - placeholder image for deal attachments
         *
         * @since 1.0.0
         *
         * @param string $image_url
         */
        return apply_filters( 'erp_deals_placeholder_img_src', WPERP_DEALS_ASSETS . '/images/placeholder.png' );
    }

    /**
     * Add admin page body classes
     *
     * @since 1.0.0
     *
     * @param string $classes
     *
     * @return string
     */
    public function settings_page_admin_body_class( $classes ) {
        global $current_screen;

        $menu = sanitize_title( __( 'ERP Settings', 'erp' ) );
        $erp_settings_pages = "{$menu}_page_erp-settings";
        $current_page_id = $current_screen->id;

        if (
            ( $current_screen !== $erp_settings_pages ) &&
            isset( $_GET['tab'] ) &&
            ( 'erp-crm' === $_GET['tab'] ) &&
            isset( $_GET['section'] ) &&
            ( 'erp_deals' === $_GET['section'] )
        ) {
            $classes .= ' ' . 'erp-deals-admin-settings-page';
        }

        return $classes;
    }

    /**
     * Add a sha1 hash id to newly uploaded attachment
     *
     * @since 1.0.0
     *
     * @param int $post_id Attachment ID
     *
     * @return void
     */
    public function add_attachment_hash( $post_id ) {
        if ( isset( $_POST['type'] ) && 'erp-deal-attachment' === $_POST['type'] && !empty( $_POST['deal_id'] ) ) {
            $file_path = get_post_meta( $post_id, '_wp_attached_file', true );
            update_post_meta( $post_id, 'erp-deals-attachment-hash', sha1( $file_path ) );
        }
    }


    /**
     * Change deal contact and company life stages
     *
     * @since 1.0.0
     *
     * @param object  $deal Eloquent Deal model with new data
     * @param array   $in   Eloquent StageHistory model
     * @param array   $out  Eloquent StageHistory model
     *
     * @return void
     */
    public function change_deal_people_life_stage( $deal ) {
        $pipeline_stage = $deal->pipeline_stage;

        if ( empty( $pipeline_stage->life_stage ) ) {
            return;
        }

        if ( !empty( $deal->contact_id ) ) {
            erp_crm_update_life_stage( $deal->contact_id, $pipeline_stage->life_stage );
        }

        if ( !empty( $deal->company_id ) ) {
            erp_crm_update_life_stage( $deal->company_id, $pipeline_stage->life_stage );
        }
    }

    /**
     * Check deal related email in inbound emails
     *
     * @since 1.0.0
     *
     * @param array $inbound_email
     * @param array $customer_feed_data
     *
     * @return void
     */
    public function check_emails_related_to_erp_deals( $inbound_email, $customer_feed_data ) {
        if ( empty( $inbound_email['hash'] ) ) {
            return;
        }

        $parent_email = \WeDevs\ERP\CRM\Deals\Models\Email::where( 'hash', $inbound_email['hash'] )->first();

        if ( !empty( $parent_email ) ) {
            $new_email = new \WeDevs\ERP\CRM\Deals\Models\Email;
            $new_email->deal_id     = $parent_email->deal_id;
            $new_email->cust_act_id = $customer_feed_data['id'];
            $new_email->parent_id   = $parent_email->id;
            $new_email->save();
        }
    }
}

new Hooks();
