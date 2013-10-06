<?php

class Admin_AuthController extends Zend_Controller_Action
{
	private $authMdl;
	public function init()
	{
		$this->authMdl = new Admin_Model_Auth();
	}
	
	
	public function indexAction()
    {
		$this->_helper->redirector('login', 'auth');
	}
	
	
	public function loginAction()
	{
		$this->view->title = 'Log in';
		
		$form = new Admin_Form_Login();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index', 'index', 'default');
			elseif ($form->isValid($formData)) {
				if ($this->authMdl->login($formData)) $this->_helper->redirector('index', 'index');
				else $form->setDescription('Error: ' . $this->authMdl->message);
			} else $form->setDescription('Error: Invalid form submitted');
		}

		$this->view->form = $form;
    }
	
	
	public function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::destroy();
		$this->_helper->redirector('index', 'index', 'default');
	}
}






