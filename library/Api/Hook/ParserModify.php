<?php 

/**
 * Modify some settings utilized by the current parser being used
 * 
 */
class Api_Hook_ParserModify extends Api_Hook_Base {
	

	public function execute() {
	
		$parser = func_get_arg(0);

		// Uncomment the lines below to add var type to the output xml
		// if ( $this->api->getType() == Api_Response::TYPE_XML ) {
			
		// 	$parser->enableAddVarTypes();

		// }
	
	}
	

}