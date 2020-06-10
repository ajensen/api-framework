<?php

use Jensen\Core;

/**
 *
 */
class JensenApi_Service_Buckets extends Api_Service_Base {


	/**
	 * Service for storing/uploading files into AWS Buckets
	 * 
	 * @var string
	 */
	protected $_name = "Buckets";

	/**
	 * Construct
	 *
	 * @param Api $api
	 */
	public function __construct($api) {

		parent::__construct($api);
		//call this to establish proper connectections
		//do not use if your route does not require db connections
		$this->connect();

		// Set request methods
		$this->addAllowedMethod("store", Api_Request::METHOD_POST);
		$this->addAllowedMethod("upload", Api_Request::METHOD_POST);

	}


	/**
	 * Check if service is active
	 *
	 * @param array $params Parameters that are submitted
	 * @param array $config Api config
	 */
	public function execute($params, $config) {
		$this->code = 200;
		return;
	}


	/**
	 * Rename file and then upload to AWS Bucket
	 * 
	 * @param array $params 
	 */
	public function upload($params)
	{

		$source 						= $params['source'] ?? null;
		$target 						= $params['target'] ?? null;

		if (empty($source) || empty($target))
			throw new Api_Error('Invalid', 'Missing Source and/or Target', 400);

		//push file up to AWS bucket!
		$AwsBuckets 					= new Core\AwsBuckets($this->_CONN, $this->_REDIS, AWS_BUCKET, AWS_BUCKET_REGION, AWS_BUCKET_CREDS);

		try {

			$request 					= $AwsBuckets->uploadFile($source, $target);

			$aws_url 					= $request;

			$request 					= null;

			$this->code 				= 200;

			return $aws_url;

		} catch (Exception $e) {

			throw new Api_Error('Invalid', $e->getMessage(), 400);

		}

	}


	/**
	 * Rename file and then upload to specified AWS Bucket
	 * 
	 * @param array $params 
	 */
	public function store($params)
	{

		$AwsBuckets 					= new Core\AwsBuckets($this->_CONN, $this->_REDIS, null, null, null);

		$bucket 						= ( isset($params['bucket']) ) ? $params['bucket'] : 'jensen-assets';

		$AwsBuckets->setBucket($bucket);

		$file_name 						= ( isset($params['file']) ) ? $params['file'] : false;

		if ( !$file_name ) {

			$this->code 				= 400;
			return false;

		}

		$new_file_name 					= ( isset($params['new_name']) ) ? $params['new_name'] : '';

		//$meta_data 					= ( isset($params['meta_data']) ) ? $params['meta_data'] : '';

		$result 						= $AwsBuckets->uploadFile($file_name, $new_file_name);

		$this->code 					= 200;

		return $result['effectiveUri'];

	}


}
