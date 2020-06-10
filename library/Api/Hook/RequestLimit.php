<?php 

/**
 * Block an IP address from excessive API requests
 * 
 * Set in config.ini file:
 * @param <max_request_time> the time in seconds that you want to use to limit requests
 * @param <max_request_hits> the max request an ip can do in the max_request_time span
 *
 */
class Api_Hook_RequestLimit extends Api_Hook_Base {
	

	public function execute() {
		
		$config = $this->api->getConfig();

		$logger = $this->api->getLogger();

		// Make sure it is configured
		if ( !isset($config['max_request_hits']) || !isset($config['max_request_time']) ) return;
		
		if ( !isset($_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]) ) {
			
			$_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['hits'] = 0;
			$_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['first'] = time();
		
		} else {

			if ( (time() - $_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['first']) >= $config['max_request_time'] ) {

				// Reset vars
				$_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['hits'] = 0;
				$_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['first'] = time();
			
			}

		}
		
		// Set current time in session
		$_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['last'] = time();
		$_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['hits']++;

		// Check if max requests reached
		if ( $_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['hits'] > $config['max_request_hits'] ) {
			throw new Api_Error(Api_Error::TOO_MANY_REQUESTS);
		}
		
		//$logger->log($_SESSION['requestlimit'][$_SERVER['REMOTE_ADDR']]['hits']);
	
	}
	

}