<?php

namespace WeDevs\ERP\Mailchimp;

class Mailchimp {
    /**
     * HTTP Request Client.
     */
    protected $request;

    /**
     * Class Contructor.
     *
     * @param string $apikey
     */
    public function __construct( $api_key ) {
        $this->api_key = $api_key;
        $base_url = 'https://us1.api.mailchimp.com/3.0/';

        $dc = 'us1';

        if ( strstr( $api_key, '-' ) ) {
            list( $key, $dc ) = explode( '-', $api_key, 2 );
            if ( ! $dc ) {
                $dc = 'us1';
            }
        }

        $base_url = str_replace( 'us1', $dc, $base_url );

        $this->request = new Http_Client( $base_url );
    }

    /**
     * Ping Mailchimp if connected.
     *
     * @return boolean
     */
    public function is_connected() {
        $response = $this->request->get( '/', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'apikey ' . $this->api_key
            ],
        ] );

        $response_code = $response->response_code();
        if ( $response_code === 200 ) {
            return true;
        }

        return false;
    }

    /**
     * Get all lists.
     *
     * @return array
     */
    public function get_lists() {
        $response = $this->request->get( 'lists?fields=lists.id,lists.name,lists.stats.member_count', [
            'headers' => [
                'Authorization' => 'apikey ' . $this->api_key
            ],
        ] );

        return $response->to_array();
    }

    /**
     * Check if member exist or not in a list.
     *
     * @param  string $list_id
     * @param  string $email
     *
     * @return boolean
     */
    public function member_exists( $list_id, $email ) {
        $response = $this->request->get( 'lists/' . $list_id . '/members/' . md5( $email ), [
            'headers' => [
                'Authorization' => 'apikey ' . $this->api_key,
            ]
        ] );

        $response_code = $response->response_code();
        if ( $response_code === 200 ) {
            return true;
        }

        return false;
    }

    /**
     * Subscribe email to a list.
     *
     * @param  string $list_id
     * @param  string $email
     * @param  string $first_name
     * @param  string $last_name
     *
     * @return array
     */
    public function subscribe_to_list( $list_id, $email, $first_name, $last_name ) {
        $params = [
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $first_name,
                'LNAME' => $last_name,
            ],
        ];

        $response = $this->request->post( 'lists/' . $list_id . '/members', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->api_key,
            ],
            'body' => json_encode( $params ),
        ], false );

        return true;
    }

    /**
     * Batch Subscribe email to a list.
     *
     * @param  string $list_id
     * @param  string $data
     *
     * @return array
     */
    public function batch_subscribe_to_list( $list_id, $data ) {
        $operations = [];
        $x = 0;
        foreach ( $data as $item ) {
            $operations['operations'][$x] = [
                'method' => 'POST',
                'path' => 'lists/' . $list_id . '/members',
                'body' => json_encode([
                    'email_address' => $item['email'],
                    'status' => 'subscribed',
                    'merge_fields' => [
                        'FNAME' => $item['first_name'],
                        'LNAME' => $item['last_name'],
                    ],
                ]),
            ];

            $x++;
        }

        $response = $this->request->post( 'batches', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->api_key,
            ],
            'body' => json_encode( $operations ),
        ], false );

        return true;
    }

    /**
     * Get Subscribed members from a list.
     *
     * @param  string $list_id
     * @param  string $offset  (optional)
     *
     * @return array
     */
    public function get_subscribed_members( $list_id, $offset = 0 ) {
        $limit = 50;

        if ( $offset > 0 ) {
            $offset = '&offset=' . $offset;
        } else {
            $offset = '';
        }

        $response = $this->request->get( 'lists/' . $list_id . '/members?count=' . $limit . $offset, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->api_key,
            ],
        ] );

        return $response->to_array();
    }
}
