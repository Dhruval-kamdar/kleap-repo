<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\ListResource;
use Twilio\Rest\Api\V2010\Account\Recording\AddOnResultContext;
use Twilio\Rest\Api\V2010\Account\Recording\AddOnResultList;
use Twilio\Rest\Api\V2010\Account\Recording\TranscriptionList;
use Twilio\Values;
use Twilio\Version;

/**
 * @property TranscriptionList transcriptions
 * @property AddOnResultList addOnResults
 * @method \Twilio\Rest\Api\V2010\Account\Recording\TranscriptionContext transcriptions(string $sid)
 * @method AddOnResultContext addOnResults(string $sid)
 */
class RecordingContext extends InstanceContext {
    protected $_transcriptions = null;
    protected $_addOnResults = null;

    /**
     * Initialize the RecordingContext
     * 
     * @param Version $version Version that contains the resource
     * @param string $accountSid The unique sid that identifies this account
     * @param string $sid Fetch by unique recording SID
     * @return RecordingContext
     */
    public function __construct(Version $version, $accountSid, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('accountSid' => $accountSid, 'sid' => $sid, );

        $this->uri = '/Accounts/' . rawurlencode($accountSid) . '/Recordings/' . rawurlencode($sid) . '.json';
    }

    /**
     * Fetch a RecordingInstance
     * 
     * @return RecordingInstance Fetched RecordingInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new RecordingInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['sid']
        );
    }

    /**
     * Deletes the RecordingInstance
     * 
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     *
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get($name) {
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     *
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call($name, $arguments) {
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Api.V2010.RecordingContext ' . implode(' ', $context) . ']';
    }

    /**
     * Access the transcriptions
     *
     * @return TranscriptionList
     */
    protected function getTranscriptions() {
        if (!$this->_transcriptions) {
            $this->_transcriptions = new TranscriptionList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['sid']
            );
        }

        return $this->_transcriptions;
    }

    /**
     * Access the addOnResults
     *
     * @return AddOnResultList
     */
    protected function getAddOnResults() {
        if (!$this->_addOnResults) {
            $this->_addOnResults = new AddOnResultList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['sid']
            );
        }

        return $this->_addOnResults;
    }
}