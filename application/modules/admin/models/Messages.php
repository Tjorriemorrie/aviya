<?php

class Admin_Model_Messages extends Zend_Db_Table
{
	protected $_name = 'avi_messages';
	public $message;
	
	
	//////////////////// CREATE ////////////////////
	public function setMessage($formData)
	{
		$data['name'] = $formData['name'];
		$data['email'] = $formData['email'];
		$data['subject'] = $formData['subject'];
		$data['message'] = $formData['message'];
		$data['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		$data['date_modified'] = time();
		
		try {
			$this->insert($data);
			if ($this->sendNotification()) {
				return true;
			} else {
				$this->message = 'Could not send notification.';
				return false;
			}
		} catch (Zend_Exception $e) {
			$this->message = 'Could not save to database';
			return false;
		}
	}
	
	
	private function sendNotification()
	{
		$messages = $this->retrieveUnread();
		
		$mail = new Zend_Mail();
		$mail->setSubject('Message received on Aviya website!');
		$mail->setFrom('webmaster@aviyacrest.com', 'Webmaster');

		$usersDb = new Model_Users();
		$users = $usersDb->retrieveAll();
		foreach ($users as $user) {
			$mail->addTo($user->email, $user->username);
		}

		$html = '<h3>Dear ' . $user->username . '</h3>';
		$html .= '<p>A new message was received on the Aviya website!</p>';
		$html .= '<p>Please <a href="http://www.aviyacrest.com/auth/login" title="log in">log in</a> to read the message.</p>';
		$html .= '<p>There is currently ' . sizeof($messages) . ' unread messages.</p>';
		$html .= '<p>Regards,</p>';
		$html .= '<p>Webmaster</p>';
		$mail->setBodyHtml($html);
		
		try {
			$mail->send();
			return true;
		} catch (Zend_Exception $e) {
			return false;
		}	
	}
	
	
	//////////////////// RETRIEVE ////////////////////
	public function retrieveItem($id)
	{
		return $this->fetchRow($this->select()
			->where('id = ?', $id)
		);
	}
	
	
	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->order(array('marked DESC', 'date_created DESC'))
		);
	}
	
	
	public function retrieveUnread()
	{
		return $this->fetchAll($this->select()
			->where('marked = ?', 'unread')
		);
	}
	
	
	public function getReadMessages()
	{
		return $this->fetchAll($this->select()
			->where('marked = ?', 'read')
		);
	}
	
	
	//////////////////// UPDATE ////////////////////
	public function markAsRead($message)
	{
		$data['marked'] = 'read';
		
		$data['date_modified'] = time();
		$where = 'id = ' . $message->id;
		
		try {
			$this->message = $this->update($data, $where);
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') $this->message = 'Could not mark item: ' . $e->getMessage();
			else $this->message = 'Could not mark item.';
			return false;
		}
	}
	
	
	//////////////////// DELETE ////////////////////
	public function deleteItem($message)
	{
		$where = 'id = ' . $message->id;
		
		try {
			$this->delete($where);
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') $this->message = 'Could not delete item: ' . $e->getMessage();
			else $this->message = 'Could not delete item.';
			return false;
		}
	}
	
	
	//////////////////// OTHER ////////////////////
	public function sendReply($formData, $message)
	{
		$mail = new Zend_Mail();
		$mail->setSubject($formData['subject']);
		$mail->setFrom('noreply@aviyacrest.com', 'Aviya');
		$mail->addTo($message->email, $message->name);

		$html = nl2br($formData['message']);
		$mail->setBodyHtml($html);
		
		try {
			$mail->send();
			return true;
		} catch (Zend_Exception $e) {
			$this->message = 'Could not send reply';
			return false;
		}
		
	}
	
	
	
}