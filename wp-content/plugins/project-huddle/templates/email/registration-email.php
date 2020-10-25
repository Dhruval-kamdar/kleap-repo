<?php

/**
 * Share comment email
 */
ph_get_template('email/default-header.php'); ?>
<td align="left" valign="top">
	<?php
	echo wpautop(apply_filters(
		'ph_email_new_user_registration_email',
		'<p style="color: #999;">Hi {{username}},</p>
			<p style="color: #999;">Please set your password so you can access your account for <strong style="color: #000;">{{site_name}}</strong>.</p>
			<p style="color: #999;">Set your password here:</p>
            <p style="text-align:center">
            {{link}}
            </p>'
	));
	?>
</td>
<?php ph_get_template('email/default-footer.php');
