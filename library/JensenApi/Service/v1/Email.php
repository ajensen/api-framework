<?php

use Jensen\Core;
use Jensen\Messaging;

/**
 *
 */
class JensenApi_Service_Email extends Api_Service_Base {
	

	/**
	 * Service for sending emails via the platform
	 * 
	 * @var string
	 */
	protected $_name = "Email";
	
	
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
		$this->addAllowedMethod("execute", Api_Request::METHOD_POST);
		$this->addAllowedMethod("notification", Api_Request::METHOD_POST);
		$this->addAllowedMethod("send", Api_Request::METHOD_POST);
		
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
	 * Send email using sendgrid
	 * 
	 * @param array $params Parameters that are submitted
	 * @param array $config Api config
	 */
	public function send($params, $config) {


		try {

			if ( $reqData = @file_get_contents('php://input') ) {

				$req 							= json_decode($reqData,true);

			}

			// requires to_email, sender, subject, msg
			// optional params to_name, bcc
			if (
				!isset($req['to_email']) ||
				!isset($req['sender']) ||
				!isset($req['subject']) ||
				!isset($req['msg'])
			) throw new Api_Error('Invalid', 'Missing Required Parameter(s)', 400);


			$to_email   						= $req['to_email'];
			$to_name							= $req['to_name'] ?? 'Unknown Name';
			$sender_email   					= $req['sender'];
			$subject							= $req['subject'];
			$msg								= $req['msg'];
			$bcc								= $req['bcc'] ?? [];
			$templateID	 						= $req['template'] ?? null;

			// send email notification here that process has been started
			$Email 								= new Messaging\Email($this->_CONN, $this->_REDIS, 1);

			if ( !empty($templateID) ) $Email->setTemplateID($templateID);

			// append bcc if used
			foreach ( $req['bcc'] as $v ) {
				
				$bcc_name 						= $v['name'] ?? null;
				$bcc_email 						= $v['email'] ?? null;

				if ( $bcc_email === null ) continue;

				$Email->setBcc($bcc_name,$bcc_email);

			}

			// wrap html template
			$body 								= $Email->build_message($subject,$msg);

			// send email via sendgrid account
			$Email->sendgrid($to_email, $to_name, $sender_email, 'Jensen Info Email', $subject, $body);

			$this->code 						= 201;

			return;

		} catch (Exception $e) {

			throw new Api_Error('Invalid', $e->getMessage(), 400);

		}

	}


	/**
	 * Send user an account notification email
	 * 
	 * @param array $params Parameters that are submitted
	 */
	public function notification($params) {

		try {

			if ( $reqData = @file_get_contents('php://input') ) {

				$req							= json_decode($reqData,true);

			}

			if ( !isset($req['notificationID']) )
				throw new Api_Error('Invalid', 'Missing Required NotificationID', 400);

			$maskedID = (int) ($params['maskedID'] ?? 0);

			if ( $maskedID === 0 ) return;

			$recipient = [
				'to_email'  					=> $req['to_email'],
				'to_name'   					=> $req['to_name']
			];

			$bcc 								= [];

			$bcc[] = [
				'name'  						=> 'Andrew Jensen',
				'email' 						=> 'andrew@andrewmichaeljensen.com'
			];

			if ( isset($req['bcc']) && !empty($req['bcc']) ) {

				$bcc 							= array_merge($bcc, $req['bcc']);

			}

			$replace 							= $req['replace'] ?? null;

			$Notifications 						= new Messaging\Notifications($this->_CONN, $this->_REDIS, 0);

			$Notifications->initDynoDBs($maskedID);


			if ( !$Notifications->sendNotificationEmail((int) $req['notificationID'], $recipient, $replace, $bcc) ) {
				throw new Api_Error('Invalid', 'Invalid Request', 400);
			}

			$this->code = 201;

			return;

		} catch (Exception $e) {

			throw new Api_Error('Invalid', $e->getMessage(), 400);

		}

	}

	
}
