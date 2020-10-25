<?php


class DroowCopyright extends WP_Widget
{


    function __construct()
    {

        parent::__construct(
            'droow_copyright',
            esc_html__( 'Droow : Copyright', 'droow' ),
            array( 'description' => esc_html__( 'Displays small text.', 'droow' ) )
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

        $description = droow_acf_option( 'description', date( 'Y' ) . ' &copy; ' . get_bloginfo( 'name' ), 'widget_' . $args[ 'widget_id' ] );


        echo $args[ 'before_widget' ];
        ?>
        <div class="copyright-social">
            <?php echo str_replace( ':y', date( 'Y' ),  $description  ) ?>
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


}
