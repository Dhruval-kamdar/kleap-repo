<?php

namespace FSPoster\App\Pages\Accounts\Controllers;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Request;

class Main
{
	private function load_assets ()
	{
		wp_register_script( 'fsp-accounts', Pages::asset( 'Accounts', 'js/fsp-accounts.js' ), [
			'jquery',
			'fsp'
		], NULL );
		wp_enqueue_script( 'fsp-accounts' );

		wp_enqueue_style( 'fsp-accounts', Pages::asset( 'Accounts', 'css/fsp-accounts.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();

		$activeTab = Request::get( 'tab', 'fb', 'string' );
		$fsp_accountsCount = Pages::action( 'Accounts', 'get_counts' );

		if ( $activeTab === 'telegram' )
		{
			$button_text = fsp__( 'ADD A BOT' );
			$err_text    = fsp__( 'bots' );
		}
		else if ( $activeTab === 'wordpress' )
		{
			$button_text = fsp__( 'ADD A SITE' );
			$err_text    = fsp__( 'sites' );
		}
		else
		{
			$button_text = fsp__( 'ADD AN ACCOUNT' );
			$err_text    = fsp__( 'accounts' );
		}

		Pages::view( 'Accounts', 'index', [
			'accounts_count' => $fsp_accountsCount,
			'active_tab'     => $activeTab,
			'button_text'    => $button_text
		] );
	}
}