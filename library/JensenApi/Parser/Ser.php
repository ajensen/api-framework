<?php

class JensenApi_Parser_Ser extends Api_Parser_Base {
	
	
	public $content_type = 'text/plain';
	

	public function parse() {

		return serialize($this->_data);
	
	}
	

}