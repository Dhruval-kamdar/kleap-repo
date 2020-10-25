<?php
acf_register_block( array(
    'name'     => 'dsn_intro' ,
    'title'    => esc_html__( 'Introduction' , 'droow' ) ,
    'icon'     => DroowSVG::Introduction() ,
    'supports' => array( 'align' => true ) ,
    'keywords' => array( 'dsn' , 'paragraph' , 'introduction' ) ,
    'category' => 'dsn-grid' ,
    'example'  => true ,


    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $droow = new DroowBlock();


//        if ( $is_preview ) {
//            $droow->preview( $block );
//        }

        echo droow_shortcode_intro( $droow->getAttrBlock( $block ) , $droow::Details() );

    }

) );
