<?php

class Blitz_Copy_Media_File_To_Network_Sites {
	
	/**
	 * Constructor
	*/
	public function __construct() {
		
		$this->hooks();
		
	}
	
	
	
	/**
	 * Initiate hooks
	*/
	public function hooks() {
		
		//add_action( 'update_option_my_options_page_key', array( $this, 'insert_media_file_into_sites' ), 10, 2 );
		
	}
	
	
	
	/**
	 * Insert media fiels to sites
	*/
	public function insert_media_file_into_sites( $attachment_id, $site_id) {
		
		if ( ! $attachment_id ) {
			return;
		}
		
		switch_to_blog(1);
		$file_path       = get_attached_file( $attachment_id );
		$file_url        = wp_get_attachment_url( $attachment_id );
		$file_type_data  = wp_check_filetype( basename( $file_path ), null );
		$file_type       = $file_type_data['type'];
		restore_current_blog();
		
		$timeout_seconds = 5;
		
			if($site_id != 1) { //if not the main site
				switch_to_blog( $site_id );
				$sideload_result = $this->sideload_media_file( $file_url, $file_type, $timeout_seconds );
				if ( ! $sideload_result || ! empty( $sideload_result['error'] ) ) {
					restore_current_blog();
				}
				$new_file_path = $sideload_result['file'];
				$new_file_type = $sideload_result['type'];
				
				// Insert media file into uploads directory.
				$inserted_attachment_id = $this->insert_media_file( $new_file_path, $new_file_type );
				restore_current_blog();
			}
			return $inserted_attachment_id;
			
	}
	
	
	
	/**
	 * Temp file storage of media file
	*/
	public function sideload_media_file( $file_url, $file_type, $timeout_seconds ) {
		
		
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once(ABSPATH . 'wp-admin/includes/image.php');
    
		// Download file to temp dir.
		$temp_file = download_url( $file_url, $timeout_seconds );
		
		if ( is_wp_error( $temp_file ) ) {
			return false;
		}
		// Array based on $_FILE as seen in PHP file uploads.
		$file = array(
			'name'     => basename( $file_url ), 
			'type'     => $file_type,
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize( $temp_file ),
		);
		
		$overrides = array(
			'test_form'   => false,
			'test_size'   => true,
			'test_upload' => true,
		);
		return wp_handle_sideload( $file, $overrides );
		
		
	}
	
	
	
	/**
	 * Insert media file
	*/
	public function insert_media_file( $file_path = '', $file_type = '' ) {
		
		if ( ! $file_path || ! $file_type ) {
			return;
		}
		$wp_upload_dir = wp_upload_dir();
		
		$attachment_data = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $file_path ),
			'post_mime_type' => $file_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		
		$inserted_attachment_id   = wp_insert_attachment( $attachment_data, $file_path );
		$inserted_attachment_path = get_attached_file( $inserted_attachment_id );
		
		$this->update_inserted_attachment_metadata( $inserted_attachment_id, $inserted_attachment_path );
		return $inserted_attachment_id;
		
	}
	
	
	
	/**
	 * Update inserted attachment metadata
	*/
	public function update_inserted_attachment_metadata( $inserted_attachment_id, $file_path ) {
		
		
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
		$attach_data = wp_generate_attachment_metadata( $inserted_attachment_id, $file_path );
		wp_update_attachment_metadata( $inserted_attachment_id, $attach_data );
		
	}
	
	
	/**
	 * Get all sites in network
	*/
	public function prefix_get_sites_in_network() 
	{
		
		$sites = get_sites();   //get all blog sites
		foreach ( $sites as $site ) {
			$blog_details = get_blog_details( $site->blog_id );
			switch_to_blog( $site->blog_id );
			$blogs[$blog_details->blog_id] = $blog_details->blogname;
			restore_current_blog();
		}
		return $blogs;
		
	}
	
}


?>
