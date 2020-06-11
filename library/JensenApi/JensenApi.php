<?php

include("library/Api/Api.php");

class JensenApi extends Api {
	

	public function __construct() {

		parent::__construct();
	
	}
	

	/**
	 * Overwrite the Api method in order to look for the correct service directory
	 */
	protected function getServicePath($service = '') {

		$api_path 						= PATH . DS . $this->config['custom_path'];

		$params 						= $this->request->getParams();

		return $api_path . DS . "Service" . DS . str_replace(".", "_", $params['version']);

	}


}