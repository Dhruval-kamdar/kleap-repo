<?php


class DroowControl
{

    function getArrayData( $options, $id, $default = false )
    {
        return droow_acf_option_array( $options, $id, $default );
    }


    function addText( $control, $args )
    {

        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => $this->getArrayData( $args, 'placeholder', '' ),
                'default'     => $this->getArrayData( $args, 'default_value', '' ),
                'conditions'  => $this->getConditions( $args ),
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )

            ] );
    }

    function addNumber( $control, $args )
    {


        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'placeholder' => $this->getArrayData( $args, 'placeholder', '' ),
                'default'     => $this->getArrayData( $args, 'default_value', '' ),
                'conditions'  => $this->getConditions( $args ),
                'min'         => $this->getArrayData( $args, 'min', '' ),
                'max'         => $this->getArrayData( $args, 'max', '' ),
                'step'        => $this->getArrayData( $args, 'step', '' ),
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )
            ] );
    }

    function addTrueFalse( $control, $args )
    {

        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'        => $this->getArrayData( $args, 'label' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'placeholder'  => $this->getArrayData( $args, 'placeholder', '' ),
                'default'      => $this->getArrayData( $args, 'default_value' ),
                'label_on'     => $this->getArrayData( $args, 'ui_on_text', 'yes' ),
                'label_off'    => $this->getArrayData( $args, 'ui_off_text', 'no' ),
                'return_value' => '1',
                'conditions'   => $this->getConditions( $args ),
                'label_block'  => true,
                'description'  => $this->getArrayData( $args, 'instructions', '' )

            ] );
    }

    function addURL( $control, $args )
    {

        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => $this->getArrayData( $args, 'placeholder', '' ),
                'default'     => $this->getArrayData( $args, 'default_value', '' ),
                'input_type'  => 'url',
                'label_block' => true,
                'conditions'  => $this->getConditions( $args ),
                'description' => $this->getArrayData( $args, 'instructions', '' )

            ] );
    }

    function addTextarea( $control, $args )
    {

        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => $this->getArrayData( $args, 'placeholder', '' ),
                'default'     => $this->getArrayData( $args, 'default_value', '' ),
                'label_block' => true,
                'conditions'  => $this->getConditions( $args ),
                'description' => $this->getArrayData( $args, 'instructions', '' )

            ] );
    }


    function addImage( $control, $args )
    {
        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'conditions'  => $this->getConditions( $args ),
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                    'id'  => ''
                ],
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )

            ] );
    }

    function addGallery( $control, $args )
    {
        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::GALLERY,
                'conditions'  => $this->getConditions( $args ),
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )
            ] );
    }

    function addGroup( $control, $args )
    {

        $inFiled = new DroowInFields();

        if ( get_class( $control ) === 'Elementor\Repeater' ) {
            $inFiled->getData( array(
                'dsn_group_key' => $args[ 'key' ]
            ), $control );
        } else {
            $rep = new \Elementor\Repeater();
            $control->add_control(
                'g-' . $this->getArrayData( $args, 'name' ),
                [
                    'label'     => $this->getArrayData( $args, 'label' ),
                    'type'      => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $control->add_control(
                'd-' . $this->getArrayData( $args, 'name' ),
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );
            
            $inFiled->getData( array(
                'dsn_group_key' => $args[ 'key' ]
            ), $rep );


            foreach ( $rep->get_controls() as $key => $value ):

                $name = $this->getArrayData( $args, 'name' ) . '_' . $key;
                unset( $value[ 'name' ] );
                unset( $value[ 'tab' ] );
                $control->add_control( $name, $value );

            endforeach;
        }


    }

    function addButtonGroup( $control, $args )
    {

        $setOp = array();

        foreach ( $this->getArrayData( $args, 'choices', '' ) as $key => $value ):

            if ( strpos( $value, 'img' ) !== false ) {
                $setOp[ $key ] = $key;
            } else {
                $setOp[ $key ] = $value;

            }
        endforeach;

        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => $this->getArrayData( $args, 'allow_null' ) ? \Elementor\Controls_Manager::SELECT2 : \Elementor\Controls_Manager::SELECT,
                'default'     => $this->getArrayData( $args, 'default_value', '' ),
                'options'     => $setOp,
                'conditions'  => $this->getConditions( $args ),
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )
            ] );
    }

    function addPostObject( $control, $args )
    {

        $items = array();

        $post_type = get_posts( array( 'posts_per_page' => -1, 'post_type' => droow_acf_option_array( $args, 'post_type', array() ) ) );
        if ( count( $post_type ) ) {
            foreach ( $post_type as $po ):
                $items[ $po->ID ] = $po->post_title;

            endforeach;
        }
//        dd($items);
        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'default'     => $this->getArrayData( $args, 'default_value', '' ),
                'options'     => $items,
                'conditions'  => $this->getConditions( $args ),
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )
            ] );
    }


    function addRepeater( $control, $args )
    {
        $repeater = new \Elementor\Repeater();
        $inFiled = new DroowInFields();
        $inFiled->getData( array(
            'dsn_group_key' => $args[ 'key' ]
        ), $repeater );


        $control->add_control(
            $this->getArrayData( $args, 'name' ),
            [
                'label'       => $this->getArrayData( $args, 'label' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'conditions'  => $this->getConditions( $args ),
                'label_block' => true,
                'description' => $this->getArrayData( $args, 'instructions', '' )
//                'title_field' => $this->getCollapsed($args , $repeater),

            ] );

    }


    function getCollapsed( $args )
    {
        if ( in_array( acf_get_local_field( $args[ 'key' ] )[ 'type' ], array( 'clone', 'repeater', 'group' ) ) )
            return '';


        dd( acf_get_local_field( $args[ 'key' ] ) );


        return '{{{ title }}}';
    }

    function getConditions( $args )
    {

        $con = $this->getArrayData( $args, 'conditional_logic' );
        if ( !$con ) return false;

        $p_array = array();
        foreach ( $con as $index1 ) {
            $array = [];
            foreach ( $index1 as $index2 ) {
                array_push( $array, array(
                    'name'     => $this->getArrayData( acf_get_local_field( $this->getArrayData( $index2, 'field' ) ), 'name' ),
                    'operator' => str_replace( 'empty', '=', $this->getArrayData( $index2, 'operator' ) ),
                    'value'    => $this->getArrayData( $index2, 'value', '' )
                ) );
            }
            array_push( $p_array, array(
                'terms' => $array
            ) );

        }
        return array(
//            'relation' => 'or',
            'terms' => $p_array
        );

    }


}