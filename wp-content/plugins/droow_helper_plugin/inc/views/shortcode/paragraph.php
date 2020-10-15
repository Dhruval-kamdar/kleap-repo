<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );


$title       = $shortcode->Title();
$description = $shortcode->Description();
$url_link    = $shortcode->UrlLink();
$title_cover = $shortcode->TitleCover();


?>


<div class="<?php echo esc_attr( $shortcode->layout() ) ?> intro-project section-p <?php echo esc_attr( $shortcode->className() ) ?>"  <?php echo esc_attr( $shortcode->changeColor() ) ?>>
    <div class="intro-text ">
        <?php if ( $title_cover ): ?>
            <div class="title-cover" data-dsn-grid="move-section" data-dsn-opacity="0.1"
                 data-dsn-duration="170%" data-dsn-move="0%">
                <?php echo esc_html( $title_cover ); ?>
            </div>
        <?php endif; ?>
        <div class="inner">
            <?php
            if ( $title )
                printf( '<h2 class="title" %s>%s</h2>' , $shortcode->titleAnimate() , $title );
            if ( $description )
                printf( '<h2 %s>%s</h2>' , $shortcode->DescriptionAnimate() , $description );
            ?>
        </div>
    </div>

</div>