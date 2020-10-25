<header class="<?php droow_space_header(); ?>">

    <div class="header-hero header-hero-2" data-dsn-header="project">
        <div id="descover-holder" class="bg-circle-dotted"></div>

        <div class="container">
            <div class="row f-align-center">
                <div class="col-lg-12">
                    <div id="dsn-hero-parallax-titles" class="contenet-hero project-title">
                        <h1 class="dsn-title-header">
                            <?php echo droow_custom_title() ?>
                        </h1>
                        <p>
                            <?php echo droow_description_head() ?>
                        </p>
                        <a href="#" class="view-case scroll-down">
                            <?php esc_html_e( 'Scroll Down' , 'droow' ); ?>
                            <img src="<?php echo DROOW_THEME_DIRECTORY ?>assets/img/arrow-right-wight.png"
                                 alt="<?php esc_attr_e( 'arrow right wight' , 'droow' ) ?>">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
