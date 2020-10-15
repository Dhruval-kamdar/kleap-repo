<header>
    <div class="header-hero">
        <div class="container">
            <div class="row f-align-center">
                <div class="col-lg-10">
                    <div class="contenet-hero">
                        <?php
                        if ( droow_subtitle_head() ):
                            printf( '<h5>%s</h5>' , droow_subtitle_head() );
                        endif;
                        ?>
                        <h1>
                            <?php echo droow_custom_title() ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>