<?php

acf_register_block( array(
    'name'     => 'embeddedVideo' ,
    'title'    => esc_html__( 'Parallax Embedded Video' , 'droow' ) ,
    'icon'     => DroowSVG::Video() ,
    'category' => 'dsn-grid' ,
    'supports' => array( 'align' => true ) ,
    'keywords' => array( 'dsn' , 'paragraph' , 'parallax' , 'video' ) ,
    'example'  => true ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            $blocks->preview( $block );
//        }

        echo droow_shortcode_embeddedVideo( $blocks->getAttrBlock( $block ) , $blocks::AcfOption( 'url' ) );
    }

) );
