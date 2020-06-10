<?php

use Jensen\Core;

/**
 *
 */
class JensenApi_Service_Validate extends Api_Service_Base {
	

	/**
	 * Validating attributes service
	 * ex: validates meeting minimum requirements, proper format, etc.
	 * 
	 * @var string
	 */
	protected $_name = "Validate";

	
	/**
	 * Construct
	 * 
	 * @param Api $api
	 */
	public function __construct($api) {

		parent::__construct($api);

		// call this to establish proper connectections
		// do not use if your route does not require db connections
		$this->connect();

		// Set request methods
		$this->addAllowedMethod("execute", Api_Request::METHOD_POST);
		$this->addAllowedMethod("pass", Api_Request::METHOD_POST);
		$this->addAllowedMethod("check", Api_Request::METHOD_POST);
		
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
	 * Checks if password meets minimum requirements
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function pass($params) {

		$password 							= ( isset($params['pass']) ) ? $params['pass'] : false;

		if ( !$password ) {
			
			$this->code 					= 417;

			return 0;

		}

		$Validate 							= new Core\Validate($this->_CONN, $this->_REDIS);

		$results 							= $Validate->check_password_strength($password);

		$Validate 							= null;

		$this->code 						= 200;

		return $results;		

	}


	/**
	 * Checks if value is provided in the correct format required for the "check" attribute
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function check($params) {

		$check 								= ( isset($params['check']) ) ? $params['check'] : false;
		$value 								= ( isset($params['value']) ) ? $params['value'] : false;

		error_log(json_encode($params));

		if ( !$check || !$value ) {
			
			$this->code 					= 417;

			return 0;

		}

		$Validate 							= new Core\Validate($this->_CONN, $this->_REDIS);


		if ( $check == 'email' ) {
		
			$results 						= $Validate->check_email($value);
		
		} else if ( $check == 'phone' || $check == 'mobile' ) {
		
			$results 						= $Validate->check_phone($value);
		
		} else if ( $check == 'zipcode' || $check == 'postal' ) {
		
			$results 						= $Validate->check_zipcode($value);
		
		} else if ( $check == 'url' ) {
		
			$results 						= $Validate->check_URL($value);
		
		} else if ( $check == 'password' ) {
		
			$results 						= $Validate->check_password_rule($value);
		
		} else if ( $check == 'firstname' ) {
		
			$results 						= $Validate->check_firstname_rule($value);
		
		} else if ( $check == 'lastname' ) {
		
			$results 						= $Validate->check_lastname_rule($value);
		
		} else if ( $check == 'fullname' ) {
		
			$results 						= $Validate->check_fullname_rule($value);
		
		} else if ( $check == 'cc_num' ) {
		
			$results 						= $Validate->check_cc_num_rule($value);
		
		} else if ( $check == 'cvc' ) {
		
			$results 						= $Validate->check_cvc_rule($value);
		
		} else if ( $check == 'cc_exp' ) {
		
			$results 						= $Validate->check_cc_exp_rule($value);
		
		} else if ( $check == 'word' ) {
			
			$results 						= $Validate->check_word_rule($value);
		
		} else {
			
			$results 						= $Validate->check_word_rule($value);

		}


		$Validate 							= null;

		$this->code 						= 200;

		return $results;

	}


}

