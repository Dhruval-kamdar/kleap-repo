<div class="error">
  <p><?php printf(__('You have activated the domain mapping option. We use Mercator by humanmade to handle this for WP Ultimo. You should be sure to have a configured <code>sunrise.php</code>, as well as a <code>define("SUNRISE", true);</code> in your <code>wp-config.php</code> file. But hey, fear not! The WP Ultimo WIzard offers a check feature and more details on how to proceed! %sClick here to visit the Wizard%s.', 'wp-ultimo'), '<a href="'. network_admin_url('admin.php?page=wu-setup&step=checks') .'">', '</a>'); ?></p>
</div>