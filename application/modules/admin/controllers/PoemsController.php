<?php

class Admin_PoemsController extends Zend_Controller_Action
{
	private $poemsDb;
	public function init()
	{
		if (!Zend_Auth::getInstance()->hasIdentity()) $this->_helper->redirector('logout', 'auth');
		$this->poemsDb = new Admin_Model_Poems();
		
		$this->view->title = 'Poems';
	}
	
	
	public function indexAction()
	{
		$this->view->poems = $this->poemsDb->retrieveAll();
	}
	
	
	public function viewAction()
	{
		$this->view->poem = $poem = $this->poemsDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$poem) $this->_helper->redirector('index');
	}		


	public function createAction()
	{
		$form = new Admin_Form_Poem();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->poemsDb->createItem($form)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->poemsDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
	
	
	public function statusAction()
	{
		$this->poemsDb->setStatus($this->getRequest()->getParam('id'));
		$this->_helper->redirector('index');
	}
	
	
	public function updateAction()
	{
		$this->view->poem = $poem = $this->poemsDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$poem) $this->_helper->redirector('index');
		else $poem->date = date('Y/m/d', $poem->date);
		
		$form = new Admin_Form_Poem();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->poemsDb->updateItem($form, $poem)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->poemsDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		} else $form->populate($poem->toArray());
		
		$this->view->form = $form;
	}


	public function deleteAction()
	{
		$this->view->poem = $poem = $this->poemsDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$poem) $this->_helper->redirector('index');
		
		$form = new Admin_Form_Yesno();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->poemsDb->deleteItem($poem)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->poemsDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
}

