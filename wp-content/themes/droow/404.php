<?php
get_header();

$img = get_theme_mod( '404_bg_image' , DROOW_THEME_DIRECTORY . 'assets/img/404.jpg' );
?>
    <header class="dsn-header-hero error404">


        <div class="dsn-parallax-move-img w-100 h-100 p-absolute">

            <div class="hero-img">
                <div class="glitch__img cover-bg z-index-0" data-image-src='<?php echo esc_url( $img ) ?>'
                     data-overlay="4"></div>
            </div>

        </div>


        <div class="container v-middle">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-8">
                    <div class="header-content mb-60">
                        <div class="name-title justify-content-center">
                            <h1 class="">
                                    <span class="title-auto-break">
                                        <?php esc_html_e( '404' , 'droow' ) ?>
                                    </span>
                            </h1>
                        </div>
                        <h4 class="subtitle text-center text-capitalize">
                            <span>
                                <?php
                                esc_html_e( 'It looks like nothing was found at this location. Maybe try a search? ' , 'droow' );
                                ?>
                            </span>

                            <?php get_search_form(); ?>

                        </h4>

                    </div>

                </div>
            </div>
        </div>
    </header>
<?php get_footer(); ?>