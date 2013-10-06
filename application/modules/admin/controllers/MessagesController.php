<?php

class Admin_MessagesController extends Zend_Controller_Action
{
	private $messagesDb;
	public function init()
	{
		if (!Zend_Auth::getInstance()->hasIdentity()) $this->_helper->redirector('logout', 'auth');
		$this->messagesDb = new Admin_Model_Messages();
		
		$this->view->title = 'Messages';
	}
	
	
	public function indexAction()
	{
		$this->view->messages = $this->messagesDb->retrieveAll();
	}
	
	
	public function viewAction()
	{
		$this->view->message = $message = $this->messagesDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$message) $this->_helper->redirector('index');
		$userInfo = Zend_Auth::getInstance()->getIdentity();
		if ($userInfo->username == 'Aviya') $this->messagesDb->markAsRead($message);
	}		


	public function deleteAction()
	{
		$this->view->message = $message = $this->messagesDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$message) $this->_helper->redirector('index');
		
		$form = new Admin_Form_Yesno();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->messagesDb->deleteItem($message)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->messagesDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
}

