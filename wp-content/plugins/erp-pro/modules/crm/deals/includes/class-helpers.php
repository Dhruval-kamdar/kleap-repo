<?php
namespace WeDevs\ERP\CRM\Deals;

/**
 * Helpers class
 *
 * Class contains miscellaneous helper methods
 *
 * @since 1.0.0
 */
class Helpers {

    /**
     * Build admin url
     *
     * @since 1.0.0
     *
     * @param array  $queries
     * @param string $page
     * @param string $base
     *
     * @return string WP Admin url
     */
    public static function admin_url( $queries = [], $page = 'erp-crm', $base = 'admin.php' ) {
        $queries = [ 'page' => $page, 'section' => 'deals' ] + $queries;

        $query_string = http_build_query( $queries );

        return admin_url( $base . '?' . $query_string );
    }

    /**
     * Date format for the jQuery Datepicker
     *
     * @since 1.0.0
     *
     * @return string Example: Y-m-d will change to yy-mm-dd
     */
    public static function js_date_format() {
        $format = erp_get_option( 'date_format', 'erp_settings_general', 'd-m-Y' );

        $js_format = str_replace( [ 'Y', 'm', 'd' ], [ 'yy', 'mm', 'dd' ], $format );

        return $js_format;
    }

    /**
     * Search contact and companies
     *
     * @since 1.0.0
     *
     * @param string $s    Search query string
     * @param string $type People type: contact or company
     *
     * @return array
     */
    public static function search_people( $s, $type = 'contact' ) {
        $args = [
            'type'              => $type,
            'number'            => '-1',
        ];

        // build filter query
        $s = trim( $s );

        $words = explode( ' ', $s );

        if ( count( $words ) > 1 ) {
            $last_name  = array_pop( $words );
            $first_name = str_replace( ' ' . $last_name, '', $s );

            $query = "first_name[]=~{$first_name}&last_name[]=~{$last_name}";

        } else {
            $query = "first_name[]=~{$s}&or&last_name[]=~{$s}&or&email[]=~{$s}";
        }

        if ( 'contact' === $type ) {
            $args['erpadvancefilter']  = $query;

        } else if ( 'company' === $type ) {
            $args['erpadvancefilter']  = "company[]=~{$s}&or&email[]=~{$s}";
        }

        $erp_people = erp_get_peoples( $args );

        $people = [];
        $ids = [];

        foreach ( $erp_people as $item ) {

            switch ( $type ) {
                case 'company':
                    $name = $item->company;
                    break;

                default:
                    $name = implode( ' ', [ $item->first_name, $item->last_name ] );
                    break;
            }

            $people[] = [
                'id' => $item->id,
                'name' => $name
            ];

            $ids[] = $item->id;
        }

        // company associated with contact_id
        if ( 'contact' === $type && !empty( $ids ) ) {
            global $wpdb;

            $ids = implode( ', ', $ids );

            $sql  = "select cci.customer_id as id, cci.company_id, pep.company";
            $sql .= " from {$wpdb->prefix}erp_crm_customer_companies as cci";
            $sql .= " left join {$wpdb->prefix}erp_peoples as pep on cci.company_id = pep.id";
            $sql .= " where customer_id in ( {$ids} )";

            $results = $wpdb->get_results( $sql, OBJECT_K );

            foreach ( $people as $i => $item ) {
                if ( array_key_exists( $item['id'], $results ) ) {
                    $erp_people = $results[ $item['id'] ];

                    $people[$i]['company'] = $erp_people->company;
                    $people[$i]['company_id'] = $erp_people->company_id;
                } else {
                    $people[$i]['company'] = null;
                    $people[$i]['company_id'] = null;
                }
            }

        }

        return $people;
    }

    /**
     * Helper method to get informations of a people
     *
     * @since 1.0.0
     *
     * @param int     $people_id
     * @param string  $type
     *
     * @return array|boolean Contact infomations or null
     */
    public static function get_people_by_id( $people_id, $type = 'contact' ) {
        if ( empty( $people_id ) ) {
            return null;
        }

        $people = new \WeDevs\ERP\CRM\Contact( $people_id, $type );

        if ( $people->id ) {
            $details = $people->to_array();

            $details['country_name'] = $people->get_country();
            $details['state_name'] = $people->get_state();

            return $details;
        } else {
            return null;
        }
    }

    /**
     * Helper method to check if a people is exist with certain type
     *
     * @since 1.0.0
     *
     * @param int    $people_id
     * @param string $type
     *
     * @return boolean
     */
    public static function is_people_exists( $people_id, $type = 'contact' ) {
        $people = new \WeDevs\ERP\CRM\Contact( $people_id, $type );

        if ( $people->id ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Wrapper method to check if a contact is exist or not
     *
     * @since 1.0.0
     *
     * @param int $contact_id
     *
     * @return boolean
     */
    public static function is_contact_exists( $contact_id ) {
        return self::is_people_exists( $contact_id, 'contact' );
    }

    /**
     * Wrapper method to check if a company is exist or not
     *
     * @since 1.0.0
     *
     * @param int $company_id
     *
     * @return boolean
     */
    public static function is_company_exists( $company_id ) {
        return self::is_people_exists( $company_id, 'company' );
    }

    /**
     * Get all CRM Agents' ids and display names
     *
     * By default, CRM Managers are included
     *
     * @param array   $args
     * @param boolean $include_managers
     * @param boolean $new
     *
     * @return array
     */
    public static function get_crm_agents( $args = [], $include_managers = true, $extra_info = false ) {
        $defaults = [
            'fields'    => [ 'ID', 'display_name' ],
            'order'     => 'ASC',
            'orderby'   => 'ID'
        ];

        if ( $include_managers ) {
            $defaults['role__in'] = [ erp_crm_get_agent_role(), erp_crm_get_manager_role() ];

        } else {
            $defaults['role'] = [ erp_crm_get_agent_role() ];
        }

        $args = wp_parse_args( $args, $defaults );

        $agents = get_users( $args );

        if ( !$extra_info ) {
            return $agents;
        }

        $crm_agents = [];
        foreach ( $agents as $i => $agent ) {
            $crm_agents[] = [
                'id'        => $agent->ID,
                'name'      => $agent->display_name,
                'avatar'    => get_avatar_url( $agent->ID, [ 'size' => 48 ] ),
                'link'      => add_query_arg( 'user_id', $agent->ID, self_admin_url( 'user-edit.php' ) )
            ];
        }

        return $crm_agents;
    }

    /**
     * Get lost reasons from DB
     *
     * @since 1.0.0
     *
     * @return object Eloquent LostReason model
     */
    public static function get_lost_reasons() {
        return \WeDevs\ERP\CRM\Deals\Models\LostReason::orderBy( 'id', 'asc' )->get();
    }

    /**
     * Get the lost reason from DB
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return object Eloquent LostReason model
     */
    public static function get_lost_reason( $id ) {
        return \WeDevs\ERP\CRM\Deals\Models\LostReason::find( $id );
    }

    /**
     * Get pipelines id and titles
     *
     * @since 1.0.0
     *
     * @return object Eloquent Pipeline model
     */
    public static function get_pipelines() {
        return \WeDevs\ERP\CRM\Deals\Models\Pipeline::orderBy( 'id', 'asc' )->get();
    }

    /**
     * Get pipelines with their stages
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function get_pipelines_with_stages() {
        $pipelines = [];

        $all_pipelines = \WeDevs\ERP\CRM\Deals\Models\Pipeline::orderBy( 'id', 'asc' )->get();
        foreach ( $all_pipelines as $pipeline ) {
            $stages = $pipeline->stages()->select( [ 'id', 'title' ] )->orderBy( 'order', 'asc' )->get();

            $pipelines[] = [
                'id'        => $pipeline->id,
                'title'     => $pipeline->title,
                'stages'    => $stages
            ];
        }

        return $pipelines;
    }

    /**
     * List of CRM Agents and current user id
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function get_crm_agents_with_current_user() {
        global $current_user;

        $available_agents = self::get_crm_agents( [ 'exclude' => [ $current_user->ID ] ] );

        // add current user/crm manager at the top of the list
        $user = [
            'ID'            => $current_user->ID,
            'display_name'  => $current_user->data->display_name
        ];

        $user = (object) $user;
        array_unshift( $available_agents, $user );


        $crm_agents = [];

        foreach ( $available_agents as $agent ) {
            $crm_agents[] = [
                'id'        => $agent->ID,
                'name'      => $agent->display_name,
                'avatar'    => get_avatar_url( $agent->ID, [ 'size' => 48 ] ),
                'link'      => add_query_arg( 'user_id', $agent->ID, self_admin_url( 'user-edit.php' ) )
            ];
        }

        return [
            'current_user_id'   => $current_user->ID,
            'crm_agents'        => $crm_agents
        ];
    }

    /**
     * ERP Company Address and logo
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function get_company_details() {
        $full_address = '';
        $logo = '';

        $company = new \WeDevs\ERP\Company();

        $address = array_filter( $company->address );

        if ( !empty( $address['country'] ) && '-1' === $address['country'] ) {
            unset( $address['country'] );
        }

        if ( !empty( $address['state'] ) && '-1' === $address['state'] ) {
            unset( $address['state'] );
        }

        if ( !empty( $address ) && !empty( $address['country'] ) ) {
            $erp_countries  = \WeDevs\ERP\Countries::instance();
            $all_countries  = $erp_countries->get_countries();

            $country = $all_countries[ $address['country'] ];

            if ( !empty( $address['state'] ) ) {
                $all_states = array_filter( $erp_countries->states );

                if ( !empty( $all_states[ $address['country'] ][ $address['state'] ] ) ) {
                    $address['state'] = $all_states[ $address['country'] ][ $address['state'] ];
                }
            }

            $address['country'] = $country;
        }

        $logo = $company->get_logo();

        if ( $logo && (string) $company->website ) {
            $logo = '<a href="' . $company->website . '">' . $logo . '</a>';
        }

        return [
            'logo'      => $logo,
            'name'      => $company->name,
            'address'   => $address,
            'phone'     => $company->phone,
            'fax'       => $company->fax,
            'mobile'    => $company->mobile,
            'website'   => $company->website,
            'currency'  => $company->currency,
        ];
    }

    /**
     * Get activity types
     *
     * @since 1.0.0
     *
     * @return object Eloquent Collection object ActivityType models
     */
    public static function get_activity_types() {
        return \WeDevs\ERP\CRM\Deals\Models\ActivityType::orderBy( 'order', 'asc' )->get();
    }

    /**
     * Calculate the duration in hour:min format
     *
     * @since 1.0.0
     *
     * @param string $start_time
     * @param string $end_time
     *
     * @return string Interval time in hour:min format
     */
    public static function calculate_duration( $start_time, $end_time ) {
        $datetime1 = new \DateTime( $start_time );
        $datetime2 = new \DateTime( $end_time );
        $duration = $datetime1->diff($datetime2);
        return $duration->format( '%Hhr %Imin' );
    }

    /**
     * Deal primary contacts
     *
     * @since 1.0.0
     *
     * @param int $deal_id
     *
     * @return array
     */
    public static function get_deal_primary_contacts( $deal_id ) {
        $deal = deals()->get_deal( $deal_id );
        $contact = self::get_people_by_id( $deal->contact_id, 'contact' );
        $company = self::get_people_by_id( $deal->company_id, 'company' );

        return [
            'contact' => $contact, 'company' => $company
        ];
    }

    /**
     * WP Timezone Settings
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function get_wp_timezone() {
        $momentjs_tz_map = [
            'UTC-12'    => 'Etc/GMT+12',
            'UTC-11.5'  => 'Pacific/Niue',
            'UTC-11'    => 'Pacific/Pago_Pago',
            'UTC-10.5'  => 'Pacific/Honolulu',
            'UTC-10'    => 'Pacific/Honolulu',
            'UTC-9.5'   => 'Pacific/Marquesas',
            'UTC-9'     => 'America/Anchorage',
            'UTC-8.5'   => 'Pacific/Pitcairn',
            'UTC-8'     => 'America/Los_Angeles',
            'UTC-7.5'   => 'America/Edmonton',
            'UTC-7'     => 'America/Denver',
            'UTC-6.5'   => 'Pacific/Easter',
            'UTC-6'     => 'America/Chicago',
            'UTC-5.5'   => 'America/Havana',
            'UTC-5'     => 'America/New_York',
            'UTC-4.5'   => 'America/Halifax',
            'UTC-4'     => 'America/Manaus',
            'UTC-3.5'   => 'America/St_Johns',
            'UTC-3'     => 'America/Sao_Paulo',
            'UTC-2.5'   => 'Atlantic/South_Georgia',
            'UTC-2'     => 'Atlantic/South_Georgia',
            'UTC-1.5'   => 'Atlantic/Cape_Verde',
            'UTC-1'     => 'Atlantic/Azores',
            'UTC-0.5'   => 'Atlantic/Reykjavik',
            'UTC+0'     => 'Etc/UTC',
            'UTC'       => 'Etc/UTC',
            'UTC+0.5'   => 'Etc/UTC',
            'UTC+1'     => 'Europe/Madrid',
            'UTC+1.5'   => 'Europe/Belgrade',
            'UTC+2'     => 'Africa/Tripoli',
            'UTC+2.5'   => 'Asia/Amman',
            'UTC+3'     => 'Europe/Moscow',
            'UTC+3.5'   => 'Asia/Tehran',
            'UTC+4'     => 'Europe/Samara',
            'UTC+4.5'   => 'Asia/Kabul',
            'UTC+5'     => 'Asia/Karachi',
            'UTC+5.5'   => 'Asia/Kolkata',
            'UTC+5.75'  => 'Asia/Kathmandu',
            'UTC+6'     => 'Asia/Dhaka',
            'UTC+6.5'   => 'Asia/Rangoon',
            'UTC+7'     => 'Asia/Bangkok',
            'UTC+7.5'   => 'Asia/Bangkok',
            'UTC+8'     => 'Asia/Shanghai',
            'UTC+8.5'   => 'Asia/Pyongyang',
            'UTC+8.75'  => 'Australia/Eucla',
            'UTC+9'     => 'Asia/Tokyo',
            'UTC+9.5'   => 'Australia/Darwin',
            'UTC+10'    => 'Australia/Brisbane',
            'UTC+10.5'  => 'Australia/Adelaide',
            'UTC+11'    => 'Australia/Melbourne',
            'UTC+11.5'  => 'Pacific/Norfolk',
            'UTC+12'    => 'Asia/Anadyr',
            'UTC+12.75' => 'Asia/Anadyr',
            'UTC+13'    => 'Pacific/Fiji',
            'UTC+13.75' => 'Pacific/Chatham',
            'UTC+14'    => 'Pacific/Tongatapu',
        ];

        $current_offset = get_option('gmt_offset');
        $tzstring = get_option('timezone_string');

        // Remove old Etc mappings. Fallback to gmt_offset.
        if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
            $tzstring = '';
        }

        if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
            if ( 0 == $current_offset ) {
                $tzstring = 'UTC+0';
            } elseif ($current_offset < 0) {
                $tzstring = 'UTC' . $current_offset;
            } else {
                $tzstring = 'UTC+' . $current_offset;
            }

        }

        if ( array_key_exists( $tzstring , $momentjs_tz_map ) ) {
            $tzstring = $momentjs_tz_map[ $tzstring ];
        }

        return $tzstring;
    }
}
