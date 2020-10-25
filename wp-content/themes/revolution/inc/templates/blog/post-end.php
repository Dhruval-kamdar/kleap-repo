<?php do_action( 'thb_post_nav' ); ?>
<?php if ( 'on' === ot_get_option( 'article_related', 'on' ) ) { ?>
	<?php do_action( 'thb_related' ); ?>
	<?php
}
