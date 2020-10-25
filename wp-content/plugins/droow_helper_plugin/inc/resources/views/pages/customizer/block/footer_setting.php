<?php
if ( !function_exists( 'droow_sanitize_minimal_decoration' ) ) :
    function droow_sanitize_minimal_decoration( $input )
    {
        $allowed_html = array(
            'a'      => array(
                'href'  => array(),
                'title' => array()
            ),
            'br'     => array(),
            'em'     => array(),
            'strong' => array(),
            'img'    => array(),
            'i'      => array(),

        );

        return wp_kses( $input, $allowed_html );
    }

endif;


Kirki::add_panel( $dsn_panel . "-2", array(
    'title'       => esc_html__( 'Footer Settings', 'droow' ),
    'description' => esc_html__( 'Options Footer Theme', 'droow' ),
    'panel'       => $dsn_panel,
    'icon'        => 'dashicons-tide'
) );

$dsn_panel = $dsn_panel . "-2";


/**
 * Normal Static Setting
 */
$dsn_section = $dsn_section . '-1';
Kirki::add_section( $dsn_section, array(
    'panel'       => $dsn_panel,
    'title'       => esc_html__( 'Footer Default', 'droow' ),
    'description' => esc_html__( 'When you create a page, it\'s going to be default.', 'droow' )
) );
Kirki::add_field( $dsn_customize, [
    'type'      => 'radio',
    'settings'  => 'footer_setting',
    'label'     => esc_html__( 'Footer Default Setting', 'kirki' ),
    'section'   => $dsn_section,
    'default'   => 'normal',
    'priority'  => 10,
    'choices'   => [
        'normal'        => esc_html__( 'Footer Normal Static', 'droow' ),
        'normal-widget' => esc_html__( 'Footer Normal Widget', 'droow' ),
        'feature'       => esc_html__( 'Footer Feature Widget', 'droow' ),
    ],
    'transport' => 'postMessage',
] );


/**
 * Normal Static Setting
 */
$dsn_section = $dsn_section . '-2';
Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Footer Normal Option (Static)', 'droow' ),
) );


Kirki::add_field( $dsn_customize, [
    'type'              => 'textarea',
    'settings'          => 'footer_cr',
    'label'             => esc_attr__( 'Footer Credits Text', 'droow' ),
    'description'       => esc_attr__( 'Allowed HTML Tags: a, em, br, strong, img, i. and you can use year dynamic add :y', 'droow' ),
    'section'           => $dsn_section,
    'sanitize_callback' => 'droow_sanitize_minimal_decoration',
    'default'           => '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ),
] );

Kirki::add_field( $dsn_customize, [
    'type'     => 'text',
    'settings' => 'footer_tel',
    'label'    => esc_attr__( 'Mobile', 'droow' ),
    'section'  => $dsn_section,
    'default'  => '',

] );


Kirki::add_field( $dsn_customize, [
    'type'     => 'text',
    'settings' => 'footer_email',
    'label'    => esc_attr__( 'E-mail', 'droow' ),
    'section'  => $dsn_section,
    'default'  => ''
] );
/**
 * End Normal Static Setting
 */


/**
 * Normal Dynamic Setting
 */
$dsn_section = $dsn_section . '-3';
Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Footer Normal Option (Dynamic)', 'droow' ),
) );

/**
 * Footer Layout and Order
 */
droow_custom_Label( $dsn_section,
    esc_html__( 'Number of Columns', 'droow' ),
    esc_html__( 'This setting creates a widget area per each column. You can edit your widgets in WordPress admin panel.', 'droow' )
);


Kirki::add_field( $dsn_customize, [
    'type'     => 'sortable',
    'settings' => 'footer-normal-columns',
    'section'  => $dsn_section,
    'default'  => [
        'footer-1',
        'footer-2',
    ],
    'choices'  => [
        'footer-1' => esc_html__( 'Footer Normal Column 1', 'droow' ),
        'footer-2' => esc_html__( 'Footer Normal Column 2', 'droow' ),
        'footer-3' => esc_html__( 'Footer Normal Column 3', 'droow' ),
        'footer-4' => esc_html__( 'Footer Normal Column 4', 'droow' ),
    ]
] );
/**
 * End Normal Dynamic Setting
 */


/**
 * Feature Dynamic Setting
 */
$dsn_section = $dsn_section . '-4';
Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Footer Feature Option', 'droow' ),
) );

/**
 * Footer Layout and Order
 */
droow_custom_Label( $dsn_section,
    esc_html__( 'Number of Columns', 'droow' ),
    esc_html__( 'This setting creates a widget area per each column. You can edit your widgets in WordPress admin panel.', 'droow' )
);

Kirki::add_field(
    $dsn_customize,
    array(
        'type'      => 'radio-buttonset',
        'settings'  => 'footer-container',
        'label'     => esc_html__( 'Container', 'droow' ),
        'section'   => $dsn_section,
        'default'   => 'container',
        'choices'   => array(
            'container'       => esc_html__( 'Boxed', 'rubenz' ),
            'container-fluid' => esc_html__( 'Fullwidth', 'rubenz' ),
        ),
        'transport' => 'postMessage',
    )
);

Kirki::add_field( $dsn_customize, [
    'type'     => 'sortable',
    'settings' => 'footer-feature-columns',
    'section'  => $dsn_section,
    'default'  => [
        'footer-1',
        'footer-2',
        'footer-3',
        'footer-4',
    ],
    'choices'  => [
        'footer-1' => esc_html__( 'Footer Feature Column 1', 'droow' ),
        'footer-2' => esc_html__( 'Footer Feature Column 2', 'droow' ),
        'footer-3' => esc_html__( 'Footer Feature Column 3', 'droow' ),
        'footer-4' => esc_html__( 'Footer Feature Column 4', 'droow' ),
    ]
] );
/**
 * End Feature Dynamic Setting
 */


/**
 * Feature Dynamic Setting
 */
$dsn_section = $dsn_section . '-5';
Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Footer JS Code', 'droow' ),
) );

Kirki::add_field( $dsn_customize, [
    'type'      => 'code',
    'settings'  => 'js_footer_code',
    'label'     => esc_html__( 'Code JS Footer', 'droow' ),
    'section'   => $dsn_section,
    'transport' => 'auto',
    'default'   => '',
    'choices'   => [
        'language' => 'js',
    ],

] );


add_action( 'wp_footer', function () {
    printf( '<script>%s</script>', get_theme_mod( 'js_footer_code' ) );
}, 30 );