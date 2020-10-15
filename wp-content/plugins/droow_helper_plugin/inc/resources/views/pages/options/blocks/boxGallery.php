<?php
acf_register_block( array(
    'name'        => 'dsnBoxGallery' ,
    'title'       => esc_html__( 'Box Vertical Gallery' , 'droow' ) ,
    'icon'        => DroowSVG::VerticalImage() ,
    'category'    => 'dsn-grid' ,
    'supports'    => array( 'align' => false ) ,
    'description' => 'that allows 3 box with Image. ' ,
    'keywords'    => array( 'dsn' , 'image' , 'gallery' ) ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        echo droow_shortcode_dsnBoxGallery( $blocks->getAttrBlock( $block ) , $blocks::AcfOption( 'image_size_popup' ) );


    }

) );


