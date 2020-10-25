<?php
/**
 * A lightweight class for making xml-rpc requests to a remote
 */

class PH_XML_Request {
	/**
	 * The username to send for basic authentication
	 *
	 * @var string
	 */
	private $username = '';

	/**
	 * The password to send for basic authentication
	 *
	 * @var string
	 */
	private $password = '';

	/**
	 * The endpoint to make the request
	 *
	 * @var string
	 */
	private $endpoint = '';

	/**
	 * Requres a username and password for the request on init
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $endpoint, $username, $password ) {
		$this->username = $username;
		$this->password = $password;
		$this->endpoint = $endpoint;
	}

	/**
	* Run xmlrpc CURL request
	*
	* @param string $request
	* @param array $params
	*
	* @return void
	*/
	public function request( $request, $params = array() ) {
		if ( ! function_exists( 'xmlrpc_encode_request' ) ) {
			return new WP_Error( 'xmlrpc_extension_missing', __( 'Your server cannot make connections to remote sites. Try manually connecting instead.', 'project-huddle' ) );
		}

		$ch = curl_init();
		// set post fields
		curl_setopt(
			$ch,
			CURLOPT_POSTFIELDS,
			xmlrpc_encode_request(
				$request,
				array( 0, $this->username, $this->password, $params ),
				array(
					'encoding' => 'UTF-8',
					'escaping' => 'markup',
					'version'  => 'xmlrpc',
				)
			)
		);

		// set url and options
		curl_setopt( $ch, CURLOPT_URL, $this->endpoint );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // added for non-ssl connections
		curl_setopt( $ch, CURLOPT_TIMEOUT, 1 );
		$results = curl_exec( $ch );

		// process possible curl errors
		$this->curl_errors( $ch );

		// close curl
		curl_close( $ch );

		// return decoded results
		return xmlrpc_decode( $results, 'UTF-8' );
	}

	/**
	 * Process and send CURL errors
	 *
	 * @param CURL $ch
	 * @return void
	 */
	public function curl_errors( $ch ) {
		if ( curl_errno( $ch ) ) {
			curl_close( $ch );
			return new WP_Error( curl_errno( $ch ), print_r( $ch, 1 ) );
		}
	}
}
