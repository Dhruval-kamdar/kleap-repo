<?php

acf_register_block( array(
    'name'     => 'parallaxImage' ,
    'title'    => esc_html__( 'Parallax Image' , 'droow' ) ,
    'icon'     => DroowSVG::Image() ,
    'category' => 'dsn-grid' ,
    'supports' => array( 'align' => false ) ,
    'keywords' => array( 'dsn' , 'image' , 'gallery' , 'parallax' ) ,
    'example'  => true ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            $blocks->preview( $block );
//        }
        echo droow_shortcode_parallaxImage( $blocks->getAttrBlock( $block ) , $content );

    }
) );



