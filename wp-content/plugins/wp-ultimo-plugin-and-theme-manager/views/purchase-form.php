<?php // var_dump($plan_id); ?>

<p><?php printf(__('This is a one-time payment to unlock the <strong>%s</strong> extension. After the payment is confirmed, you\'ll be capable of activating the extension on your site.', 'wp-ultimo'), $extension->get_title()); ?></p>

<div id="major-publishing-actions" style="margin: 12px -12px -12px;">
  <a target="_blank" href="https://docs.wpultimo.com/community/" class="button button-primary button-streched"><?php printf(__('Pay %s to Unlock %s', 'wp-ultimo'), wu_format_currency($extension->get_price($plan_id)), $extension->get_title()); ?></a>
</div>

