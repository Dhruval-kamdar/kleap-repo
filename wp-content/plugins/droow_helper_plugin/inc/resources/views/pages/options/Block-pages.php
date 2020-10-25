<?php

add_filter( 'block_categories' , function ( $categories , $post ) {

    return array_merge(
        $categories ,
        array(
            array(
                'slug'  => 'dsn-grid' ,
                'title' => __( 'Design Grid' , 'droow' ) ,
                'icon'  => 'wordpress' ,
            ) ,
        )
    );
} , 10 , 2 );


add_action( 'acf/init' , function () {


    // check function exists.
    if ( function_exists( 'acf_register_block' ) and function_exists( 'acf_add_local_field_group' ) ) :


        droow_resources_block( 'DroowBlock' );
        droow_resources_block( 'DroowSVG' );


        // register a Introduction block.
        droow_resources_block( 'intro' );


        // register a Introduction block.
        droow_resources_block( 'paragraph' );

        // register a parallaxImage block.
        droow_resources_block( 'parallaxImage' );

        // register a embeddedVideo block.
        droow_resources_block( 'embeddedVideo' );

        // register a boxFigcaption block.
        droow_resources_block( 'boxVertical' );

        // register a lidaSlider block.
        droow_resources_block( 'slider' );

        // register a gallery block.
        droow_resources_block( 'gallery' );

        // register a gallery block.
        droow_resources_block( 'boxGallery' );


        // register a gallery block.
        droow_resources_block( 'parallaxImage_with_box' );


        // register a gallery block.
        droow_resources_block( 'service' );

        // register a client block.
        droow_resources_block( 'client' );


        // register a client block.
        droow_resources_block( 'experience' );


        // register a Brand block.
        droow_resources_block( 'Brand' );

        // register a blockQuote block.
        droow_resources_block( 'team' );

        // register a blockQuote block.
        droow_resources_block( 'featured-posts' );

        // register a blockQuote block.
        droow_resources_block( 'map' );

        // register a blockQuote block.
        droow_resources_block( 'contact' );


        add_filter( 'acf/load_field/name=image_size_acf' , function ( $field ) {
            $field[ 'choices' ] = acf_get_image_sizes();
            return $field;
        } );

        add_filter( 'acf/load_field/name=image_size_popup' , function ( $field ) {
            $field[ 'choices' ] = acf_get_image_sizes();
            return $field;
        } );


    endif;
}


);

