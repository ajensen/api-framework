<?php

use Jensen\Core;

/**
 *
 */
class JensenApi_Service_Session extends Api_Service_Base {
	

	/**
	 * Retrieving and setting account session information
	 * 
	 * @var string
	 */
	protected $_name = "Session";
	

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
		$this->addAllowedMethod("admin", Api_Request::METHOD_POST);
		$this->addAllowedMethod("check", Api_Request::METHOD_POST);
		$this->addAllowedMethod("enter", Api_Request::METHOD_POST);
		$this->addAllowedMethod("logged_in_info", Api_Request::METHOD_POST);
		$this->addAllowedMethod("logout", Api_Request::METHOD_POST);
		$this->addAllowedMethod("token", Api_Request::METHOD_POST);
		
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
	 * Check if user has an admin account
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function admin($params) {

		$Session 							= new Core\Session($this->_CONN, $this->_REDIS);

		$results 							= ( $Session->getUserType() == 2 ) ? $Session->getUserID() : 0;

		$Session 							= null;

		$this->code 						= 200;

		return $results;		

	}


	/**
	 * Check if user is currently logged in
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function check($params) {

		$Session 							= new Core\Session($this->_CONN, $this->_REDIS);

		$results 							= ( $Session->getUserID() >= 1 ) ? $Session->getUserID() : 0;

		$Session 							= null;

		$this->code 						= 200;

		return $results;

	}


	/**
	 * Log user in via email/pass combination, add cookie for remember_me value
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function enter($params) {


		$email						   		= ( isset($params['email']) ) ? $params['email'] : false;
		$password							= ( isset($params['password']) ) ? $params['password'] : false;
		$remember_me 						= ( isset($params['remember_me']) ) ? $params['remember_me'] : 0;

		$Session 							= new Core\Session($this->_CONN, $this->_REDIS);
		
		$Session->setEmail($email);
		
		$Session->setPassword($password);

		$results 							= $Session->email_login();

		if ( $results ) $Session->set_cookie($remember_me);

		$Session 							= null;

		return ( $results ) ? $results : 0;

	}


	/**
	 * Authenticate user account via token
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function token($params) {


		$userID						   		= ( isset($params['id']) ) ? $params['id'] : false;
		$app_key				 			= ( isset($params['app_key']) ) ? $params['app_key'] : false;

		$Session 							= new Core\Session($this->_CONN, $this->_REDIS);
		
		$Session->setUserID($userID);
		
		$Session->setAppKey($app_key);

		$outcome 							= $Session->check_for_app_user_token(12);

		$results 							= ( $outcome ) ? $Session->getToken() : 0;

		$Session 							= null;

		return $results;

	}


	/**
	 * Retrieve basic user logged in account details
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function logged_in_info($params) {

		$Session 							= new Core\Session($this->_CONN, $this->_REDIS);

		$results 							= $Session->getUserInfo();

		$Session 							= null;

		$this->code 						= 200;

		return $results;		

	}


	/**
	 * Logout user
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function logout($params) {

		$Session 							= new Core\Session($this->_CONN, $this->_REDIS);
	
		$Session->logout();

		$Session 							= null;

		$this->code 						= 200;

		return 1;		

	}


}

