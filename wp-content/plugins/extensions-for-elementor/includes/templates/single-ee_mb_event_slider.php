<?php

$post_id = get_the_ID();
$post = get_post($post_id);

$meta  = get_post_meta($post_id);
$title = $post->post_title;
$desc = $post->post_content;
$link  = get_the_permalink();
$feature = get_the_post_thumbnail_url( $post_id, 'large' ); 

$start = $end = $start_time = $end_time = $location = $website = $all_day = '';
if(!empty($meta)):
   $start = (!empty($meta['_ee_mb_start_date'][0])) ? $meta['_ee_mb_start_date'][0] : '';
   $end = (!empty($meta['_ee_mb_end_date'][0])) ? $meta['_ee_mb_end_date'][0] : '';
   $start_time = (!empty($meta['_ee_mb_start_time'][0])) ? $meta['_ee_mb_start_time'][0] : '';
   $end_time = (!empty($meta['_ee_mb_end_time'][0])) ? $meta['_ee_mb_end_time'][0] : '';
   $location = (!empty($meta['_ee_mb_event_location'][0])) ? $meta['_ee_mb_event_location'][0] : '';
   $website = (!empty($meta['_ee_mb_event_website'][0])) ? $meta['_ee_mb_event_website'][0] : '';
   $all_day = (!empty($meta['_ee_mb_all_day_event'][0])) ? $meta['_ee_mb_all_day_event'][0] : '';

   $current_date = strtotime(date("Y-m-d H:i:s"));
 
   $strend = 0;
   if(!empty($start)):
      $strend = strtotime($start);
   endif;

   if(!empty($start) && !empty($start_time)):
      $strend = strtotime($start.' '.$start_time);
   endif;
      
   if(!empty($end)):
      $strend = strtotime($end);
   endif;
   
   if(!empty($end) && !empty($end_time)):
      $strend = strtotime($end.' '.$end_time);
   endif;
endif;
   
get_header();
?>
<div class="es_page_wrapper">
   <div class="siteset-elementor-container">
      <div class="inner_wrapper">

         <?php if($current_date > $strend): ?>
            <div class="notices"><?php _e('Event has passed'); ?></div>
         <?php endif; ?>

         <?php if(!empty($feature)): 
            echo '<div class="image_wrapper">
               <img src="'.esc_url($feature).'"/>
            </div>';
                      endif; ?>

         <div class="content_wrapper">

            <?php if(!empty($title)): ?>
            <div class="title_wrapper">
               <h1><?php _e($title, 'elementor-extensions'); ?></h1>
            </div>
            <?php endif; 

            if ( have_posts() ) {

               while ( have_posts() ) {
                  the_post();

                   if(!empty($desc)): ?>
                     <div class="desc_wrapper">
                        <?php the_content(); ?>
                     </div>
                  <?php endif; 
               }
            }
            ?>
            <div class="page-meta-details">
               <h2 class="meta-title"><?php _e('Details', 'elementor-extensions'); ?> </h2>

               <?php if(!empty($start)): ?>
               <dl>
                  <dt class="meta-field-title"> <?php _e('Start', 'elementor-extensions'); ?>: </dt>
                  <dd>
                     <abbr class="meta-field-value" title="<?php echo $start; ?>"><?php echo date('l jS F Y', strtotime($start)); ?> </abbr>
                  </dd>
               </dl>
               <?php endif; ?>

               <?php if(!empty($end)): ?>
               <dl>
                  <dt class="meta-field-title"> <?php _e('End', 'elementor-extensions'); ?>: </dt>
                  <dd>
                     <abbr class="meta-field-value" title="<?php echo $end; ?>"><?php echo date('l jS F Y', strtotime($end)); ?> </abbr>
                  </dd>
               </dl>
               <?php endif; ?>

               <?php if(!empty($website)): ?>
               <dl>
                  <dt class="meta-field-title"> <?php _e('Website', 'elementor-extensions'); ?>: </dt>
                  <dd>
                     <abbr class="meta-field-value" title="<?php echo $website; ?>"><a href="<?php echo esc_url($website); ?>"  target="_blank"><?php echo $website; ?></a> </abbr>
                  </dd>
               </dl>
               <?php endif; ?>

               <?php if(!empty($location)): ?>
               <dl>
                  <dt class="meta-field-title"> <?php _e('Location', 'elementor-extensions'); ?>: </dt>
                  <dd>
                     <abbr class="meta-field-value" title="<?php echo $location; ?>"><?php echo $location; ?> </abbr>
                  </dd>
               </dl>
               <?php endif; ?>
            </div>
         </div>

      </div>
   </div>
</div>
<?php
get_footer();
