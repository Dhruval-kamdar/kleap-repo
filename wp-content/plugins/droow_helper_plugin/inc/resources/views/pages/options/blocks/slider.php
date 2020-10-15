<?php
acf_register_block( array(
    'name'     => 'dsnSlider' ,
    'title'    => esc_html__( 'Slider' , 'droow' ) ,
    'icon'     => DroowSVG::Slider() ,
    'category' => 'dsn-grid' ,
    'supports' => array( 'align' => false ) ,
    'keywords' => array( 'dsn' , 'image' , 'gallery' , 'slider' ) ,
    'example'  => true ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            $blocks->preview( $block );
//        }

        echo droow_shortcode_dsnSlider( $blocks->getAttrBlock( $block ) );

    }

) );


