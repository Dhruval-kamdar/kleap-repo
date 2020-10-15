<?php
namespace WeDevs\ERP\CRM\Deals;

/**
 * Class for frontend link manipulation
 *
 * @since 1.0.0
 */
class Frontend {

    /**
     * erp-deals-attachment-hash meta
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $hash;

    /**
     * The class constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        if ( isset( $_GET['download-deal-attachment'] ) && !empty( $_GET['download-deal-attachment'] ) ) {
            $this->hash = $_GET['download-deal-attachment'];
            $this->init();
        }
    }

    /**
     * Initialize the process
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function init() {
        $args = [
            'post_type'     => 'attachment',
            'post_status'   => 'inherit',
            'meta_key'      => 'erp-deals-attachment-hash',
            'meta_value'    => $this->hash,
            'meta_compare'  => '='
        ];

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            $post = array_pop( $query->posts );

            $upload_dirs = wp_upload_dir();
            $file = get_post_meta( $post->ID, '_wp_attached_file', true );
            $file_path = $upload_dirs['basedir'] . '/' . $file;

            $filename = basename( $file_path );
            $this->download_file( $file_path, $filename );
        }

        wp_reset_postdata();
    }

    /**
     * Download file
     *
     * @since 1.0.0
     *
     * @param string $file_path
     * @param string $filename
     *
     * @return void
     */
    public function download_file( $file_path, $filename ) {
        if ( !file_exists( $file_path ) ) {
            wp_die( __( 'File not found', 'erp-pro' ), '', array( 'response' => 404 ) );
        }

        $this->download_headers( $file_path, $filename );

        $this->readfile_chunked( $file_path );

        exit;
    }

    /**
     * Get content type of a download.
     *
     * @since 1.0.0
     *
     * @param string $file_path
     *
     * @return string
     */
    private function get_download_content_type( $file_path ) {
        $file_extension  = strtolower( substr( strrchr( $file_path, "." ), 1 ) );
        $ctype           = "application/force-download";

        foreach ( get_allowed_mime_types() as $mime => $type ) {
            $mimes = explode( '|', $mime );
            if ( in_array( $file_extension, $mimes ) ) {
                $ctype = $type;
                break;
            }
        }

        return $ctype;
    }

    /**
     * Set headers for the download.
     *
     * @since 1.0.0
     *
     * @param  string $file_path
     * @param  string $filename
     *
     * @return void
     */
    private function download_headers( $file_path, $filename ) {
        $this->check_server_config();
        $this->clean_buffers();
        nocache_headers();

        header( "X-Robots-Tag: noindex, nofollow", true );
        header( "Content-Type: " . $this->get_download_content_type( $file_path ) );
        header( "Content-Description: File Transfer" );
        header( "Content-Disposition: attachment; filename=\"" . $filename . "\";" );
        header( "Content-Transfer-Encoding: binary" );

        if ( $size = @filesize( $file_path ) ) {
            header( "Content-Length: " . $size );
        }
    }

    /**
     * Check and set certain server config variables to ensure downloads work as intended.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function check_server_config() {
        if ( function_exists( 'set_time_limit' ) && false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) && ! ini_get( 'safe_mode' ) ) {
            @set_time_limit( 0 );
        }

        if ( function_exists( 'get_magic_quotes_runtime' ) && get_magic_quotes_runtime() && version_compare( phpversion(), '5.4', '<' ) ) {
            set_magic_quotes_runtime( 0 );
        }
        if ( function_exists( 'apache_setenv' ) ) {
            @apache_setenv( 'no-gzip', 1 );
        }
        @ini_set( 'zlib.output_compression', 'Off' );
        @session_write_close();
    }

    /**
     * Clean all output buffers.
     *
     * Can prevent errors, for example: transfer closed with 3 bytes remaining to read.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function clean_buffers() {
        if ( ob_get_level() ) {
            $levels = ob_get_level();
            for ( $i = 0; $i < $levels; $i++ ) {
                @ob_end_clean();
            }
        } else {
            @ob_end_clean();
        }
    }

    /**
     * readfile_chunked.
     *
     * Reads file in chunks so big downloads are possible without changing PHP.INI
     * http://codeigniter.com/wiki/Download_helper_for_large_files/.
     *
     * @since 1.0.0
     *
     * @param string $file
     *
     * @return boolean Success or fail
     */
    public function readfile_chunked( $file ) {
        $chunksize = 1024 * 1024;
        $handle    = @fopen( $file, 'r' );

        if ( false === $handle ) {
            return false;
        }

        while ( ! @feof( $handle ) ) {
            echo @fread( $handle, $chunksize );

            if ( ob_get_length() ) {
                ob_flush();
                flush();
            }
        }

        return @fclose( $handle );
    }

}

new Frontend();
