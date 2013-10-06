<?php

class ContactController extends Zend_Controller_Action
{
	private $session;
	private $messagesDb;
	public function init()
	{
		$this->session = new Zend_Session_Namespace();
		$this->messagesDb = new Model_Messages();
	}
	
	
	public function indexAction()
    {
		$this->view->title = 'Contact';
		
        $form = new Form_Contact();
        $form->removeElement('cancel');
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
            	if ($this->messagesDb->setMessage($formData)) $form->reset()->setDescription('Message received. Thank you very much!');
				else $form->setDescription('Error: ' . $this->messagesDb->message);
            } elseif (count($form->getErrors('hash')) > 0) {
    			$this->_forward('csrf', 'error');
            } else $form->setDescription('Error: Invalid form submitted');
        }
		
		$this->view->form = $form;
    }
}

