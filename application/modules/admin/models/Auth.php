<?php

class Admin_Model_Auth extends Zend_Db_Table
{
	public $message;
	protected $_name = 'avi_users';
	
	
	public function login($formData)
	{
		
		$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbAdapter'));
		
		$authAdapter
			->setTableName($this->_name)
			->setIdentityColumn('username')
			->setCredentialColumn('password')
			->setIdentity($formData['username'])
			->setCredential($formData['password'])
			//->setCredentialTreatment((?))
			;
			
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($authAdapter);

		switch ($result->getCode()) {
		
			case Zend_Auth_Result::SUCCESS:
				$userInfo = $authAdapter->getResultRowObject(null, 'password');					
				$auth->getStorage()->write($userInfo);
				return true;
				break;
				
			case Zend_Auth_Result::FAILURE:
		    	$this->message = 'The login attempt failed.';
		    	return false;
				break;
				
			case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
		    	$this->message = 'Your username were not found.';
		    	return false;
				break;
				
			case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
		    	$this->message = 'Your username is ambiguous.';
		    	return false;
				break;
				
			case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
		    	$this->message = 'Your password is incorrect.';
		    	return false;
				break;
				
			case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
		    	$this->message = 'The login attempt is uncategorized.';
		    	return false;
				break;
				
		    default:
		    	$this->message = 'The login attempt did not complete. Please try again.';
		    	return false;
		        break;
		}
	}
}