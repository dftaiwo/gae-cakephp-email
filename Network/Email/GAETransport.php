<?php

/**
 * A Google App Engine Mail Transport for CakePHP
  Filename: GAETransport.php 
  @author: Femi TAIWO [dftaiwo@gmail.com]
  Created: Jun 30, 2015  3:21:59 PM
 * GAE Transport class
 * @package       Lib.Network.Email
 */
App::uses('AbstractTransport', 'Network/Email');

use \google\appengine\api\mail\Message;

class GAETransport extends AbstractTransport {

	/**
	 * Send mail
	 *
	 * @param CakeEmail $email CakeEmail
	 * @return array
	 */
	public function send(CakeEmail $email) {

		if (!array_key_exists('APPENGINE_RUNTIME',$_SERVER)) { //not google app engine
			throw new Exception("Mail Environment Error: The GAETransport can only run on Google App Engine");
		}
		
		$gaeMail = new google\appengine\api\mail\Message();

		$headers = $email->getHeaders(array('from', 'sender', 'replyTo', 'readReceipt', 'returnPath', 'to', 'cc', 'subject', 'bcc'));

		$gaeMail->setSender($headers['From']);
		
		if(isset($headers['ReplyTo']) && $headers['ReplyTo']){
			$gaeMail->setReplyTo($headers['ReplyTo']);
		}
		
		$this->_addRecipients($gaeMail,$email->to(),"to");
		
		$this->_addRecipients($gaeMail,$email->cc(),"cc");
		
		$this->_addRecipients($gaeMail,$email->bcc(),"bcc");
		
		$gaeMail->setSubject($email->subject());

		switch ($email->emailFormat()) {
			case 'html':
				$gaeMail->setHtmlBody($email->message($email::MESSAGE_HTML));
				break;
			case 'text':
				$gaeMail->setTextBody($email->message($email::MESSAGE_TEXT));
				break;
			case 'both':
				$gaeMail->setHtmlBody($email->message($email::MESSAGE_HTML));
				$gaeMail->setTextBody($email->message($email::MESSAGE_TEXT));
				break;
		}

		if ($email->attachments()) {
			foreach ($email->attachments() as $attachment) {
				$this->_addAttachment($gaeMail, $attachment);
			}
		}
		
		try{
			
			$gaeMail->send();
			return true;
		} catch (Exception $ex) {
			
			throw new Exception("Send Error: ".$ex->getMessage());
			
		}
		
	}
	/**
	 * 
	 * @param google\appengine\api\mail\Message $gaeMail
	 * @param array $attachment
	 * @throws Exception
	 */

	function _addAttachment($gaeMail, $attachment) {

		$file = $attachment['file'];

		if (!file_exists($file)) {
			throw new Exception("Attachment Error: Unable to locate file : $file");
		}

		$filename = basename($file);
		
		$contentId = (isset($attachment['contentId']) && $attachment['contentId']) ? "<{$attachment['contentId']}>" : null;

		$gaeMail->addAttachment($filename, file_get_contents($file), $contentId);
	}
	/**
	 * 
	 * @param google\appengine\api\mail\Message $gaeMail
	 * @param Array $recipients
	 * @param string $method
	 */
	
	function _addRecipients($gaeMail,$recipients,$method){
		if(!$recipients) return;
		$method = "add".ucfirst($method);
		
		foreach($recipients as $email=>$name){
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				continue;//or should I throw a fit?
			}
			
			if(!is_numeric($email) && $name && $email!=$name){
				$email = "$name <$email>";
			}else{
				$email = $name;
			}
			$gaeMail->{$method}($email);
		}
		
	}

}
