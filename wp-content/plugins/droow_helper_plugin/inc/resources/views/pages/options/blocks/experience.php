<?php
acf_register_block( array(
    'name'     => 'dsn_experience' ,
    'title'    => esc_html__( 'Experience' , 'droow' ) ,
    'icon'     => DroowSVG::Experience() ,
    'supports' => array( 'align' => false ) ,
    'keywords' => array( 'dsn' , 'paragraph' , 'introduction' , 'experience' ) ,
    'category' => 'dsn-grid' ,


    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        $droow = new DroowBlock();

        echo droow_shortcode_experience( $droow->getAttrBlock( $block ) , $droow::AcfOption( 'item' ) );

    }

) );
