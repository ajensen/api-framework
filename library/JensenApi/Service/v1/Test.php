<?php

/**
 *
 */
class JensenApi_Service_Test extends Api_Service_Base {


	/**
	 * Service to test the API framework and/or methods
	 * 
	 * @var string
	 */
	protected $_name = "Test";
	

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
		$this->addAllowedMethod("execute", Api_Request::METHOD_GET);
		$this->addAllowedMethod("sum", Api_Request::METHOD_GET);
		$this->addAllowedMethod("sum", Api_Request::METHOD_POST);
		$this->addAllowedMethod("fromConfig", Api_Request::METHOD_GET);

	}


	/**
	 * Check if service is active
	 * 
	 * @param array $params Parameters that are submitted
	 * @param array $config Api config
	 */
	public function execute($params, $config) {
		$this->code = 200;
		return;
	}


	/**
	 * Return the sum of two values
	 * 
	 * @param array $params 
	 */
	public function sum($params) {
		
		$this->code = 200;
		
		if ( !isset($params['value1']) || !isset($params['value2']) ) {
			throw new Api_Error('Missing param', 'Make sure to fill in value1 and value2.');
		}

		$results = $params['value1'] + $params['value2'];

		error_log('THE SUM IS ' . $results);
		
		return $results;
		
	}
	
	
	/**
	 * Return the value of the "myvalue" attribute located within the config.ini file
	 * 
	 * @param array $params 
	 */
	public function fromConfig($params, $config) {
		
		$this->code = 200;
		
		return $config['myvalue'];

	}
	
	
}