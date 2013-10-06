<?php

class Admin_GalleryController extends Zend_Controller_Action
{
	private $galleryDb;
	public function init()
	{
        if (!Zend_Auth::getInstance()->hasIdentity()) $this->_helper->redirector('logout', 'auth');
		$this->galleryDb = new Model_Gallery();
	}
	
	
	public function indexAction()
	{
		$this->view->title = 'Gallery';
		$this->view->photos = $this->galleryDb->retrievePhotosAll();
	}
	
	
	public function createAction()
	{
		$this->view->title = 'Add New Photograph';
		
		$form = new Admin_Form_Gallery();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->galleryDb->createPhoto($form)) $this->_helper->redirector('index');			
				else $form->setDescription('Error: ' . $this->galleryDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
	
	
	public function statusAction()
	{
		$this->galleryDb->setStatus($this->getRequest()->getParam('id'));
		$this->_helper->redirector('index');
	}
	
	
	public function updateAction()
	{
		$this->view->title = 'Updating Photo';
		
		$id = $this->getRequest()->getParam('id');
		$this->view->photo = $photo = $this->galleryDb->retrievePhoto($id);
		if (!$photo) $this->_helper->redirector('index');
		
		$form = new Admin_Form_Gallery();
		$form->picture->setRequired(false);
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->galleryDb->updatePhoto($form, $id)) $this->_helper->redirector('index');			
				else $form->setDescription('Error: ' . $this->photoDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		} else $form->populate($photo->toArray());
		
		$this->view->form = $form;
	}


	public function deleteAction()
	{
		$this->view->title = 'Delete Photo?';
		
		$id = $this->getRequest()->getParam('id');
		$this->view->photo = $photo = $this->galleryDb->retrievePhoto($id);
		if (!$photo) $this->_helper->redirector('index');
		
		$form = new Form_Yesno();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if (isset($formData['cancel'])) $this->_helper->redirector('index');
			elseif ($form->isValid($formData)) {
				if ($this->galleryDb->deletePhoto($photo)) $this->_helper->redirector('index');			
				else $form->setDescription('Error: ' . $this->photoDb->message);
			} else $form->setDescription('Error: Invalid form submitted.');
		}
		
		$this->view->form = $form;
	}
}