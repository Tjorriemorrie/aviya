<?php

class Model_Auth
{
	protected $tableName = 'avi_users';
	private $identityColumn = 'username';
	private $credentialColumn = 'password';
	
	
	public function processLogin($formData)
	{
		$registry = Zend_Registry::getInstance();
		$authAdapter = new Zend_Auth_Adapter_DbTable($registry->dbAdapter);
		
		$authAdapter
			->setTableName($this->tableName)
			->setIdentityColumn($this->identityColumn)
			->setCredentialColumn($this->credentialColumn)
			->setIdentity($formData['username'])
			->setCredential($formData['password'])
			//->setCredentialTreatment('MD5(?)');
			;
			
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($authAdapter);
		
		if ($result->isValid()) {
			$userInfo = $authAdapter->getResultRowObject(null, 'password');
			$auth->getStorage()->write($userInfo);
			return TRUE;
		} else return FALSE;
	}
	
	
	public function registerUser($formData)
	{
		if ($formData['password'] !== $formData['password2']) return 'Passwords does not match';
		if ($formData['email'] !== $formData['email2']) return 'Email address does not match';
		
		$usersDto = new Users();
		$user = $usersDto->getUserByEmail($formData['email']);
		if (sizeof($user) > 0) return 'That email address is already registered. Please follow the recover password procedure';
		
		$data['firstname'] = $formData['firstname'];
		$data['lastname'] = $formData['lastname'];
		$data['email'] = $formData['email'];
		$data['password'] = md5($formData['password']);
		
		return $usersDto->insert($data);
	}
	
	
	private function rand_chars($c, $l, $u = FALSE) 
	{
		//string $c is the string of characters to use.
		//integer $l is how long you want the string to be.
		//boolean $u is whether or not a character can appear beside itself.
		 if (!$u) for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c{$x}, $i++);
		 else for ($i = 0, $z = strlen($c)-1, $s = $c{rand(0,$z)}, $i = 1; $i != $l; $x = rand(0,$z), $s .= $c{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s));
		 return $s;
	}


	private function sendPasswordToUser($user, $newPassword)
	{
		$bodyHtml = 'Dear ' . $user->firstname . PHP_EOL . PHP_EOL;
		$bodyHtml .= 'You requested your password.' . PHP_EOL . PHP_EOL;
		$bodyHtml .= 'Your new password is: ' . $newPassword . '.' . PHP_EOL . PHP_EOL;
		$bodyHtml .= 'Regards,' . PHP_EOL;
		$bodyHtml .= 'The Living Tree' . PHP_EOL;
		
		$mail = new Zend_Mail();
		$mail->setBodyHtml($bodyHtml);
		$mail->setFrom('noreply@thelivingtree.org', 'The Living Tree');
		$mail->addTo($user->email, $user->firstname . ' ' . $user->lastname);
		$mail->setSubject('Password reset');
		return $mail->send();
	}
	
	
	public function recoverPassword($formData)
	{
		$usersDto = new Users();
		$user = $usersDto->getUserByEmail($formData['email']);
		if (sizeof($user) == 1) {
			// make new password
			$newPassword = $this->rand_chars("bcdfghjklmnpqrstvwxyz0123456789", 9, TRUE);
			// mail password
			$result = $this->sendPasswordToUser($user, $newPassword);
			// save new password
			if ($result) {
				$data['password'] = md5($newPassword);
				$where = 'id = ' . $user->id;
				$update = $usersDto->update($data, $where);
				if ($update) return 'success';
				else return 'Could not reset password';
			} else
				return 'Could not send password to email address';
		} else 
			return 'No such registered email address was found';
	}
}




