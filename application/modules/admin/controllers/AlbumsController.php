<?php

class Admin_AlbumsController extends Zend_Controller_Action
{
	private $session;
	private $albumsDb;
	private $tracksDb;
	public function init()
	{
        if (!Zend_Auth::getInstance()->hasIdentity()) $this->_helper->redirector('logout', 'auth');
        
		$this->session = new Zend_Session_Namespace();
		$this->albumsDb = new Admin_Model_Albums();
		$this->tracksDb = new Admin_Model_Tracks();
		
		$this->view->title = 'Albums';
	}
	
	
	public function indexAction()
	{
		$this->view->albums = $this->albumsDb->retrieveAll();
	}
	
	
	public function setalbumAction()
	{
		$this->session->album = $album = $this->albumsDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$album) {
			$this->session->album = false;
			$this->_helper->redirector('index');
		} else $this->_helper->redirector('view');
	}
	
	
	public function viewAction()
	{
		$this->view->album = $album = $this->session->album;
		if (!$album) $this->_helper->redirector('index');
		
		$this->view->tracks = $this->tracksDb->retrieveAlbum($album->id);
	}
	
	
	public function settrackAction()
	{
		$this->session->track = $track = $this->tracksDb->retrieveItem($this->getRequest()->getParam('id'));
		if (!$track || $track->albumID != $this->session->album->id) {
			$this->session->track = false;
			$this->_helper->redirector('view');
		} else $this->_helper->redirector('lyrics');
	}


	public function lyricsAction()
	{
		$this->view->album = $this->session->album;
		$this->view->track = $this->session->track;
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