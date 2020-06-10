<?php

use Jensen\Api;

class JensenApi_Hook_ApiKey extends Api_Hook_Base {


	public function execute() {


		$params = $this->api->getRequest()->getParams();


		// check if key and token exists for basic authorization 
		if ( !isset($params['key']) || !isset($params['token']) ) {

			throw new Api_Error('Denied access!', 'Invalid API credentials submitted', 403);

			return;

		}

		// authorization handling for live api authorization
		if ($params['version'] == 'v2') {

			$this->connect();

			$Extension = new Api\Extension($this->_CONN, $this->_REDIS, $params['key'], $params['token'], $params['service'], $params['method']);

			if ( !$Extension->_valid ) {

				throw new Api_Error('Not allowed', 'Invalid API credentials submitted', 403);
			
			} else {

				$_SESSION['userID'] = $Extension->_userID;
				
				return;

			}

		// authorization handling for internal web authorization
		} else if ($params['version'] == 'v1') {

			$this->connect();

			if ( $params['key'] === LIVE_API_NAME && $params['token'] !== LIVE_API_PASSWORD ) {

				throw new Api_Error('Forbidden', 'Invalid API credentials', 403);

			} elseif ( $params['key'] === LIVE_API_NAME && $params['token'] === LIVE_API_PASSWORD ) {

				return;

			} else {

				throw new Api_Error('Not allowed', 'Invalid API credentials', 403);

			}


		}
	}


}

