<?php

class Admin_Model_Poems extends Zend_Db_Table
{
	protected $_name = 'avi_poems';
	public $message;
		
	
	//////////////////// CREATE ////////////////////
	public function createItem($form)
	{
		$formData = $form->getValues();
		
		$data['title'] = $formData['title'];
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(8, 0, 0, $month, $day, $year);
		$data['content'] = $formData['content'];
		
		if (isset($formData['picture'])) {
			$targetName = $formData['title'] . ' ' . time() . substr($formData['picture'], strrpos($formData['picture'], '.'));
			$currentPath = PUBLIC_PATH . '/uploads/' . $formData['picture'];
			$targetPath = PUBLIC_PATH . '/uploads/poems/' . $targetName;

			//$mimes[1] = $form->file1->getMimeType($fullFilePath); // for email attachment
            $filterRename = new Zend_Filter_File_Rename(array('target' => $targetPath, 'overwrite' => true));
            $filterRename->filter($currentPath);
            
			$imageMdl = new Admin_Model_Image();
			if ($imageMdl->setSize($targetPath, 200, 'auto')) {						
				$data['picture'] = $targetName;
			} else {
				$this->message = $imageMdl->message;
				return false;
			}
		}

		try {
			$this->message = $this->insert($data);
			//$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') $this->message = 'Could not create item: ' . $e->getMessage();
			else $this->message = 'Could not create item.';
			return false;
		}
	}
	
	
	//////////////////// RETRIEVE ////////////////////
	public function retrieveItem($id)
	{
		return $this->fetchRow($this->select()
			->where('id = ?', $id)
		);
	}
	
	
	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->order('date DESC')
		);
	}


	public function getLatestPoems()
	{
		return $this->fetchAll($this->select()
			->order('date_created DESC')
			->limit(5)
		);
	}
	
	
	//////////////////// UPDATE ////////////////////
	public function setStatus($id)
	{
		$item = $this->retrieveItem($id);
		if (!$item) die('Could not retrieve item');
		
		if ($item->status == 'deactive') $data['status'] = 'active';
		elseif ($item->status == 'active') $data['status'] = 'deactive';
		else die('Could not establish status of item');
		
		$data['date_modified'] = time();
		$where = 'id = ' . $id;
		
		try {
			$this->message = $this->update($data, $where);
			//$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') die('Could not toggle item status: ' . $e->getMessage());
			else die('Could not toggle item status.');
			return false;
		}
	}
	
	
	public function updateItem($form, $poem)
	{
		$formData = $form->getValues();
		
		$data['title'] = $formData['title'];
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(8, 0, 0, $month, $day, $year);
		$data['content'] = $formData['content'];
		
		if (isset($formData['picture'])) {
			$targetName = $formData['title'] . ' ' . time() . substr($formData['picture'], strrpos($formData['picture'], '.'));
			$currentPath = PUBLIC_PATH . '/uploads/' . $formData['picture'];
			$targetPath = PUBLIC_PATH . '/uploads/poems/' . $targetName;

			//$mimes[1] = $form->file1->getMimeType($fullFilePath); // for email attachment
            $filterRename = new Zend_Filter_File_Rename(array('target' => $targetPath, 'overwrite' => true));
            $filterRename->filter($currentPath);
            
			$imageMdl = new Admin_Model_Image();
			if ($imageMdl->setSize($targetPath, 200, 'auto')) {
				$this->unlinkFile($poem->picture);						
				$data['picture'] = $targetName;
			} else {
				$this->message = $imageMdl->message;
				return false;
			}
		}

		$data['date_modified'] = time();
		$where = 'id = ' . $poem->id;
		
		try {
			$this->update($data, $where);
			//$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') $this->message = 'Could not update item: ' . $e->getMessage();
			else $this->message = 'Could not update item.';
			return false;
		}
	}
	
	
	//////////////////// DELETE ////////////////////
	public function deleteItem($poem)
	{
		$this->unlinkFile($poem->picture);
		
		$where = 'id = ' . $poem->id;
		
		try {
			$this->delete($where);
			//$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') $this->message = 'Could not delete item: ' . $e->getMessage();
			else $this->message = 'Could not delete item.';
			return false;
		}
	}
	
	
	private function unlinkFile($filename)
	{
		$file = PUBLIC_PATH . '/uploads/poems/' . $filename;
		if (file_exists($file)) unlink($file);
	}	


	private function setXml()
	{
		function cmpEntries($a , $b)
		{
			$a_time = $a['lastUpdate'];
			$b_time = $b['lastUpdate'];
			if ($a_time == $b_time) {
				return 0;
			}
			return ($a_time > $b_time) ? -1 : 1;
		}

		$entries = array();

		$blogDb = new Model_Blog();
		$blogs = $blogDb->retrieveAllAdmin();		
		foreach ($blogs as $blog) {
			$entry = array(
				'title' => $blog->title,
				'link' => 'http://www.aviyacrest.com/index/blog/entry/' . $blog->id,
				'description' => htmlentities(nl2br($blog->description)),
				'lastUpdate' => $blog->date
			);
			array_push($entries, $entry);
		}

		$poems = $this->poemsDb->retrieveAllAdmin();
		foreach ($poems as $poem) {
			$entry = array(
				'title' => $poem->title,
				'link' => 'http://www.aviyacrest.com/index/poems/entry/' . $poem->id,
				'description' => 'New poem from Aviya.',
				'lastUpdate' => $poem->date
			);
			array_push($entries, $entry);
		}		

		usort($entries, 'cmpEntries');

		$feed = array(
			'title' => 'Aviya',
			'link' => 'http://www.aviyacrest.com',
			'description' => 'The latest blog posts and poems from Aviya',
			'language' => 'en-gb',
			'charset' => 'utf-8',
			'lastUpdate' => mktime(0, 0, 0, 11, 1, 2007),
			'generator' => 'Zend Framework Zend_Feed',
			'entries' => $entries
		);
		
		$rss = Zend_Feed::importArray($feed, 'rss');
		$rssFeed = $rss->saveXML();
		
		try {
			$fh = fopen(PUBLIC_PATH . '/blog.xml', 'w');
			fwrite($fh, $rssFeed);
			fclose($fh);
			return true;
		} catch (Zend_Exception $e) {
			die($e->getMessage());
			return false;
		}
	}
}

