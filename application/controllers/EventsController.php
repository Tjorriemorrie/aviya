<?php

class EventsController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		
	}	
	
	
	public function indexAction()
	{
		$this->_helper->redirector('upcoming');
	}
	
	
	public function upcomingAction()
	{
		$this->view->title = 'Upcoming Events';
	}
}