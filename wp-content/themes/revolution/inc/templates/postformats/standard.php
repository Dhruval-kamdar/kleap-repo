<?php
	$thb_id         = get_the_ID();
	$post_header_bg = get_post_meta( $thb_id, 'post_header_bg', true );
	$article_style  = ot_get_option( 'article_style', 'style1' );

	$article_sidebar     = ot_get_option( 'article_sidebar', 'on' );
	$article_author_name = ot_get_option( 'article_author_name', 'on' );
	$article_date        = ot_get_option( 'article_date', 'on' );
	$article_cat         = ot_get_option( 'article_cat', 'on' );
?>
<?php if ( 'style1' === $article_style ) { ?>
<figure class="post-gallery parallax post-gallery-detail">
	<div class="parallax_bg">
		<?php if ( $post_header_bg ) { ?>
			<style>
				.post-<?php echo esc_attr( $thb_id ); ?> .parallax_bg {
					<?php thb_bgoutput( $post_header_bg ); ?>
				}
			</style>
			<?php
		} else {
			the_post_thumbnail( 'revolution-wide-3x' ); }
		?>
	</div>

	<div class="header-spacer-force"></div>
	<header class="post-title entry-header animation bottom-to-top-3d">
		<div class="row align-center">
			<div class="small-12 medium-10 large-7 columns">
				<?php if ( 'on' === $article_cat ) { ?>
				<aside class="post-category">
					<?php the_category( ', ' ); ?>
				</aside>
				<?php } ?>
				<?php the_title( '<h1 class="entry-title" itemprop="name headline">', '</h1>' ); ?>
				<?php if ( 'on' === $article_author_name || 'on' === $article_date ) { ?>
				<aside class="post-meta">
					<?php
					if ( 'on' === $article_author_name ) {
						?>
						<?php the_author_posts_link(); ?> <?php esc_html_e( 'on', 'revolution' ); ?> <?php } ?>
					<?php
					if ( 'on' === $article_date ) {
						?>
						<?php echo get_the_date(); ?><?php } ?>
				</aside>
				<?php } ?>
			</div>
		</div>
	</header>
</figure>
<?php } elseif ( has_post_thumbnail() ) { ?>
<figure class="post-gallery">
	<?php the_post_thumbnail( 'revolution-squaresmall-x3' ); ?>
</figure>
<?php } ?>
