<?php

namespace FSPoster\App\Providers;

trait PluginMenu
{
	public function initMenu ()
	{
		add_action( 'init', function () {
			$this->getNotifications();
			$res1 = $this->checkLicense();
			if ( FALSE === $res1 )
			{
				return;
			}

			$plgnVer = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

			if ( Helper::isHiddenUser() )
			{
				return;
			}

			if ( empty( $plgnVer ) )
			{
				add_action( 'admin_menu', function () {
					add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', function () {
						Pages::controller( 'Base', 'App', 'install' );
					}, Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
				} );

				return;
			}
			else
			{
				if ( $plgnVer != Helper::getVersion() )
				{
					$fsPurchaseKey = Helper::getOption( 'poster_plugin_purchase_key', '', TRUE );

					if ( $fsPurchaseKey != '' )
					{
						$result = Ajax::updatePlugin( $fsPurchaseKey );
						if ( $result[ 0 ] == FALSE )
						{
							add_action( 'admin_menu', function () {
								add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', function () {
									Pages::controller( 'Base', 'App', 'update' );
								}, Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
							} );

							return;
						}
					}
					else
					{
						add_action( 'admin_menu', function () {
							add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', function () {
								Pages::controller( 'Base', 'App', 'update' );
							}, Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
						} );

						return;
					}
				}
			}

			add_action( 'admin_menu', function () {
				add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', [
					Pages::class,
					'load_page'
				], Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );

				add_submenu_page( 'fs-poster', fsp__( 'Dashboard' ), fsp__( 'Dashboard' ), 'read', 'fs-poster', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', fsp__( 'Accounts' ), fsp__( 'Accounts' ), 'read', 'fs-poster-accounts', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', fsp__( 'Schedules' ), fsp__( 'Schedules' ), 'read', 'fs-poster-schedules', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', fsp__( 'Direct Share' ), fsp__( 'Direct Share' ), 'read', 'fs-poster-share', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', fsp__( 'Logs' ), fsp__( 'Logs' ), 'read', 'fs-poster-logs', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', fsp__( 'Apps' ), fsp__( 'Apps' ), 'read', 'fs-poster-apps', [
					Pages::class,
					'load_page'
				] );

				if ( current_user_can( 'administrator' ) )
				{
					add_submenu_page( 'fs-poster', fsp__( 'Settings' ), fsp__( 'Settings' ), 'read', 'fs-poster-settings', [
						Pages::class,
						'load_page'
					] );
				}
			} );
		} );
	}

	public function app_disable ()
	{
		register_uninstall_hook( FS_ROOT_DIR . '/init.php', [ Helper::class, 'removePlugin' ] );

		Helper::deleteOption( 'poster_plugin_installed', TRUE );

		Pages::controller( 'Base', 'App', 'disable' );
	}

	public function getNotifications ()
	{
		//bugs
	}

	public function checkLicense ()
	{
		return;
	}
}