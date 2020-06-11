<?php

use Jensen\Event;

/**
 *
 */
class JensenApi_Service_Events extends Api_Service_Base {
	

	/**
	 * Events logging service
	 * 
	 * @var string
	 */
	protected $_name = "Events";
	

	/**
	 * Construct
	 * 
	 * @param Api $api
	 */
	public function __construct($api) {

		parent::__construct($api);
		//call this to establish proper connectections
		//do not use if your route does not require db connections
		$this->connect();
		
		// Set request methods
		$this->addAllowedMethod("execute", Api_Request::METHOD_POST);
		$this->addAllowedMethod("log_event", Api_Request::METHOD_POST);
		
	}


	/**
	 * Check if service is active
	 * 
	 * @param array $params	Parameters that are submitted
	 * @param array $config	Api config
	 */
	public function execute($params, $config) {
		$this->code = 200;
		return;
	}


	/**
	 * Log app event 
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function log_event($params) {


		$remoteIP	   							= ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '';

		if ( !(isset($params['event_url'])) ) {

			$this->code 						= 400;

			return 'Missing the event_url.';

		}

		if ( $params['event_url'] == '' ) {

			$this->code 						= 400;

			return 'Missing the event_url.';

		}


		if ( !(isset($params['eventID'])) && !(isset($params['event'])) ) {

			$this->code 						= 400;

			return 'Missing event declaration.';

		}

		$Event 									= new Event\Event($this->_CONN, $this->_REDIS, $_SESSION['userID'], 3, $remoteIP);

		if ( !$Event->set_appID($params['key'], $params['token']) ) {

			$this->code 						= 400;

			return 'Error processing.';

		}

		if ( isset($params['eventID']) ) {

			if ( $params['eventID'] <= 1000 ) {

				$this->code 					= 400;

				return 'Improper eventID entered.';

			}

			$Event->setEventTypeID($params['eventID']);

		} else {

			if ( $params['event'] == '' ) {

				$this->code 					= 400;

				return 'Missing event declaration.';

			}

			$Event->setEventType($params['event']);

			if ( !$Event->api_eventID_exists() ) {

				$eventID 						= $Event->add_api_event_type($params['description']);

			}

			if ( !isset($params['event_url']) && $Event->getEventTypeID() < 1000 ) {

				$this->code 					= 400;

				return 'Invalid event declaration.';

			}


		}

		$Event->setEventUrl($params['event_url']);

		$results 								= $Event->insert_event();

		$this->code 							= 200;

		return $results;

	}

	
}

