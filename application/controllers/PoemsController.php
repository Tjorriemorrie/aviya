<?php

class PoemsController extends Zend_Controller_Action
{
	private $session;
	private $poemsDb;
	public function init()
	{
		$this->session = new Zend_Session_Namespace();
		$this->poemsDb = new Model_Poem();
	}
	
	
	public function indexAction()
    {
    	$this->view->title = 'Poems by Aviya';
    	
		$this->view->poems = $this->poemsDb->retrieveAll();
    }
    
    
    public function viewAction()
    {
    	$title = $this->getRequest()->getParam('entry');
    	$this->view->poem = $poem = $this->poemsDb->retrievePoemByTitle($title);
    	if (!$poem) $this->_helper->redirector('index');
    	
		$this->view->title = $poem->title;
	}
}

