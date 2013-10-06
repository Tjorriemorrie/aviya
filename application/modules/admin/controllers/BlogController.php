<?php

class Admin_BlogController extends Zend_Controller_Action
{
	private $session;
	private $blogDb;
	private $page;
	public function init()
	{
        if (!Zend_Auth::getInstance()->hasIdentity()) $this->_helper->redirector('logout', 'auth');
        
		$this->session = new Zend_Session_Namespace();
		$this->blogDb = new Admin_Model_Blog();
		
		if (!isset($this->session->page)) $this->page = $this->session->page = 1;
		else $this->page = $this->session->page;
		
		$this->view->title = 'Blog';
	}
	
	
	private function getTotalPages()
	{
		$datas = $this->blogDb->retrieveAll();
		return ceil(sizeof($datas) / 10);
	}
	
	
	public function pageAction()
	{
		$this->session->page = $page = $this->getRequest()->getParam('number');
		$pagesTotal = $this->getTotalPages();
		if ($page > $pagesTotal || $page < 1) $this->session->page = 1;
		$this->_helper->redirector('index');
	}
	
	
	public function indexAction()
	{
		$this->view->blog = $this->blogDb->retrievePage($this->page);
		
		$this->view->page = $this->page;
		$this->view->pagesTotal = $this->getTotalPages();
	}
	
	
	public function viewAction()
	{
		$this->view->blog = $blog = $this->blogDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$blog) $this->_helper->redirector('index');
	}		


	public function createAction()
	{
		$form = new Admin_Form_Blog();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->blogDb->createItem($form)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->blogDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
	
	
	public function statusAction()
	{
		$this->blogDb->setStatus($this->getRequest()->getParam('id'));
		$this->_helper->redirector('index');
	}
	
	
	public function updateAction()
	{
		$this->view->blog = $blog = $this->blogDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$blog) $this->_helper->redirector('index');
		else $blog->date = date('Y/m/d', $blog->date);
		
		$form = new Admin_Form_Blog();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->blogDb->updateItem($form, $blog)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->blogDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		} else $form->populate($blog->toArray());
		
		$this->view->form = $form;
	}


	public function deleteAction()
	{
		$this->view->blog = $blog = $this->blogDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$blog) $this->_helper->redirector('index');
		
		$form = new Admin_Form_Yesno();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->blogDb->deleteItem($blog)) $this->_helper->redirector('index');
				else $form->setDescription('Error: ' . $this->blogDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
}