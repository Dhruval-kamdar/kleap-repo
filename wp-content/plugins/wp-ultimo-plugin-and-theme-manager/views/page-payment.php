<?php

/**
 * Get the Screen
 * @var
 */
$screen = get_current_screen();

/**
 * Get the Extension
 * @var
 */
$slug = isset($_GET['extension']) ? $this->get_slug_from_file($_GET['extension']) : false;

/**
 * Get the extension
 */
$extension = wu_get_extension($slug);

/**
 * Site
 */
$site = wu_get_current_site();

/**
 * Adds the purchase metabox
 */
add_meta_box('wu-extension-payment', __('Unlock Extension', 'wp-ultimo'), 'render_payment_widget', $screen->id, 'normal', '', array(
    'extension' => $extension,
    'plan_id'   => $site->plan_id,
));

/**
 * Renders the payment widget
 * @param  null  $null
 * @param  array $args
 */
function render_payment_widget($null, $args) {

  WP_Ultimo_PTM()->render('purchase-form', $args['args']);

} // end;

?>

<div id="wp-ultimo-wrap" class="wrap">
  
  <h1><?php _e('Unlock Extension', 'wp-ultimo'); ?></h1>
  <p class="description"><?php _e('', 'wp-ultimo'); ?></p>

  <?php if (isset($_GET['deleted'])) : ?>
    <div id="message" class="updated notice notice-success is-dismissible below-h2"><p><?php _e('Message deleted successfully!', 'wp-ultimo'); ?></p>
    </div>
  <?php endif; ?>

  <div id="dashboard-widgets-wrap">

    <div id="dashboard-widgets" class="metabox-holder">

      <div id="postbox-container-0" class="postbox-container postbox-container-full">
          <?php do_meta_boxes($screen->id, 'normal', ''); ?>
      </div>

    </div>

  </div>
  
</div>