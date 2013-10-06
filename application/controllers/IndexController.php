<?php

class IndexController extends Zend_Controller_Action
{
	private $albumsDb;
	private $tracksDb;
	private $blogDb;
	private $poemsDb;
	public function init()
	{
		$this->albumsDb = new Model_Albums();
		$this->tracksDb = new Model_Tracks();
		$this->blogDb = new Model_Blog();
		$this->poemsDb = new Model_Poem();
	}
	
	
	public function indexAction()
    {
		$this->view->title = 'Talented singer and artist';
		
		$this->view->albums = $albums = $this->albumsDb->retrieveAll();
		$this->view->tracks = $tracks = $this->tracksDb->retrieveAlbum($albums[0]['id']);
		
		$this->view->blog = $this->blogDb->retrieveAll();
		$this->view->poems = $this->poemsDb->retrieveAll();
    }
}

