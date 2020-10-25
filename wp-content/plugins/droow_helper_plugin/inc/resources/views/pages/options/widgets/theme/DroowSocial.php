<?php


class DroowSocial extends WP_Widget
{


    function __construct()
    {

        parent::__construct(
            'droow_social',
            esc_html__( 'Droow : Social', 'droow' ),
            array( 'description' => esc_html__( 'Displays social media links.', 'droow' ) )
        );
    }

    /**
     * Display widget on frontend
     *
     * @param array $args widget arguments.
     * @param array $instance saved data from settings
     */
    function widget( $args, $instance )
    {

        if ( !isset( $args[ 'widget_id' ] ) ) {
            $args[ 'widget_id' ] = $this->id;
        }


        $use_logo = droow_acf_option( 'use_logo', false, 'widget_' . $args[ 'widget_id' ] );
        $dsn_display = droow_acf_option( 'dsn_display', 'row', 'widget_' . $args[ 'widget_id' ] );
        $justify_content = droow_acf_option( 'justify_content', 'end', 'widget_' . $args[ 'widget_id' ] );
        $align_items = droow_acf_option( 'align_items', 'end', 'widget_' . $args[ 'widget_id' ] );
        $display_social = droow_acf_option( 'display_social', 'name', 'widget_' . $args[ 'widget_id' ] );
        $dsn_social = droow_acf_option( 'dsn_social', array(), 'widget_' . $args[ 'widget_id' ] );
        $title = droow_acf_option( 'title', array(), 'widget_' . $args[ 'widget_id' ] );


        echo $args[ 'before_widget' ];
        if ( !empty( $title ) ) {
            ?>
            <div class="d-flex  justify-content-<?php echo esc_attr( $align_items ) ?>">
                <?php
                echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
                ?>
            </div>
            <?php
        }
        ?>
        <style>
            .widget.widget_droow_social {
                height: 100%;
            }
        </style>
        <?php if ( ( has_custom_logo() || get_theme_mod( 'custom_logo_dark' ) ) && $use_logo ): ?>
        <div class="footer-logo d-flex justify-content-<?php echo esc_attr( $justify_content ) ?>  justify-items-<?php echo esc_attr( $align_items ) ?>">
            <?php

            echo ' <a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
            if ( has_custom_logo() ):
                echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array(
                    'alt'   => get_bloginfo( 'name', 'display' ),
                    'class' => 'logo-light'
                ) );
            endif;

            if ( get_theme_mod( 'custom_logo_dark' ) ):
                echo wp_get_attachment_image( get_theme_mod( 'custom_logo_dark' ), 'full', false, array(
                    'alt'   => get_bloginfo( 'name', 'display' ),
                    'class' => 'logo-dark'
                ) );
            endif;
            echo ' </a>';
            ?>
        </div>
    <?php endif; ?>

        <div class="copyright-social type-socila-<?php echo esc_attr( $display_social ) ?> type-flex-<?php echo esc_attr( $dsn_display ) ?> h-100 d-flex flex-<?php echo esc_attr( $dsn_display ) ?> justify-content-<?php echo esc_attr( $justify_content ) ?> align-items-<?php echo esc_attr( $align_items ) ?>">
            <ul class="d-flex flex-<?php echo esc_attr( $dsn_display ) ?> justify-content-<?php echo esc_attr( $justify_content ) ?> align-items-<?php echo esc_attr( $align_items ) ?>">
                <?php echo droow_get_social( $display_social, $dsn_social ) ?>
            </ul>
        </div>
        <?php
        echo $args[ 'after_widget' ];
    }

    /**
     * Admin settings
     *
     * @param array $instance saved data from settings
     */
    function form( $instance )
    {

    }


    function update( $new_instance, $old_instance )
    {
        return $new_instance;
    }

}
