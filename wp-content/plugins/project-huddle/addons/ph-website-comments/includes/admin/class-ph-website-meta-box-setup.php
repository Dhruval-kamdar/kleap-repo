<?php

/**
 * Setup Website Meta Box
 *
 * @package     ProjectHuddle
 * @copyright   Copyright (c) 2016, Andre Gagnon
 * @since       2.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * PH_Meta_Box_Project_Options Class
 *
 * @since 1.0
 */
class PH_Website_Meta_Box_Setup
{


	public static $fields = array();

	public static function meta_fields()
	{
		$fields = apply_filters(
			'ph_website_meta_box_options',
			array(
				array(
					'id'          => 'website_url',
					'description' => __('Enter the website URL to collect comments.', 'project-huddle'),
					'label'       => __('1. Enter the Website URL', 'project-huddle'),
					'placeholder' => 'http://',
					'type'        => 'url',
				),
			)
		);

		return $fields;
	}

	/**
	 * Output the metabox
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public static function output($post)
	{
		// create nonce field
		wp_nonce_field('project_huddle_save_data', 'project_huddle_meta_nonce');
		$url               = get_post_meta($post->ID, 'ph_website_url', true);
		$not_set_correctly = !filter_var($url, FILTER_VALIDATE_URL) && get_post_status($post) != 'auto-draft'; ?>

		<?php if ($not_set_correctly) : ?>
			<p class="notice notice-error"><?php _e('You need to enter a valid website URL.', 'project-huddle'); ?></p>
		<?php endif; ?>

		<style>
			[v-cloak] {
				display: none;
			}
		</style>

		<div id="project_website_container" class="ph_meta_box" v-cloak>
			<div>
				<ph-steps :space="200" :active="active" finish-status="success" simple>
					<ph-step class="ph-url-step" title="<?php _e('Setup', 'project-huddle'); ?>" @click.native="maybeStep(0)"></ph-step>
					<ph-step class="ph-connect-step" :class="{'has-url': url}" title="<?php _e('Connect', 'project-huddle'); ?>" @click.native="maybeStep(1)"></ph-step>
					<ph-step title="<?php _e('Verify', 'project-huddle'); ?>"></ph-step>
				</ph-steps>
				<div class="ph-steps-container">
					<div class="ph-step" v-if="active === 0">
						<h3><?php _e('Enter the website URL', 'project-huddle'); ?></h3>
						<div style="display:block; margin-bottom: 15px;">
							<ph-input type="url" required name="ph_website_url" placeholder="https://" prefix-icon="el-icon-link" style="max-width:400px;" v-model="url"></ph-input>
						</div>
						<ph-button @click="maybeSubmit" native-type="submit" type="primary" name="publish" :loading="isLoading" id="publish_website">
							<?php _e('Next', 'project-huddle'); ?>
							<i v-if="!isLoading" class="el-icon-arrow-right el-icon-right"></i>
						</ph-button>
					</div>

					<!-- Step 2 -->
					<div class="ph-step" v-if="active === 1">
						<h3><?php _e('What type of website is it? ', 'project-huddle'); ?></h3>

						<div style="display:block; margin-bottom: 15px;">
							<ph-radio v-model="websiteType" label="WordPress" border><?php _e('WordPress', 'project-huddle'); ?></ph-radio>
							<ph-radio v-model="websiteType" label="custom" border :disabled="isLoading"><?php _e('Something Else', 'project-huddle'); ?></ph-radio>
						</div>

						<!-- phpcs:disable -->
						<template v-if="websiteType === 'WordPress'">

							<template v-if="error">
								<ph-alert title="<?php esc_attr_e('Could not connect.', 'project-huddle'); ?>" type="error" :closable="false" :description="error" show-icon>
								</ph-alert>
							</template>

							<!-- We are not connected yet -->
							<template v-if="!status.connected">
								<!-- we are not trying manually -->
								<template v-if="!tryManual">
									<!-- show loading -->
									<template v-if="isLoading">
										<ph-button type="primary">
											<?php _e('Connecting...', 'project-huddle'); ?>
										</ph-button>
										<template v-if="status.connecting && !status.activated">
											<br>
											<a href="" @click.prevent="status.connected = false; closeWindow()">
												<?php _e('I\'ve activated the plugin', 'project-huddle'); ?>
											</a>
										</template>
									</template>

									<!-- Show username/password form -->
									<template v-else>
										<p style="margin: 35px auto;">
											<?php echo sprintf(__('Enter the <strong>Admin</strong> username and password for <strong>%s</strong>', 'project-huddle'), $url); ?>
										</p>

										<div style="max-width: 400px; text-align:left; margin: auto">
											<div style="margin-bottom: 10px;">
												<p><strong><?php _e('Username', 'project-huddle'); ?></strong></p>
												<ph-input v-model="username" placeholder="Enter the username" autocomplete="off"></ph-input>
											</div>
											<div style="margin-bottom: 25px;">
												<p><strong><?php _e('Password', 'project-huddle'); ?></strong></p>
												<ph-input placeholder="Enter the password" v-model="password" style="max-width: 400px;" show-password autocomplete="off"></ph-input>
											</div>
										</div>

										<a href="<?php echo esc_url(trailingslashit($url)); ?>wp-admin/plugin-install.php?tab=plugin-information&plugin=projecthuddle-child-site&TB_iframe=true" class="el-button el-button--primary" target="_blank" @click.prevent="install('<?php echo esc_url(trailingslashit($url)); ?>wp-admin/plugin-install.php?tab=plugin-information&plugin=projecthuddle-child-site&TB_iframe=true')">
											<?php _e('Connect and Install the Child Plugin', 'project-huddle'); ?>
											<i class="el-icon-top-right"></i>
										</a>
										<br><br>
										<a href="" @click.prevent="resetStatus(); tryManual = true"><?php _e('Or Try Connecting Manually', 'project-huddle'); ?></a>
									</template>

								</template>

								<!-- Try manually connecting -->
								<template v-if="tryManual">
									<br>
									<h3><?php echo sprintf(__('1. Install the client site plugin on %s. ', 'project-huddle'), $url); ?></h3>
									<p>
										<a href="<?php echo esc_url(trailingslashit($url)); ?>wp-admin/plugin-install.php?s=projecthuddle-child-site&tab=search&type=term" target="_blank" class="el-button el-button--primary">
											<?php _e('Click Here To Install The Client Site Plugin', 'project-huddle'); ?>
										</a>
									</p>
									<br>
									<h3><?php echo sprintf(__('2. Paste this code under <strong>Settings > Feedback > Connection</strong>. ', 'project-huddle'), $url); ?></h3>

									<textarea id="ph_website_js_code" onclick="this.focus();this.select()" readonly="readonly" style="width: 100%;" rows="7">
									{{ manualJSON | pretty }}</textarea>
									<p>
										<a href="<?php echo esc_url(trailingslashit($url)); ?>wp-admin/options-general.php?page=feedback-connection-options&tab=connection" target="_blank" class="el-button el-button--primary">
											<?php _e('Copy the code and click here', 'project-huddle'); ?>
										</a>
									</p>
									<p><a href="" @click.prevent="resetStatus(); verify();"><?php _e('I\'ve pasted the code.', 'project-huddle'); ?></a></p>
									<p><a href="" @click.prevent="resetStatus(); tryManual = false"><?php _e('Try Automatically Connecting.', 'project-huddle'); ?></a></p>

								</template>
							</template>

							<!-- If we've connected, but could not verify installed or activated -->
							<template v-else-if="!connecting">
								<ph-alert title="<?php esc_attr_e('Could not connect to remote site. Please try again or try connecting manually.', 'project-huddle'); ?>" type="error" :closable="false" :description="error" show-icon>
								</ph-alert>
								<p><a href="" @click.prevent="resetStatus(); tryManual = true;"><?php _e('Try manually connecting.', 'project-huddle'); ?></a></p>
								<p><a href="" @click.prevent="resetStatus(); tryManual = false"><?php _e('Try Automatically Connecting.', 'project-huddle'); ?></a></p>
								<p><a href="#" @click.prevent="resetStatus(); active=0;"><?php _e('Update Site URL', 'project-huddle'); ?></a></p>
							</template>
							<template v-else>
								<p><a href="" @click.prevent="resetStatus(); tryManual = false"><?php _e('Try Automatically Connecting.', 'project-huddle'); ?></a></p>
							</template>
						</template>

						<div v-if="websiteType === 'custom'">
							<p style="margin: 35px auto;">
								<?php echo htmlspecialchars(sprintf(__('Copy and paste this right before the closing </body> tag on %1s.', 'project-huddle'), $url)); ?>
							</p>
							<textarea id="ph_website_js_code" onclick="this.focus();this.select()" readonly="readonly" style="width: 100%;" rows="10">
		<script>
			(function (d, t, g) {
				var ph    = d.createElement(t), s = d.getElementsByTagName(t)[0];
				ph.type   = 'text/javascript';
				ph.async   = true;
				ph.charset = 'UTF-8';
				ph.src     = g + '&v=' + (new Date()).getTime();
				s.parentNode.insertBefore(ph, s);
			})(document, 'script', '<?php ph_the_api_url($post); ?>');
		</script></textarea>
							<div style="margin: 35px auto;">
								<ph-button @click="next" type="primary">
									<?php _e('Next', 'project-huddle'); ?>
									<i class="el-icon-arrow-right el-icon-right"></i>
								</ph-button>
							</div>
						</div>

						<div v-if="!status.installed" style="text-align: center; margin: 10px 0;">
							<a style="color: #999; text-decoration: none;" v-if="websiteType && websiteType != 'WordPress'" href="#" data-beacon-article-modal="5d4b183a0428635a6ec8a7a7"><?php _e('Need Help?', 'project-huddle'); ?></a>
							<a style="color: #999; text-decoration: none;" v-else href="#" data-beacon-article-modal="5d4b0d4d2c7d3a330e3c3a2c"><?php _e('Need Help?', 'project-huddle'); ?></a>
						</div>
					</div>

					<!-- Step 3 -->
					<div class="ph-step" v-if="active === 2">
						<template v-if="status.installed">
							<p>
								<ph-tooltip content="<?php echo esc_url(untrailingslashit($url)); ?>">
									<ph-tag type="success" style="margin-bottom: 15px; text-overflow: ellipsis; overflow: hidden; max-width: 100%;">
										<?php echo sprintf(__('Connected to %s', 'project-huddle'), esc_url(untrailingslashit($url))); ?>
									</ph-tag>
								</ph-tooltip>
							</p>
						</template>
						<template v-else>
							<ph-button type="primary" :loading="isLoading" v-if="isLoading">
								<?php _e('Verifying...', 'project-huddle'); ?>
							</ph-button>
							<a v-else href="<?php the_permalink(); ?>" @click.prevent="verify" class="el-button el-button--primary">
								<?php _e('Verify Installation', 'project-huddle'); ?>
							</a>
						</template>
						<p v-if="!isLoading">
							<a href="#" @click.prevent="resetStatus(); active=0;"><?php _e('Update Connection Details', 'project-huddle'); ?></a>
						</p>
					</div>
				</div>
			</div>
		</div>
<?php
	}

	/**
	 * Save meta box data
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public static function save($post_id, $post)
	{
		$fields = self::meta_fields();

		foreach ($fields as $field) {
			$value = self::sanitize_field($field);
			if ($value == get_site_url() && get_post_status((int) get_option('ph_site_post')) == 'publish' && $value == get_post_meta(get_option('ph_site_post'), 'website_url', true)) {
				PH_Website_Meta_Boxes::add_error(__('This website has already been added!', 'project-huddle') . ' <a href="' . get_edit_post_link((int) get_option('ph_site_post')) . '">' . __('View Project', 'project-huddle') . '</a>');
				continue;
			}
			update_post_meta($post_id, 'ph_' . $field['id'], esc_attr($value));
			update_post_meta($post_id, $field['id'], esc_attr($value));
		}
	}

	public static function sanitize_field($field)
	{

		$value = isset($_POST['ph_' . $field['id']]) ? $_POST['ph_' . $field['id']] : false;

		switch ($field['type']) {
			case 'checkbox':
				$value = $value ? esc_html($value) : 'off';
				break;
		}

		return $value;
	}
}
