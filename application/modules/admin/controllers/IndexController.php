<?php

class Admin_IndexController extends Zend_Controller_Action
{
	private $blogDb;
	private $poemsDb;
	private $messagesDb;
	private $usersDb;
    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) $this->_helper->redirector('login', 'auth');
        
        $this->blogDb = new Admin_Model_Blog();
        $this->poemsDb = new Admin_Model_Poems();
        $this->messagesDb = new Admin_Model_Messages();
        $this->usersDb = new Admin_Model_Users();
        
        $this->view->title = 'Dashboard';
    }


    public function indexAction()
    {
        $this->view->blog = $this->blogDb->retrieveAll();
        $this->view->poems = $this->poemsDb->retrieveAll();
        $this->view->msgsUnread = $this->messagesDb->retrieveUnread();
        $this->view->users = $this->usersDb->retrieveAll();
    }
}

