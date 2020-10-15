<?php

/**
 * Manage iThemes Security Pro functionality
 *
 * Provides command line access via WP-CLI: http://wp-cli.org/
 */
class ITSEC_WP_CLI_Command_ITSEC extends WP_CLI_Command {

	/**
	 * Run the upgrade routine.
	 *
	 * ## OPTIONS
	 *
	 * [--build=<build>]
	 * : Manually specify the build number to upgrade from. Otherwise, will pull from current version.
	 *
	 * @param array $args
	 * @param array $assoc_args
	 */
	public function upgrade( $args, $assoc_args ) {

		$build = ! empty( $assoc_args['build'] ) ? $assoc_args['build'] : false;

		$error = ITSEC_Core::get_instance()->handle_upgrade( $build );

		if ( is_wp_error( $error ) ) {
			WP_CLI::error( $error );
		}

		WP_CLI::success( __( 'Upgrade routine completed.', 'it-l10n-ithemes-security-pro' ) );
	}

	/**
	 * Create the database schema.
	 */
	public function schema() {

		$GLOBALS['wpdb']->show_errors();

		require_once( ITSEC_Core::get_core_dir() . 'lib/schema.php' );

		ob_start();
		ITSEC_Schema::create_database_tables();
		$error = ob_get_clean();

		if ( $error ) {
			WP_CLI::error( 'Error creating tables.', false );
			WP_CLI::log( strip_tags( $error ) );
		} else {
			WP_CLI::success( 'Tables created.' );
		}
	}

	/**
	 * Retrieve active lockouts.
	 *
	 * # DEPRECATED
	 *
	 * See the "wp itsec lockout list" command for a replacement.
	 *
	 * @since 1.12
	 *
	 * @return void
	 */
	public function getlockouts() {
		WP_CLI::warning( 'Deprecated. See the "wp itsec lockout list" command for a replacement.' );

		/** @var ITSEC_Lockout $itsec_lockout */
		global $itsec_lockout;

		$host_locks = $itsec_lockout->get_lockouts( 'host' );
		$user_locks = $itsec_lockout->get_lockouts( 'user' );

		if ( empty( $host_locks ) && empty( $user_locks ) ) {

			WP_CLI::success( __( 'There are no current lockouts', 'it-l10n-ithemes-security-pro' ) );

		} else {

			if ( ! empty( $host_locks ) ) {

				foreach ( $host_locks as $index => $lock ) {

					$host_locks[ $index ]['type']           = __( 'host', 'it-l10n-ithemes-security-pro' );
					$host_locks[ $index ]['lockout_expire'] = isset( $lock['lockout_expire'] ) ? human_time_diff( ITSEC_Core::get_current_time(), strtotime( $lock['lockout_expire'] ) ) : __( 'N/A', 'it-l10n-ithemes-security-pro' );

				}

			}

			if ( ! empty( $user_locks ) ) {

				foreach ( $user_locks as $index => $lock ) {

					$user_locks[ $index ]['type']           = __( 'user', 'it-l10n-ithemes-security-pro' );
					$user_locks[ $index ]['lockout_expire'] = isset( $lock['lockout_expire'] ) ? human_time_diff( ITSEC_Core::get_current_time(), strtotime( $lock['lockout_expire'] ) ) : __( 'N/A', 'it-l10n-ithemes-security-pro' );

				}

			}

			$lockouts = array_merge( $host_locks, $user_locks );

			WP_CLI\Utils\format_items( 'table', $lockouts, array( 'lockout_id', 'type', 'lockout_host', 'lockout_username', 'lockout_expire' ) );

		}

	}

	/**
	 * Release a lockout using one or more ID's provided by getlockouts.
	 *
	 * ## DEPRECATED
	 *
	 * See the "wp itsec lockout release" command for a replacement.
	 *
	 * ## OPTIONS
	 *
	 * [<id>...]
	 * : One or more active lockout ID's.
	 *
	 * [--id=<id>]
	 * : An active lockout ID.
	 *
	 * ## EXAMPLES
	 *
	 *     wp itsec releaselockout 14 21
	 *     wp itsec releaselockout --id=83
	 *
	 * @since 1.12
	 *
	 * @param array $args
	 * @param array $assoc_args
	 *
	 * @return void
	 */
	public function releaselockout( $args, $assoc_args ) {
		WP_CLI::warning( 'Deprecated. See the "wp itsec lockout release" command for a replacement.' );

		/** @var ITSEC_Lockout $itsec_lockout */
		global $itsec_lockout;

		$ids = array();

		//make sure they provided a valid ID
		if ( isset( $assoc_args['id'] ) ) {
			$ids[] = $assoc_args['id'];
		} else {
			$ids = $args;
		}

		if ( empty( $ids ) ) {
			WP_CLI::error( __( 'You must supply one or more lockout ID\'s to release.', 'it-l10n-ithemes-security-pro' ) );
		}

		foreach ( $ids as $id ) {
			if ( '' === $id ) {
				WP_CLI::error( __( 'Skipping empty ID.', 'it-l10n-ithemes-security-pro' ) );
			} elseif ( (string) intval( $id ) !== (string) $id ) {
				WP_CLI::error( sprintf( __( 'Skipping invalid ID "%s". Please supply a valid ID.', 'it-l10n-ithemes-security-pro' ), $id ) );
			} elseif ( ! $itsec_lockout->release_lockout( $id ) ) {
				WP_CLI::error( sprintf( __( 'Unable to remove lockout "%s".', 'it-l10n-ithemes-security-pro' ), $id ) );
			} else {
				WP_CLI::success( sprintf( __( 'Successfully removed lockout "%d".', 'it-l10n-ithemes-security-pro' ), $id ) );
			}
		}
	}

	/**
	 * List the most recent log items
	 *
	 * ## DEPRECATED
	 *
	 * See the "wp itsec log list" command for a replacement.
	 *
	 * ## OPTIONS
	 *
	 * [<count>]
	 * : The number of log items to display.
	 * ---
	 * default: 10
	 * ---
	 *
	 * [--count=<count>]
	 * : The number of log items to display.
	 * ---
	 * default: 10
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp itsec getrecent 20
	 *     wp itsec getrecent --count=50
	 *
	 * @since 1.12
	 *
	 * @param array $args
	 * @param array $assoc_args
	 *
	 * @return void
	 */
	public function getrecent( $args, $assoc_args ) {
		WP_CLI::warning( 'Deprecated. See the "wp itsec log list" command for a replacement.' );

		if ( isset( $assoc_args['count'] ) && 10 != $assoc_args['count'] ) {
			$count = intval( $assoc_args['count'] );
		} elseif ( isset( $args[0] ) && 10 != $args[0] ) {
			$count = intval( $args[0] );
		} else {
			$count = 10;
		}

		$entries = ITSEC_Log::get_entries( array(), $count );

		if ( ! is_array( $entries ) || empty( $entries ) ) {

			WP_CLI::success( __( 'The Security logs are empty.', 'it-l10n-ithemes-security-pro' ) );

		} else {

			foreach ( $entries as $index => $entry ) {
				if ( '' === $entry['user_id'] ) {
					$username = '';
				} else {
					$user = get_user_by( 'id', $entry['user_id'] );

					if ( false === $user ) {
						$username = '';
					} else {
						$username = $user->user_login;
					}
				}

				$entries[ $index ] = array(
					'Time'     => sprintf( esc_html__( '%s ago', 'it-l10n-ithemes-security-pro' ), human_time_diff( ITSEC_Core::get_current_time_gmt(), strtotime( $entry['timestamp'] ) ) ),
					'Code'     => $entry['code'],
					'Type'     => $entry['type'],
					'IP'       => $entry['remote_ip'],
					'Username' => $username,
					'URL'      => $entry['url'],
				);

			}

			WP_CLI\Utils\format_items( 'table', $entries, array( 'Time', 'Code', 'Type', 'IP', 'Username', 'URL' ) );

		}

	}

	/**
	 * Analyse the User-Agent with the Browser library.
	 *
	 * ## OPTIONS
	 *
	 * <user-agent>
	 * : The User-Agent string.
	 *
	 * [--fields=<fields>]
	 * : Limit the output to specific object fields.
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: table
	 * options:
	 *  - table
	 *  - json
	 *  - csv
	 *  - yaml
	 */
	public function browser( $args, $assoc_args ) {

		list( $agent ) = $args;

		require_once( ITSEC_Core::get_core_dir() . 'lib/class-itsec-lib-browser.php' );

		$browser = new ITSEC_Lib_Browser( $agent );

		$assoc_args = wp_parse_args( $assoc_args, array(
			'fields' => array( 'browser', 'version', 'platform', 'robot', 'mobile', 'tablet' ),
			'format' => 'table'
		) );

		$formatter = new WP_CLI\Formatter( $assoc_args );
		$formatter->display_item( array(
			'browser'  => $browser->getBrowser(),
			'version'  => $browser->getVersion(),
			'platform' => $browser->getPlatform(),
			'robot'    => $browser->isRobot(),
			'mobile'   => $browser->isMobile(),
			'tablet'   => $browser->isTablet(),
			'aol'      => $browser->isAol(),
			'facebook' => $browser->isFacebook(),
		) );
	}

	/**
	 * Evaluates a password's strength.
	 *
	 * ## OPTIONS
	 *
	 * <password>
	 * : The password.
	 *
	 * [--fields=<fields>]
	 * : Limit the output to specific object fields.
	 *
	 * [--field=<field>]
	 * : Instead of returning the whole results, returns the value of a single field.
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: table
	 * options:
	 *  - table
	 *  - json
	 *  - csv
	 *  - yaml
	 */
	public function zxcvbn( $args, $assoc_args ) {
		$results   = ITSEC_Lib::get_password_strength_results( $args[0] );
		$formatted = [
			'score'       => $results->score,
			'guesses'     => $results->guesses,
			'calc_time'   => $results->calc_time,
			'warning'     => $results->feedback->warning,
			'suggestions' => $results->feedback->suggestions,
			'times'       => $results->crack_times_display,
			'sequence'    => $results->sequence,
		];

		$foramtter = new \WP_CLI\Formatter( $assoc_args, array_keys( $formatted ) );
		$foramtter->display_item( $formatted );
	}

	/**
	 * Generates a random password.
	 *
	 * ## OPTIONS
	 *
	 * [<length>]
	 * : The password length. Defaults to 32 characters long.
	 *
	 * @subcommand generate-password
	 */
	public function generate_password( $args ) {
		$length = isset( $args[0] ) ? $args[0] : 32;

		WP_CLI::log( wp_generate_password( $length ) );
	}

	/**
	 * Changes the "admin" username and/or the user id of 1.
	 *
	 * ## OPTIONS
	 *
	 * [--username=<username>]
	 * : The username to change "admin" to.
	 *
	 * [--change-id]
	 * : Whether to change the user id of 1.
	 *
	 * @subcommand change-admin-user
	 */
	public function change_admin_user( $args, $assoc_args ) {
		$username  = \WP_CLI\Utils\get_flag_value( $assoc_args, 'username' );
		$change_id = \WP_CLI\Utils\get_flag_value( $assoc_args, 'change-id', false );

		if ( ! $username && ! $change_id ) {
			WP_CLI::error( 'Must include --username or --change-id.' );
		}

		$changed = itsec_change_admin_user( $username, $change_id );

		if ( $changed ) {
			WP_CLI::success( 'Updated.' );
		} else {
			WP_CLI::error( 'The user was unable to be successfully updated. This could be due to a plugin or server configuration conflict.' );
		}
	}

	/**
	 * Scaffold a JavaScript entry.
	 *
	 * ## OPTIONS
	 *
	 * <module>
	 * : The module the entry is for. Currently only supports "pro" modules.
	 *
	 * <name>
	 * : The name of the entry.
	 *
	 * @subcommand scaffold-entry
	 */
	public function scaffold_entry( $args, $assoc_args ) {

		$itsec_dir = ITSEC_Core::get_plugin_dir();

		list( $module, $name ) = $args;

		if ( file_exists( $itsec_dir . 'pro/' . $module ) ) {
			$filename = $itsec_dir . 'pro/' . $module;
		} elseif ( file_exists( $itsec_dir . 'core/modules/' . $module ) ) {
			$filename = $itsec_dir . 'core/modules/' . $module;
		} else {
			WP_CLI::error( 'Invalid module. Module directory not found.' );
		}

		if ( ! preg_match( '/[a-z]+/', $name ) ) {
			WP_CLI::error( 'Invalid entry name. Only lower-case alpha allowed.' );
		}

		$entries_dir = "{$filename}/entries/";
		$entry_dir   = "{$entries_dir}/{$name}";

		if ( ! wp_mkdir_p( $entry_dir ) ) {
			WP_CLI::error( "Failed to create directory: '{$entry_dir}'" );
		}

		$files = array();

		$files["{$entries_dir}/{$name}.js"] = <<<'JS'
/**
 * WordPress dependencies
 */
import { setLocaleData } from '@wordpress/i18n';
import { render } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';

// Silence warnings until JS i18n is stable.
setLocaleData( { '': {} }, 'ithemes-security-pro' );

/**
 * Internal dependencies
 */
import App from './{{name}}/app.js';

domReady( () => render( <App />, document.getElementById( 'itsec-{{name}}-root' ) ) );

JS;

		$files["{$entry_dir}/app.js"] = <<<'JS'
/**
 * Internal dependencies
 */
import './style.scss';

export default function App() {
	return <p>Welcome to {{name}}.</p>;
}

JS;

		$files[] = "{$entry_dir}/style.scss";

		foreach ( $files as $file => $template ) {
			if ( is_int( $file ) ) {
				$file     = $template;
				$template = '';
			}

			if ( ! $fh = fopen( $file, 'wb' ) ) {
				WP_CLI::warning( "Failed to create file: {$file}" );
				continue;
			}

			if ( $template && class_exists( 'Mustache_Engine' ) ) {
				$m = new \Mustache_Engine( array(
					'escape' => function ( $val ) { return $val; },
				) );

				$content = $m->render( $template, compact( 'name', 'module' ) );

				if ( ! fwrite( $fh, $content ) ) {
					WP_CLI::warning( "Failed to write file content to: {$file}" );
				}
			}
		}

		WP_CLI::success( 'Entry created.' );
	}
}
