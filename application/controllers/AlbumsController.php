<?php

class AlbumsController extends Zend_Controller_Action
{
	private $albumsDb;
	private $tracksDb;
	public function init()
	{
		$this->albumsDb = new Model_Albums();
		$this->tracksDb = new Model_Tracks();
	}
	
	
	public function indexAction()
    {
		$this->view->title = 'Flight of Time Album';
		
		$this->view->album = $album = $this->albumsDb->retrieveItem(1);
		$this->view->tracks = $tracks = $this->tracksDb->retrieveAlbum($album->id);
	}
}

