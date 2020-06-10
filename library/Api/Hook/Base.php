<?php

use Jensen\Dbase;

/**
 * Base class for API hooks
 * 
 */
abstract class Api_Hook_Base {
	

	/**
	 * List of API Hook types
	 * @var string
	 */
	const HOOK_BEFORE_SERVICE_EXECUTE 	= "HOOK_BEFORE_SERVICE_EXECUTE";
	const HOOK_MODIFY_PARSER			= "HOOK_MODIFY_PARSER";
	

	/**
	 * Api instance
	 * @var Api $api
	 */
	protected $api;

	/**
	 * Database instance
	 * @var DB $_CONN
	 */
	protected $_CONN;

	/**
	 * Redis instance
	 * @var Redis $_REDIS
	 */
	protected $_REDIS;


	/**
	 * Makes connections to databases and redis
	 */
	final protected function connect() {

        global $_CONN, $_REDIS;

		$_CONN = $this->_CONN = new Dbase\Connection(DB_NAME, DB_SERVER);

		if ( USE_REDIS ) {

			// connect to redis server
			try {

			    $_REDIS = $this->_REDIS = new \Redis();

			    $this->_REDIS->connect(REDIS_SERVER['server'], REDIS_SERVER['port']);	    

			} catch (Exception $e) {

				$redis_error = 'Redis Error: '.$e->getMessage();

			    error_log($redis_error);

			    if ( ENVIRONMENT !== 'production' ) {

			    	throw new Exception($redis_error);

			    }

			}

		}

	}
	

	/**
	 * Method called to execute the hook
	 * 
	 * @param Api $api
	 * @param Api_Service_Base $service
	 * @return *
	 */
	abstract public function execute();
	

	/**
	 * Set api
	 * 
	 * @param Api $api
	 * @return void
	 */
	public function setApi(&$api) {

		$this->api = $api;
	
	}


}