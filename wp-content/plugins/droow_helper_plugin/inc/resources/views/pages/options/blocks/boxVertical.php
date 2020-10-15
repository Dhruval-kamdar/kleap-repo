<?php
acf_register_block( array(
    'name'     => 'boxVertical' ,
    'title'    => esc_html__( 'Box Vertical With Image' , 'droow' ) ,
    'icon'     => DroowSVG::BoxVertical() ,
    'category' => 'dsn-grid' ,
    'keywords' => array( 'dsn' , 'image' , 'gallery' , 'introduction' , 'paragraph' ) ,
    'example'  => true ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            $blocks->preview( $block );
//        }


        echo droow_shortcode_boxVertical( $blocks->getAttrBlock( $block ) , $blocks::AcfOption( 'description_blod' ) );

    }

) );

