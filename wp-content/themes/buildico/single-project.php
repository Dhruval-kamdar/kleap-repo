
<?php
/**
 * Project Details Template
 *
 * @package buildico
 */
get_header(); 

if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
?>

<?php
global $post;
$currentID = get_the_ID();
$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $currentID ), 'large' );
?>

<div class="page-header sine-post-header" style="background-image: url(<?php if ( has_post_thumbnail() ) { echo esc_url( $featured_img[0] ); } ?>);">
	<div class="<?php echo esc_attr( $container ); ?>">
		<div class="col-md-7">
			<?php 
				the_title( '<h1 class="page-title">', '</h1>' );
			?>
		    <?php 
		    if( wt_get_option('breadcrumbs_enable') == true ){
			    if( function_exists( 'bcn_display' ) ) {
			    	echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
			        bcn_display();
			        echo '</div>';
			    } 
			} ?>
			
		</div>
	</div>
</div><!-- /.page-header -->

<div class="wrapper" id="project-details">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			<?php 
			global $wp_query;
			$postid = $wp_query->post->ID;
			if( get_post_meta( $postid, '_project_metabox_options', true ) ){
					$meta_data = get_post_meta( $postid, '_project_metabox_options', true );
			}else{
				$meta_data = array();
			}
			if( isset( $meta_data['project_gallery'] ) || array_key_exists( 'project_gallery', $meta_data ) ){
				$gallery_items = $meta_data['project_gallery'];
			}else{
				$gallery_items ='';
			}
			$ids = explode( ',', $gallery_items );

			if( ! empty( $gallery_items ) ) :
			?>
			<div class="project-slider">
				<div id="projectsingle-carousel" class="owl-carousel project-single-carousel">
					<?php foreach ( $ids as $id ){
						$attachment = wp_get_attachment_image_src( $id, 'large' );
						echo '<div class="project-item"><img src="'. esc_url( $attachment[0] ). '" alt="'. get_the_title($id) . '"></div>';
					} ?>
				</div>
			</div>
			<?php endif; ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="row">
				<div class="col-md-8 sm-padding">
					<div class="project-content">
						<?php the_content(); ?>
					</div>
				</div>

				<div class="col-md-4 sm-padding">
					<div class="project-info">
						<h3><?php echo esc_html__( 'Project Details', 'buildico'); ?></h3>
						<ul>
						<?php
							if ( array_key_exists( 'pj_location', $meta_data ) && !empty( $meta_data['pj_location'] ) ) {
								echo '<li><span>'. esc_html__( 'Location : ', 'buildico' ) .'</span>'. esc_html( $meta_data['pj_location'] ) .'</li>';
							}
							if ( array_key_exists( 'pj_budget', $meta_data ) && !empty( $meta_data['pj_budget'] ) ) {
								echo '<li><span>'. esc_html__( 'Budgets : ', 'buildico' ) .'</span>'. esc_html( $meta_data['pj_budget'] ) .'</li>';
							}
							if (  array_key_exists( 'pj_date', $meta_data ) && !empty( $meta_data['pj_date'] ) ) {
								echo '<li><span>'. esc_html__( 'Complete Date : ', 'buildico' ) .'</span>'. esc_html( $meta_data['pj_date'] ) .'</li>';
							}
							if ( array_key_exists( 'pj_client_name', $meta_data ) && !empty( $meta_data['pj_client_name'] ) ) {
								echo '<li><span>'. esc_html__( 'Client Name : ', 'buildico' ) .'</span>'. esc_html( $meta_data['pj_client_name'] ) .'</li>';
							}
							if( array_key_exists( 'pj_extra_data', $meta_data) && ! empty($meta_data['pj_extra_data'])){
								$pj_extra_data = $meta_data['pj_extra_data'];
								foreach( $pj_extra_data as $pj_data ){
									echo '<li><span>'. esc_html( $pj_data['pj_field_label'] ) .' : </span>'. esc_html( $pj_data['pj_field_data'] ) .'</li>';
								}
							}
						?>
						</ul>
					</div>
				</div>
			</div><!-- .row -->
		</div>
		<?php endwhile; endif; ?>

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>