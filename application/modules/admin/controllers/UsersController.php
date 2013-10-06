<?php

class Admin_UsersController extends Zend_Controller_Action
{
	private $session;
	private $usersDb;
	public function init()
	{
		$this->session = new Zend_Session_Namespace();
		$this->usersDb = new Admin_Model_Users();

		$this->view->title = 'Users';
	}
	
	
	public function indexAction()
	{
		$this->view->users = $this->usersDb->retrieveAll();
	}


	public function userAction()
	{
		$this->validPrivilige();
		
		$username = $this->getRequest()->getParam('for');
		$setting = $this->getRequest()->getParam('change');
		
		if ($this->usersDb->changePermission($username, $setting)) {
			$this->session->userMsg = 'Successfully changed ' . $setting . ' for ' . $username;
		} else {
			$this->session->userMsg = 'Error: ' . $this->usersDb->message;
		}
		
		$this->_helper->redirector('index');
	}
}