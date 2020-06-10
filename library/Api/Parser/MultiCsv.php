<?php

/**
 * Parse output to Csv
 * 
 */
class Api_Parser_MultiCsv extends Api_Parser_Base {
	

	/**
	 * Separator character
	 * @var string
	 */
	const SEPARATOR 						= ";";
	

	/**
	 * Adds column header names as first line in CSV if set to true
	 * @var string
	 */
	const DISPLAY_HEADER 					= false;
	

	/**
	 * Content type
	 * @var string
	 */
	public $content_type 					= "text/csv";
	

	/**
	 * Parse to CSV
	 * 
	 * @return string
	 */
	public function parse() {
		
		// Get first group
		$first_group 						= reset($this->_data['data']);
		
		// Loop and make csv
		$csv 								= "";
		
		// If add header
		if ( self::DISPLAY_HEADER ) {
			
			$header 						= '';

			foreach ( $first_group[0] as $column => $value ) {
			
				$header 					.= $column . self::SEPARATOR;
			
			}
			
			$csv 							.= substr($header, 0, -1) . "\n";
		
		}

		foreach ( $first_group as $row ) {
			
			// Add row to CSV
			$line 							= '';
			
			foreach ( $row as $column => $value ) {

				$line 						.= $value . self::SEPARATOR;
			
			}

			$csv 							.= substr($line, 0, -1) . "\n";
		
		}
		
		return $csv;
	
	}
	
	
}