<?php 

/**
 * Check if request method is accessible for this service
 * 
 */
class Api_Hook_MethodAllowed extends Api_Hook_Base {
	

	public function execute() {
		
		$service = func_get_arg(0);

		$params = $this->api->getRequest()->getParams();
		
		$method = $this->api->prepareMethodName(!isset($params['method']) || empty($params['method']) ? "execute" : $params['method']);
		
		$accepted_request_methods = $service->getAcceptMethods($method);

		if ( !$accepted_request_methods || !in_array( $this->api->getRequest()->method, $accepted_request_methods ) ) {
			throw new Api_Error(Api_Error::REQUEST_METHOD_DISABLED, array($this->api->getRequest()->method, $method));
		}

	}


}