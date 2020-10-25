<?php 
namespace ElementorExtensions\Classes;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Classes\EE_MB_Wordpress_Metabox;
use ElementorExtensions\Classes\Utils;

class EE_MB_Event_Metabox{

    public $prefix = '';

    private static $_instance;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){
        $this->prefix = '_ee_mb_';
        add_action('admin_init',array($this,'eeMbAddEventMetaBox'));
    }

    public function eeMbAddEventMetaBox(){

        $metabox = new EE_MB_Wordpress_Metabox( 'Event`s Data', 'ee_mb_event_slider', array( 'ee_mb_event_slider'),'advanced','high');

        $metabox->add_field(
            array(
                'type'  => 'section',
                'title' => 'Time & Date',
                'name'  => 'event_datetime_section',
            )
        ); 

        $metabox->add_field(
            array(
                'name' => $this->prefix.'start_date',
                'title' => __('Start Date'),
                'type' => 'date',
                'default' => date('Y-m-d'),
            )
        );

        $metabox->add_field(
            array(
                'name' => $this->prefix.'start_time',
                'title' => __('Start Time'),
                'type' => 'time',
                'default' => '8:00am',
            )
        );

        $metabox->add_field(
            array(
                'name' => $this->prefix.'end_time',
                'title' => __('End Time'),
                'type' => 'time',
                'default' => '5:00pm',
            )
        );

        $metabox->add_field(
            array(
                'name' => $this->prefix.'end_date',
                'title' => __('End Date'),
                'type' => 'date',
                'default' => date('Y-m-d')
            )
        );

        $metabox->add_field(
            array(
                'name' => $this->prefix.'all_day_event',
                'title' => __('All Day Event'),
                'type' => 'checkbox'
            )
        );


        $metabox->add_field(
            array(
                'type'  => 'section',
                'name'  => 'event_website_section',
                'title' => 'Event Website',
            )
        ); 

        $metabox->add_field(
            array(
                'name' => $this->prefix.'event_website',
                'title' => __('Event Website'),
                'desc' => __('Insert / Edit the event website URL from here e.g. http://www.example.com')
            )
        );


        $metabox->add_field(
            array(
                'type'  => 'section',
                'name'  => 'event_location',
                'title' => 'Event Location',
            )
        );

        $metabox->add_field(
            array(
                'type' => 'textarea',
                'name' => $this->prefix.'event_location',
                'title' => __('Location'),
                'desc' => __('Insert / Edit the event address or location from here')
            )
        );

        $metabox->add_field(
            array(
                'type'  => 'section',
                'name'  => 'event_page_override',
                'title' => 'Event Page Override',
            )
        );

        $metabox->add_field(
            array(
                'name' => $this->prefix.'external_url_chk',
                'title' => __('External URL?'),
                'type' => 'checkbox'
            )
        );

        $all_pages = Utils::get_pages();
        $metabox->add_field(
            array(
                'name' => $this->prefix.'event_page_link',
                'title' => __('Page Link'),
                'desc' => __('Select page name to add page link, External URL will replace this'),
                'type' => 'select',
                'options' => $all_pages,
            )
        );

        $metabox->add_field(
            array(
                'name' => $this->prefix.'event_external_link',
                'title' => __('External URL'),
                'desc' => __('Insert / Edit the event external URL from here e.g. http://www.example.com')
            )
        );
    }
}

EE_MB_Event_Metabox::instance();
