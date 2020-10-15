<template>
	<fragment>
		<label class="sui-label">{{ $i18n.settings.tooltips.connected_account }}</label>
		<div class="sui-border-frame google-account-overview">
			<div class="sui-box-builder sui-flushed">
				<div class="sui-builder-fields">
					<div class="sui-builder-field">
						<div class="sui-builder-field-label">
							<span v-if="userPhoto" class="beehive-google-user-photo">
								<img :src="userPhoto" :alt="userName" />
							</span>
							<span>
								<span class="beehive-google-user-name">{{ userName }}</span>
								<span class="beehive-google-user-email">{{ userEmail }}</span>
							</span>
						</div>
						<div class="sui-dropdown">
							<button class="sui-button-icon sui-dropdown-anchor">
								<i class="sui-icon-widget-settings-config" aria-hidden="true"></i>
								<span class="sui-screen-reader-text">{{ $i18n.settings.buttons.open_options }}</span>
							</button>
							<ul>
								<li>
									<button
										type="button"
										id="google-switch-user"
										data-modal-open="beehive-google-switch-confirm"
									>
										<i class="sui-icon-update" aria-hidden="true"></i>
										{{ $i18n.settings.labels.switch_profile }}
									</button>
								</li>
								<li>
									<button
										type="button"
										id="google-logout-user"
										data-modal-open="beehive-google-logout-confirm"
									>
										<i class="sui-icon-logout" aria-hidden="true"></i>
										{{ $i18n.settings.labels.logout }}
									</button>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</fragment>
</template>

<script>
export default {
	name: 'InfoBar',

	computed: {
		userName: function() {
			const name = this.getOption('name', 'google_login', '')
			if (name) {
				return name
			} else {
				return this.$i18n.settings.labels.no_account_info
			}
		},

		userEmail: function() {
			return this.getOption('email', 'google_login', '')
		},

		userPhoto: function() {
			return this.getOption('photo', 'google_login', '')
		}
	}
}
</script>
