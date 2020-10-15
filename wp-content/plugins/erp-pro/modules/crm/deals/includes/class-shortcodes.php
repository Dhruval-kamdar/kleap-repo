<?php
namespace WeDevs\ERP\CRM\Deals;

/**
 * Shortcode object
 *
 * @since 1.0.0
 */
class Shortcodes {

    /**
     * Contact custom meta by ERP Field Builder addon
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $contact_custom_fields;

    /**
     * Contact custom meta by ERP Field Builder addon
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $company_custom_fields;

    /**
     * Company details
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $company_details;


    /**
     * Initializes the class
     *
     * Checks for an existing instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
     *
     * @return object Class instance
     */
    public static function instance() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Templates constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        if ( is_plugin_active( 'erp-field-builder/erp-field-builder.php' ) ) {
            $this->contact_custom_fields = get_option( 'erp-contact-fields' );
            $this->company_custom_fields = get_option( 'erp-company-fields' );
        }
    }

    /**
     * Render HTML for the email
     *
     * @since 1.0.0
     *
     * @param int    $campaign_id
     * @param int    $people_id
     * @param string $hash
     *
     * @return string html
     */
    public function render_email( $people, $html ) {
        // render shortcodes
        $html = $this->render_shortcodes( $people, $html );

        // get CSS styles
        ob_start();
        echo file_get_contents( WPERP_DEALS_PATH . '/assets/css/text-editor.css' );
        $css = ob_get_clean();

        // remove backslash from elements like <p style="\color: red\">Text</p>
        $html = preg_replace( '/style=(\\\\)"(.+?)(\\\\)"/', 'style="${2}"', $html );

        // apply CSS styles inline for picky email clients
        $emogrifier = new \WeDevs\ERP\Lib\Emogrifier( $html, $css );
        $html = $emogrifier->emogrify();

        return $html;
    }

    /**
     * The available shortcodes used in email templates
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function shortcodes() {
        $shortcodes = [];

        $shortcodes['user'] = [
            'title' => __( 'Company/Contact', 'erp-pro' ),
            'codes' => [
                'first_name'        => [ 'title' => __( 'Contact First Name', 'erp-pro' ) ],
                'last_name'         => [ 'title' => __( 'Contact Last Name', 'erp-pro' ) ],
                'email'             => [ 'title' => __( 'Email', 'erp-pro' ) ],
                'company'           => [ 'title' => __( 'Company Name', 'erp-pro' ) ],
                'phone'             => [ 'title' => __( 'Phone', 'erp-pro' ) ],
                'mobile'            => [ 'title' => __( 'Mobile', 'erp-pro' ) ],
                'other'             => [ 'title' => __( 'Other', 'erp-pro' ) ],
                'website'           => [ 'title' => __( 'Website', 'erp-pro' ) ],
                'fax'               => [ 'title' => __( 'Fax', 'erp-pro' ) ],
                'notes'             => [ 'title' => __( 'Notes', 'erp-pro' ) ],
                'street_1'          => [ 'title' => __( 'Street 1', 'erp-pro' ) ],
                'street_2'          => [ 'title' => __( 'Street 2', 'erp-pro' ) ],
                'city'              => [ 'title' => __( 'City', 'erp-pro' ) ],
                'state'             => [ 'title' => __( 'State', 'erp-pro' ) ],
                'postal_code'       => [ 'title' => __( 'Postal Code', 'erp-pro' ) ],
                'country'           => [ 'title' => __( 'Country', 'erp-pro' ) ],
                'currency_code'     => [ 'title' => __( 'Currency Code', 'erp-pro' ) ],
                'currency_symbol'   => [ 'title' => __( 'Currency Symbol', 'erp-pro' ) ],
                'currency_name'     => [ 'title' => __( 'Currency Name', 'erp-pro' ) ],
            ]
        ];

        if ( !empty( $this->contact_custom_fields ) ) {
            $shortcodes['meta']['title'] = __( 'Contact Custom Meta', 'erp-pro' );

            foreach ( $this->contact_custom_fields as $field ) {
                $shortcodes['meta']['codes'][ $field['name'] ] = [ 'title' => $field['label'] ];
            }
        }

        if ( !empty( $this->company_custom_fields ) ) {
            $shortcodes['company_meta']['title'] = __( 'Company Custom Meta', 'erp-pro' );

            foreach ( $this->company_custom_fields as $field ) {
                $shortcodes['company_meta']['codes'][ $field['name'] ] = [ 'title' => $field['label'] ];
            }
        }

        $shortcodes['date'] = [
            'title' => __( 'Date', 'erp-pro' ),
            'codes' => [
                'current_date'              => [ 'title' => __( 'Current date', 'erp-pro' ) ],
                'current_day_full_name'     => [ 'title' => __( 'Full name of current day', 'erp-pro' ) ],
                'current_day_short_name'    => [ 'title' => __( 'Short name of current day', 'erp-pro' ) ],
                'current_month_number'      => [ 'title' => __( 'Current Month number', 'erp-pro' ) ],
                'current_month_full_name'   => [ 'title' => __( 'Full name of current month', 'erp-pro' ) ],
                'current_month_short_name'  => [ 'title' => __( 'Short name of current month', 'erp-pro' ) ],
                'year'                      => [ 'title' => __( 'Year', 'erp-pro' ) ],
            ]
        ];

        $this->company_details = Helpers::get_company_details();

        $shortcodes['company'] = [
            'title' => $this->company_details['name'] ? $this->company_details['name'] : __( 'Your Company', 'erp-pro' ),
            'codes' => [
                'logo'      => [ 'title' => __( 'Logo', 'erp-pro' ), 'plain_text' => true, 'text' => $this->company_details['logo'] ],
                'name'      => [ 'title' => __( 'Name', 'erp-pro' ) ],
                'address'   => [ 'title' => __( 'Mailing Address', 'erp-pro' ) ],
                'phone'     => [ 'title' => __( 'Phone', 'erp-pro' ) ],
                'fax'       => [ 'title' => __( 'Fax', 'erp-pro' ) ],
                'mobile'    => [ 'title' => __( 'Mobile', 'erp-pro' ) ],
                'website'   => [ 'title' => __( 'Website', 'erp-pro' ) ],
                'currency'  => [ 'title' => __( 'Currency', 'erp-pro' ) ],
            ]
        ];

        return $shortcodes;
    }

    /**
     * Render the shortcodes present in template HTML
     *
     * @since 1.0.0
     *
     * @param string  $html
     * @param integer $campaign_id
     * @param integer $people_id
     *
     * @return string
     */
    public function render_shortcodes( $people, $html ) {
        foreach ( $this->shortcodes() as $type_name => $shortcode_type ) {
            foreach ( $shortcode_type['codes'] as $shortcode => $code_details ) {
                // skip if the code is plain text type
                if ( !empty( $code_details['plain_text'] ) ) {
                    continue;
                }

                preg_match_all( "/\{($type_name):($shortcode).*?\}/", $html, $matches );

                if ( !empty( $matches[1] ) && !empty( $matches[2] ) && method_exists( $this , "sc_$type_name" ) ) {
                    foreach ( $matches[0] as $i => $match ) {
                        $code_string = $matches[0][$i];

                        $replace_with = call_user_func( [ $this, "sc_$type_name" ], $people, $shortcode, $code_string );
                        $html = str_replace( $code_string , $replace_with, $html );
                    }
                }
            }
        }

        return $html;
    }

    /**
     * User Shortcodes
     *
     * @since 1.0.0
     *
     * @param int    $campaign_id
     * @param object $people
     * @param string $hash
     * @param string $shortcode
     * @param string $code_string
     *
     * @return string
     */
    private function sc_user( $people, $shortcode, $code_string ) {
        $user = '';

        if ( empty( $people->id ) ) {
            switch ( $shortcode ) {

                case 'first_name':
                case 'last_name':

                    if ( preg_match( '/default=\\\"(.*?)\\\"/' , $code_string, $default ) ) {
                        $user = $default[1];
                    }

                    break;
            }

        } else {
            switch ( $shortcode ) {
                case 'first_name':
                case 'last_name':

                    if ( !empty( $people ) ) {
                        $user = $people->$shortcode;
                    } else if ( preg_match( '/default=\\\"(.*?)\\\"/' , $code_string, $default ) ) {
                        $user = $default[1];
                    }

                    break;

                case 'country':
                    $user = erp_get_country_name( $people->country );
                    break;

                case 'state':
                    $user = erp_get_state_name( $people->country, $people->state );
                    break;

                case 'currency_code':
                    $user = $people->currency;
                    break;

                case 'currency_symbol':
                    $user = erp_get_currency_symbol( $people->currency );
                    break;

                case 'currency_name':
                    $user = !empty( $currencies[ $people->currency ] ) ? $currencies[ $people->currency ] : '';
                    break;

                default:
                    $user = $people->$shortcode;
                    break;
            }
        }

        return $user;
    }

    /**
     * Contact Meta
     *
     * Meta Created by ERP Field Builder addon
     *
     * @since 1.0.0
     *
     * @param int    $campaign_id
     * @param object $people
     * @param string $hash
     * @param string $shortcode
     * @param string $code_string
     *
     * @return string
     */
    private function sc_meta( $people, $shortcode, $code_string ) {
        if ( empty( $people->id ) ) {
            return '';
        }

        $field = array_filter( $this->contact_custom_fields, function ( $field ) use ( $shortcode ) {
            return $field['name'] === $shortcode;
        } );

        $field = array_pop( $field );

        $contact_meta = erp_people_get_meta( $people->id, $shortcode, true );

        if ( in_array( $field['type'] , [ 'radio', 'checkbox', 'select' ]) ) {
            $selected = '';

            foreach ( $field['options'] as $option ) {
                if ( $contact_meta === $option['value'] ) {
                    $contact_meta = $option['text'];
                }
            }
        }

        return $contact_meta;
    }

    /**
     * Company Meta
     *
     * Meta Created by ERP Field Builder addon
     *
     * @since 1.0.0
     *
     * @param int    $campaign_id
     * @param object $people
     * @param string $hash
     * @param string $shortcode
     * @param string $code_string
     *
     * @return string
     */
    private function sc_company_meta( $people, $shortcode, $code_string ) {
        if ( empty( $people->id ) ) {
            return '';
        }

        $field = array_filter( $this->company_custom_fields, function ( $field ) use ( $shortcode ) {
            return $field['name'] === $shortcode;
        } );

        $field = array_pop( $field );

        $company_meta = erp_people_get_meta( $people->id, $shortcode, true );

        if ( in_array( $field['type'] , [ 'radio', 'checkbox', 'select' ] ) ) {
            $selected = '';

            foreach ( $field['options'] as $option ) {
                if ( $company_meta === $option['value'] ) {
                    $company_meta = $option['text'];
                    break;
                }
            }
        }

        return $company_meta;
    }

    /**
     * Date Shortcodes
     *
     * @since 1.0.0
     *
     * @param int    $campaign_id
     * @param object $people
     * @param string $hash
     * @param string $shortcode
     * @param string $code_string
     *
     * @return string
     */
    private function sc_date( $people, $shortcode, $code_string ) {
        $date = '';

        switch ( $shortcode ) {
            case 'current_date':
                $date = date( 'd' );
                break;

            case 'current_day_full_name':
                $date = date( 'l' );
                break;

            case 'current_day_short_name':
                $date = date( 'D' );
                break;

            case 'current_month_number':
                $date = date( 'm' );
                break;

            case 'current_month_full_name':
                $date = date( 'F' );
                break;

            case 'current_month_short_name':
                $date = date( 'M' );
                break;

            case 'year':
                $date = date( 'Y' );
                break;

        }

        return $date;
    }

    /**
     * Company Shortcodes
     *
     * @since 1.0.0
     *
     * @param int    $campaign_id
     * @param object $people
     * @param string $hash
     * @param string $shortcode
     * @param string $code_string
     *
     * @return string
     */
    private function sc_company( $people, $shortcode, $code_string ) {
        $company = '';

        switch ( $shortcode ) {
            case 'address':
                $seperator = ',';

                if ( preg_match( '/seperator=\\\"(.*?)\\\"/' , $code_string, $match ) ) {
                    $seperator = htmlspecialchars_decode( $match[1] );
                }

                $company = implode( "{$seperator} " , $this->company_details['address'] );
                break;

            default:
                $company = $this->company_details[ $shortcode ];
                break;
        }

        return $company;
    }
}

/**
 * Class instance
 *
 * @return object
 */
function deal_shortcodes() {
    return Shortcodes::instance();
}
