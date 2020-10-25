<?php

if ( !get_theme_mod( 'effect_cursor' ) ) return;
?>
<div class="cursor">

    <div class="cursor-helper cursor-view">
        <span><?php esc_html_e( 'VIEW' , 'droow' ) ?></span>
    </div>

    <div class="cursor-helper cursor-close">
        <span><?php esc_html_e( 'CLOSE' , 'droow' ) ?></span>
    </div>

    <div class="cursor-helper cursor-link"></div>
</div>
