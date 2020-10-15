<div class="widget-search">
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
        <input type="search" value="<?php echo get_search_query() ?>"
               placeholder="<?php esc_attr_e( 'Find what you want...' , 'droow' ) ?>" name="s">
    </form>
</div>