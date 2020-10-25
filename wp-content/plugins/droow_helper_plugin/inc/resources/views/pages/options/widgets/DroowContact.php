<?php

class DroowContact extends \Elementor\Widget_Base
{


    private $dsn_target_block;


    public function __construct( $data = [], $args = null )
    {
        $this->dsn_target_block = acf_get_block_type( 'acf/' . acf_slugify( 'dsn_contact' ) );
        parent::__construct( $data, $args );
    }


    /**
     * Get widget name.
     *
     * Retrieve oEmbed widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return str_replace( 'acf/', '', droow_acf_option_array( $this->dsn_target_block, 'name' ) );
    }

    /**
     * Get widget title.
     *
     * Retrieve oEmbed widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {

        return droow_acf_option_array( $this->dsn_target_block, 'title' );
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the widget keywords.
     *
     * @since 1.0.10
     * @access public
     *
     */
    public function get_keywords()
    {
        return droow_acf_option_array( $this->dsn_target_block, 'keywords', array() );
    }


    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'fas fa-window-maximize';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return [ 'droow_cat' ];
    }

    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {


        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'droow' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $f = new DroowInFields();
        $f->getData( array(
            'dsn_group_key' => 'group_5ebd5511d6592'
        ), $this );


        $this->end_controls_section();

        droow_style_Section( $this );

    }

    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();
//        dd($settings['item']);
//        $html = wp_oembed_get($settings['url']);
//        dd($settings['style_box']);


        $droow = new DroowBlock();
        echo droow_shortcode_contact( $droow->getAttrBlock( null, $settings ), array(
            "phone"               => array(
                'title'               => droow_acf_option_array( $settings, 'phone_title' ),
                'title_animate'       => droow_acf_option_array( $settings, 'phone_title_animate' ),
                'description'         => droow_acf_option_array( $settings, 'phone_description' ),
                'description_animate' => droow_acf_option_array( $settings, 'phone_description_animate' ),
            ),
            "e-mail"              => array(
                'title'               => droow_acf_option_array( $settings, 'e-mail_title' ),
                'title_animate'       => droow_acf_option_array( $settings, 'e-mail_title_animate' ),
                'description'         => droow_acf_option_array( $settings, 'e-mail_description' ),
                'description_animate' => droow_acf_option_array( $settings, 'e-mail_description_animate' ),
            ),
            "address"             => array(
                'title'               => droow_acf_option_array( $settings, 'address_title' ),
                'title_animate'       => droow_acf_option_array( $settings, 'address_title_animate' ),
                'description'         => droow_acf_option_array( $settings, 'address_description' ),
                'description_animate' => droow_acf_option_array( $settings, 'address_description_animate' ),
            ),
            "choose_contact_form" => get_post( droow_acf_option_array( $settings, 'choose_contact_form' ) )
        ) );


    }

}