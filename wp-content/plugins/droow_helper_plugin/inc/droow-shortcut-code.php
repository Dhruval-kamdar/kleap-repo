<?php


add_shortcode( 'dsnIntro' , 'droow_shortcode_intro' );

if ( !function_exists( 'droow_shortcode_intro' ) ) :

    function droow_shortcode_intro( $att , $content = null )
    {
        return droow_view( 'shortcode/dsnIntro' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'paragraph' , 'droow_shortcode_paragraph' );

if ( !function_exists( 'droow_shortcode_paragraph' ) ) :

    function droow_shortcode_paragraph( $att , $content = null )
    {
        return droow_view( 'shortcode/paragraph' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'parallaxImage' , 'droow_shortcode_parallaxImage' );

if ( !function_exists( 'droow_shortcode_parallaxImage' ) ) :

    function droow_shortcode_parallaxImage( $att , $content = null )
    {
        return droow_view( 'shortcode/parallaxImage' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'embeddedVideo' , 'droow_shortcode_embeddedVideo' );

if ( !function_exists( 'droow_shortcode_embeddedVideo' ) ) :

    function droow_shortcode_embeddedVideo( $att , $content = null )
    {
        return droow_view( 'shortcode/embedded' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;

add_shortcode( 'boxVertical' , 'droow_shortcode_boxVertical' );

if ( !function_exists( 'droow_shortcode_boxVertical' ) ) :

    function droow_shortcode_boxVertical( $att , $content = null )
    {
        return droow_view( 'shortcode/boxVertical' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'dsnSlider' , 'droow_shortcode_dsnSlider' );

if ( !function_exists( 'droow_shortcode_dsnSlider' ) ) :

    function droow_shortcode_dsnSlider( $att , $content = null )
    {
        return droow_view( 'shortcode/dsnSlider' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;

add_shortcode( 'dsnGallery' , 'droow_shortcode_dsnGallery' );

if ( !function_exists( 'droow_shortcode_dsnGallery' ) ) :

    function droow_shortcode_dsnGallery( $att , $content = null )
    {

        return droow_view( 'shortcode/dsnGallery' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;

add_shortcode( 'dsnGallery' , 'droow_shortcode_dsnBoxGallery' );

if ( !function_exists( 'droow_shortcode_dsnBoxGallery' ) ) :

    function droow_shortcode_dsnBoxGallery( $att , $content = null )
    {

        return droow_view( 'shortcode/dsnBoxGallery' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'ImageWithBox' , 'droow_shortcode_ImageWithBox' );

if ( !function_exists( 'droow_shortcode_ImageWithBox' ) ) :

    function droow_shortcode_ImageWithBox( $att , $content = null )
    {

        return droow_view( 'shortcode/ImageWithBox' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'dsnService' , 'droow_shortcode_Service' );

if ( !function_exists( 'droow_shortcode_Service' ) ) :

    function droow_shortcode_Service( $att , $content = null )
    {

        return droow_view( 'shortcode/dsnService' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;

add_shortcode( 'dsnContact' , 'droow_shortcode_contact' );

if ( !function_exists( 'droow_shortcode_contact' ) ) :

    function droow_shortcode_contact( $att , $content = null )
    {

        return droow_view( 'shortcode/contact' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;



add_shortcode( 'dsnPosts' , 'droow_shortcode_posts' );

if ( !function_exists( 'droow_shortcode_posts' ) ) :

    function droow_shortcode_posts( $att , $content = null )
    {

        return droow_view( 'shortcode/dsnPosts' , array( 'attr' => $att , 'content' => $content ) );
    }

endif;


add_shortcode( 'dsnClient' , 'droow_shortcode_client' );

if ( !function_exists( 'droow_shortcode_client' ) ) :

    function droow_shortcode_client( $att , $content = null )
    {
        return droow_view( 'shortcode/client' , array( 'attr' => $att , 'content' => $content ) );
    }
endif;


add_shortcode( 'dsnExperience' , 'droow_shortcode_experience' );

if ( !function_exists( 'droow_shortcode_experience' ) ) :

    function droow_shortcode_experience( $att , $content = null )
    {
        return droow_view( 'shortcode/experience' , array( 'attr' => $att , 'content' => $content ) );
    }
endif;


add_shortcode( 'dsnBrand' , 'droow_shortcode_brand' );


if ( !function_exists( 'droow_shortcode_brand' ) ) :

    function droow_shortcode_brand( $att , $content = null )
    {
        return droow_view( 'shortcode/brand' , array( 'attr' => $att , 'content' => $content ) );
    }
endif;



add_shortcode( 'dsnTeam' , 'droow_shortcode_team' );


if ( !function_exists( 'droow_shortcode_team' ) ) :

    function droow_shortcode_team( $att , $content = null )
    {
        return droow_view( 'shortcode/team' , array( 'attr' => $att , 'content' => $content ) );
    }
endif;


add_shortcode( 'dsnMap' , 'droow_shortcode_map' );


if ( !function_exists( 'droow_shortcode_map' ) ) :

    function droow_shortcode_map( $att , $content = null )
    {
        return droow_view( 'shortcode/map' , array( 'attr' => $att , 'content' => $content ) );
    }
endif;

