<?php

/**
 * Parse output to Printr
 * 
 */
class Api_Parser_Printr extends Api_Parser_Base {
	

	/**
	 * Content type
	 * @var string
	 */
	public $content_type = "text/plain";
	

	/**
	 * Parse to XML
	 * 
	 * @return string
	 */
	public function parse() {
		return print_r($this->_data, true);
	}
	
	
}