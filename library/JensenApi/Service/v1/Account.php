<?php

use Jensen\Account;
use Jensen\Core;

/**
 *
 */
class JensenApi_Service_Account extends Api_Service_Base {


	/**
	 * Service for user account details
	 * 
	 * @var string
	 */
	protected $_name = "Account";
	
	
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
		$this->addAllowedMethod("settings", Api_Request::METHOD_POST);
		$this->addAllowedMethod("update_attribute", Api_Request::METHOD_POST);
		$this->addAllowedMethod("update_setting", Api_Request::METHOD_POST);
		$this->addAllowedMethod("update_pass", Api_Request::METHOD_POST);
		$this->addAllowedMethod("photo", Api_Request::METHOD_POST);
		$this->addAllowedMethod("photo_rotate", Api_Request::METHOD_POST);
		
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
	 * Get user settings
	 * ex. user privacy options, notification options
	 * 
	 * @param array $params	Parameters that are submitted
	 */
	public function settings($params) {

		$userID								= ( isset($params['userID']) ) ? (int)$params['userID'] : false;

		if ( !$userID ) {
			$this->code 					= 417;
			return 0;
		}

		$Settings 							= new Account\Settings($this->_CONN, $this->_REDIS, $userID);

		$user_privacyID 					= $Settings->get_tableID('user_privacy');

		if ( !$user_privacyID ) {

			$insert 						= $Settings->initial_user_settings();

			if ( !$insert ) {
				
				$this->code 				= 417;

				$Settings 					= null;

				return 2;

			}

		}

		$results 							= $Settings->get_full_user_settings();

		$Settings 							= null;

		$this->code 						= 200;

		return $results;		

	}


	/**
	 * Update main user attribute 
	 * ex: email, firstname, lastname, phone
	 * @param array $params	Parameters that are submitted
	 */
	public function update_attribute($params) {


		$userID								= ( isset($params['userID']) ) ? (int)$params['userID'] : false;
		$attr								= ( isset($params['attr']) ) ? $params['attr'] : false;
		$entry								= ( isset($params['entry']) ) ? $params['entry'] : false;

		if ( !$userID ) {
			$this->code 					= 417;
			return 0;
		}

		if ( !$attr ) {
			
			$this->code 					= 417;

			return 4;

		}

		$User 								= new Account\User($this->_CONN, $this->_REDIS, $userID);

		if ( $attr == 'email' || $attr == 'firstname' || $attr == 'lastname' ) {
			$results 						= $User->update_attribute($entry, $attr);
		}


		$User 								= null;

		$this->code 						= 200;

		return $results;

	}


	/**
	 * Update user setting
	 * @param array $params	Parameters that are submitted
	 */
	public function update_setting($params) {

		$userID								= ( isset($params['userID']) ) ? (int)$params['userID'] : false;
		$key								= ( isset($params['key']) ) ? $params['key'] : false;
		$value								= ( isset($params['val']) ) ? $params['val'] : false;

		if ( !$userID ) {
			$this->code 					= 417;
			return 0;
		}

		$Settings 							= new Account\Settings($this->_CONN, $this->_REDIS, $userID);

		$result 							= $Settings->get_table_column($key);

		if ( !$result ) {
			
			$this->code 					= 417;

			return 3;

		}

		$results 							= $Settings->update_setting($result['table'], $result['column'], $value);

		$Settings 							= null;

		$this->code 						= 200;

		return $results;

	}


	/**
	 * Update user password
	 * @param array $params	Parameters that are submitted
	 */
	public function update_pass($params) {

		$userID								= ( isset($params['userID']) ) ? (int)$params['userID'] : false;
		$old_pass							= ( isset($params['old_pass']) ) ? $params['old_pass'] : false;
		$new_pass							= ( isset($params['new_pass']) ) ? $params['new_pass'] : false;

		if ( !$userID ) {
			$this->code 					= 417;
			return 0;
		}

		$Password 							= new Core\Password($this->_CONN, $this->_REDIS, $userID);

		$Password->set_password($old_pass);

		$check 								= $Password->check_user_password();

		if ( !$check ) {
			$this->code 					= 417;
			return 3;
		}

		$Password->set_password($new_pass);

		$results 							= $Password->update_user_password();

		$Password 							= null;

		$this->code 						= 200;

		return $results;

	}


	/**
	 * Update user photo in DB
	 * @param array $params	Parameters that are submitted
	 */
	public function photo($params) {

		$userID								= ( isset($params['userID']) ) ? (int)$params['userID'] : false;
		$photo					  			= ( isset($params['photo']) ) ? $params['photo'] : false;

		if ( !$userID ) {
			$this->code 					= 417;
			return 0;
		}

		if ( !$photo ) {
			$this->code 					= 417;
			return 3;
		}

		$User 								= new Account\User($this->_CONN, $this->_REDIS, $userID);

		$update 							= $User->update_user_photo($photo);

		$results 							= $update ? 1 : 2;
		
		$User 								= null;

		$this->code 						= 200;

		return $results;

	}


	/**
	 * Rotate user photo 90 degrees
	 * @param array $params	Parameters that are submitted
	 */
	public function photo_rotate($params) {

		$userID								= ( isset($params['userID']) ) ? (int)$params['userID'] : false;

		if ( !$userID ) {
			$this->code 					= 417;
			return 0;
		}

		$User								= new Account\User($this->_CONN, $this->_REDIS, $userID);

		$user_photo			 				= $User->get_user_photo();

		if ( !$user_photo ) {
			$User 							= null;
			$this->code 					= 417;
			return 2;
		}

		$Upload				 				= new Core\Upload($this->_CONN, $this->_REDIS, $userID);
	
		$image				  				= APP_PATH . DS . $user_photo;
	
		$outcome							= $Upload->rotate_image($image, 90);

		$results 							= ( $outcome ) ? 1 : 3;
		
		$User 								= null;
		$Upload 							= null;

		$this->code 						= 200;

		return $results;

	}

}

