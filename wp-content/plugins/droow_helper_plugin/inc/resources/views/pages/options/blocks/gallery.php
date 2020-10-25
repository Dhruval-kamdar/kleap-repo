<?php
acf_register_block( array(
    'name'        => 'dsnGallery' ,
    'title'       => esc_html__( 'Justified Gallery' , 'droow' ) ,
    'icon'        => 'images-alt2' ,
    'category'    => 'dsn-grid' ,
    'supports'    => array( 'align' => false ) ,
    'description' => 'that allows you to create an high quality justified gallery of images. ' ,
    'keywords'    => array( 'dsn' , 'image' , 'gallery' ) ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        echo droow_shortcode_dsnGallery( $blocks->getAttrBlock( $block ) , $blocks::AcfOption( 'image_size_popup' ) );

    }

) );


