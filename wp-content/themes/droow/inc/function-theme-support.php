<?php

/*
 *  @package droow/Inc
 *  =======================================
 *              Theme Support
 *  =======================================
 */


/**
 * This hook is called during each page load,
 * after the theme is initialized. It is generally used to perform basic
 * setup, registration, and init actions for a theme.
 */

add_action( 'after_setup_theme' , function () {

    /**
     *
     * ===================================================
     *      Load the theme is translated strings.
     * ===================================================
     *  - Make theme available for translation
     *  - Translations can be filed in the /lang/ directory.
     *  it will be included in the translated strings by the $domain
     *
     */
    load_theme_textdomain( 'droow' , get_template_directory() . '/lang' );

    //--==== Enables post and comment RSS feed links to head ====--//
    add_theme_support( 'automatic-feed-links' );

    /*
         * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
	*/
    add_theme_support( 'title-tag' );


    //--==== Enable support for Post Thumbnails on posts and pages. ====//
    add_theme_support( 'post-thumbnails' );


    /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
    add_theme_support(
        'html5' ,
        array(
            'comment-list' ,
            'comment-form' ,
            'search-form' ,
            'gallery' ,
            'caption' ,
            'script' ,
            'style' ,
        )
    );


    /**
     * * Using a custom logo allows site owners to upload an image for their website,
     * which can be placed at the top of their website. It can be uploaded from Appearance > Header,
     */

    add_theme_support( 'custom-logo' , array(
        'height'      => 40 ,
        'width'       => 250 ,
        'flex-height' => true ,
        'flex-width'  => true ,
        'header-text' => array( 'site-title' , 'site-description' ) ,
    ) );


    add_theme_support( 'custom-background' , array(
        'default-color'      => '#0d0d0d' ,
        'default-repeat'     => 'no-repeat' ,
        'default-position-x' => 'left' ,
        'default-position-y' => 'top' ,
        'default-size'       => 'auto' ,
        'default-attachment' => 'scroll' ,

    ) );


    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for Block Styles.
    add_theme_support( 'wp-block-styles' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );
    add_theme_support( 'dark-editor-style' );

    // Enqueue editor styles.
    add_editor_style( 'droow-editor.css' );


    //--==== Cropping behavior for the image size is dependent on the value ====//
    add_image_size( 'droow-large-img' , 1240 );
    add_image_size( 'droow-media-img' , 880 );
    add_image_size( 'droow-brand-img' , 300 );
    add_image_size( 'droow-slider-img' , 800 , 500 , true );
    add_image_size( 'droow_image_part' , 400 , 300 , true );
    add_image_size( 'droow_height_img' , 880 , 1399 , true );
    add_image_size( 'droow_vertical_img' , 300 , 450 , true );



    /**
     * ============================
     *     Editor Text Size Palette
     * ============================
     * A default set of sizes is provided, but themes can register their own and optionally lock users into picking from preselected sizes.
     */
    add_theme_support( 'editor-font-sizes' , array(
        array(
            'name'      => esc_html__( 'Small' , 'droow' ) ,
            'shortName' => esc_html__( 'S' , 'droow' ) ,
            'size'      => 16 ,
            'slug'      => 'small'
        ) ,
        array(
            'name'      => esc_html__( 'Normal' , 'droow' ) ,
            'shortName' => esc_html__( 'N' , 'droow' ) ,
            'size'      => 20 ,
            'slug'      => 'normal'
        ) ,
        array(
            'name'      => esc_html__( 'Medium' , 'droow' ) ,
            'shortName' => esc_html__( 'M' , 'droow' ) ,
            'size'      => 24 ,
            'slug'      => 'medium'
        ) ,
        array(
            'name'      => esc_html__( 'Large' , 'droow' ) ,
            'shortName' => esc_html__( 'L' , 'droow' ) ,
            'size'      => 36 ,
            'slug'      => 'large'
        ) ,
        array(
            'name'      => esc_html__( 'Huge' , 'droow' ) ,
            'shortName' => esc_html__( 'XL' , 'droow' ) ,
            'size'      => 50 ,
            'slug'      => 'huge'
        )
    ) );


    /**
     * ============================
     *     Responsive Embeds
     * ============================
     * Themes must opt-in to responsive embeds.
     */
    add_theme_support( 'responsive-embeds' );
} );

if ( !isset( $content_width ) ) {
    $content_width = 960;
}
