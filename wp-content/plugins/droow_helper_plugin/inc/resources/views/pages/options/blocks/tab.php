<?php
acf_register_block( array(
    'name'     => 'dsn_tab' ,
    'title'    => esc_html__( 'Tab' , 'miao' ) ,
    'icon'     => 'welcome-widgets-menus' ,
    'category' => 'dsn-grid' ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $title    = miao_acf_option( 'dsn-title' , 'What We Do' );
        $column   = miao_acf_option( 'dsn-column' , '10' );
        $contents = miao_acf_option( 'dsn-content' , array() );


//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }


        echo miao_view( 'shortcode/tab' , array( 'attr'    => array( 'title' => $title , 'dsn-column' => $column , ) ,
                                                 'content' => $contents ) );


    }

) );


acf_add_local_field_group( array(
    'key'                   => 'group_5d7c112d7b0f4' ,
    'title'                 => 'Block Tab' ,
    'fields'                => array(
        array(
            'key'               => 'field_5d7c112d898d4' ,
            'label'             => 'Title' ,
            'name'              => 'dsn-title' ,
            'type'              => 'text' ,
            'instructions'      => '' ,
            'required'          => 0 ,
            'conditional_logic' => 0 ,
            'wrapper'           => array(
                'width' => '' ,
                'class' => '' ,
                'id'    => '' ,
            ) ,
            'default_value'     => 'What We Do' ,
            'placeholder'       => '' ,
            'prepend'           => '' ,
            'append'            => '' ,
            'maxlength'         => '' ,
        ) ,
        array(
            'key'               => 'field_5d7c1681a40e8' ,
            'label'             => 'Column' ,
            'name'              => 'dsn-column' ,
            'type'              => 'number' ,
            'instructions'      => '' ,
            'required'          => 0 ,
            'conditional_logic' => 0 ,
            'wrapper'           => array(
                'width' => '' ,
                'class' => '' ,
                'id'    => '' ,
            ) ,
            'default_value'     => 10 ,
            'placeholder'       => '' ,
            'prepend'           => '' ,
            'append'            => '' ,
            'min'               => 10 ,
            'max'               => 12 ,
            'step'              => 1 ,
        ) ,
        array(
            'key'               => 'field_5d7c114949ada' ,
            'label'             => 'Content' ,
            'name'              => 'dsn-content' ,
            'type'              => 'repeater' ,
            'instructions'      => '' ,
            'required'          => 0 ,
            'conditional_logic' => 0 ,
            'wrapper'           => array(
                'width' => '' ,
                'class' => '' ,
                'id'    => '' ,
            ) ,
            'collapsed'         => 'field_5d7c116249adb' ,
            'min'               => 0 ,
            'max'               => 0 ,
            'layout'            => 'row' ,
            'button_label'      => 'Add Tab' ,
            'sub_fields'        => array(
                array(
                    'key'               => 'field_5d7c116249adb' ,
                    'label'             => 'Title' ,
                    'name'              => 'title' ,
                    'type'              => 'text' ,
                    'instructions'      => '' ,
                    'required'          => 0 ,
                    'conditional_logic' => 0 ,
                    'wrapper'           => array(
                        'width' => '' ,
                        'class' => '' ,
                        'id'    => '' ,
                    ) ,
                    'default_value'     => '' ,
                    'placeholder'       => '' ,
                    'prepend'           => '' ,
                    'append'            => '' ,
                    'maxlength'         => '' ,
                ) ,
                array(
                    'key'               => 'field_5d7c117849adc' ,
                    'label'             => 'Description' ,
                    'name'              => 'description' ,
                    'type'              => 'textarea' ,
                    'instructions'      => '' ,
                    'required'          => 0 ,
                    'conditional_logic' => 0 ,
                    'wrapper'           => array(
                        'width' => '' ,
                        'class' => '' ,
                        'id'    => '' ,
                    ) ,
                    'default_value'     => '' ,
                    'placeholder'       => '' ,
                    'maxlength'         => '' ,
                    'rows'              => '' ,
                    'new_lines'         => '' ,
                ) ,
                array(
                    'key'               => 'field_5d7c11a149add' ,
                    'label'             => 'Feature list' ,
                    'name'              => 'list' ,
                    'type'              => 'repeater' ,
                    'instructions'      => '' ,
                    'required'          => 0 ,
                    'conditional_logic' => 0 ,
                    'wrapper'           => array(
                        'width' => '' ,
                        'class' => '' ,
                        'id'    => '' ,
                    ) ,
                    'collapsed'         => '' ,
                    'min'               => 0 ,
                    'max'               => 0 ,
                    'layout'            => 'table' ,
                    'button_label'      => '' ,
                    'sub_fields'        => array(
                        array(
                            'key'               => 'field_5d7c11d149ade' ,
                            'label'             => 'Item' ,
                            'name'              => 'item' ,
                            'type'              => 'text' ,
                            'instructions'      => '' ,
                            'required'          => 0 ,
                            'conditional_logic' => 0 ,
                            'wrapper'           => array(
                                'width' => '' ,
                                'class' => '' ,
                                'id'    => '' ,
                            ) ,
                            'default_value'     => '' ,
                            'placeholder'       => '' ,
                            'prepend'           => '' ,
                            'append'            => '' ,
                            'maxlength'         => '' ,
                        ) ,
                    ) ,
                ) ,
            ) ,
        ) ,
    ) ,
    'location'              => array(
        array(
            array(
                'param'    => 'block' ,
                'operator' => '==' ,
                'value'    => 'acf/dsn-tab' ,
            ) ,
        ) ,
    ) ,
    'menu_order'            => 0 ,
    'position'              => 'normal' ,
    'style'                 => 'default' ,
    'label_placement'       => 'top' ,
    'instruction_placement' => 'label' ,
    'hide_on_screen'        => '' ,
    'active'                => true ,
    'description'           => '' ,
) );



