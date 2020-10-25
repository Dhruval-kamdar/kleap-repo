<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );


$title       = $shortcode->Title();
$description = $shortcode->Description();
$url_link    = $shortcode->UrlLink();


$details = $content;
if ( is_string( $content ) && $content )
    $details = explode( ',' , $content );


?>

<div class="container intro-project <?php echo esc_attr( $shortcode->className() ) ?>"  <?php echo esc_attr( $shortcode->changeColor() ) ?>>
    <div class="intro-text">
        <?php if ( $shortcode->TitleCover() ): ?>
            <div class="title-cover" data-dsn-grid="move-section" data-dsn-opacity="0.1"
                 data-dsn-duration="170%" data-dsn-move="0%">
                <?php echo esc_html( $shortcode->TitleCover() ); ?>
            </div>
        <?php endif; ?>
        <div class="inner">
            <?php
            if ( $title )
                printf( '<h2 class="dsn-title-p" %s>%s</h2>' , $shortcode->titleAnimate() , $title );
            if ( $description )
                printf( '<p class="span-small" %s>%s</p>' , $shortcode->DescriptionAnimate() , $description );
            if ( $details ):
                echo '<ul data-dsn-animate="up">';
                foreach ( $details as $d )
                    printf( '<li>%s</li>' , $d );
                echo '</ul>';
            endif;
            ?>


        </div>
    </div>
    <?php
    if ( $url_link ): ?>
        <a class="bottom-link" href="<?php echo esc_url( $url_link ) ?>" data-dsn-animate="up" target="_blank">
            <div class="content">
                <div class="inner">
                    <p><?php echo esc_html( $shortcode->TitleLink( 'VISIT SITE' ) ) ?></p>
                </div>
            </div>
        </a>
    <?php endif; ?>

</div>