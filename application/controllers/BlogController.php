<?php

class BlogController extends Zend_Controller_Action
{
	private $blogDb;
	public function init()
	{
		$this->blogDb = new Model_Blog();
	}
	
	
	public function indexAction()
    {
		$this->view->title = "Blog";

		$this->view->blogs = $blogs = $this->blogDb->getAllBlogs();
	}
	
	
	public function entryAction()
	{
		$this->view->title = 'Blog';
		
		$this->view->item = $item = $this->blogDb->retrieveItem($this->getRequest()->getParam('title'));
		if (!$item) $this->_helper->redirector('index');

		$this->view->blogs = $blogs = $this->blogDb->getAllBlogs();
	}
}

