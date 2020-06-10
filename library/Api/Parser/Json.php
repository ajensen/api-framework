<?php

/**
 * Parse output to Json
 * @param <cb> - use to add a callback function (/cb/myFunction.json)
 * 
 */
class Api_Parser_Json extends Api_Parser_Base {
	

	/**
	 * Content type
	 * @var string
	 */
	public $content_type = "application/json";
	

	/**
	 * Parse to Json
	 * 
	 * @return string
	 */
	public function parse() {

		if ( isset($this->params['cb']) && !empty($this->params['cb']) ) {

			return $this->params['cb'] . '(' . json_encode( $this->_data ) . ')';
		
		}

		return json_encode($this->_data);
		
	}
	

}