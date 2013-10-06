<?php

class Admin_Model_Blog extends Zend_Db_Table
{
	protected $_name = 'avi_blog';
	public $message;
	
	
	//////////////////// CREATE ////////////////////
	public function createItem($form)
	{
		$formData = $form->getValues();
		
		$data['title'] = $formData['title'];
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(0, 0, 0, $month, $day, $year);
		$data['description'] = $formData['description'];
		$data['content'] = $formData['content'];
		
		if (isset($formData['picture'])) {
			$targetName = $formData['title'] . ' ' . time() . substr($formData['picture'], strrpos($formData['picture'], '.'));
			$currentPath = PUBLIC_PATH . '/uploads/' . $formData['picture'];
			$targetPath = PUBLIC_PATH . '/uploads/blogs/' . $targetName;

			//$mimes[1] = $form->file1->getMimeType($fullFilePath); // for email attachment
            $filterRename = new Zend_Filter_File_Rename(array('target' => $targetPath, 'overwrite' => true));
            $filterRename->filter($currentPath);
            
			$imageMdl = new Admin_Model_Image();
			if ($imageMdl->setSize($targetPath, 200, 'auto')) {						
				$this->unlinkFile($blog->picture);
				$data['picture'] = $targetName;
			} else {
				$this->message = $imageMdl->message;
				return false;
			}
		}

		try {
			$this->message = $this->insert($data);
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') die('Could not create item: ' . $e->getMessage());
			else die('Could not create item.');
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
	
	
	public function retrievePage($page)
	{
		return $this->fetchAll($this->select()
			->order('date DESC')
			->limit(10, (($page - 1) * 10))
		);
	}
	
	
	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->order('date DESC')
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
			$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') die('Could not toggle item status: ' . $e->getMessage());
			else die('Could not toggle item status.');
			return false;
		}
	}
	
	
	public function updateItem($form, $blog)
	{
		$formData = $form->getValues();
		
		$data['title'] = $formData['title'];
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(0, 0, 0, $month, $day, $year);
		$data['description'] = $formData['description'];
		$data['content'] = $formData['content'];

		if (isset($formData['picture'])) {
			$targetName = $formData['title'] . ' ' . time() . substr($formData['picture'], strrpos($formData['picture'], '.'));
			$currentPath = PUBLIC_PATH . '/uploads/' . $formData['picture'];
			$targetPath = PUBLIC_PATH . '/uploads/blogs/' . $targetName;

			//$mimes[1] = $form->file1->getMimeType($fullFilePath); // for email attachment
            $filterRename = new Zend_Filter_File_Rename(array('target' => $targetPath, 'overwrite' => true));
            $filterRename->filter($currentPath);
            
			$imageMdl = new Admin_Model_Image();
			if ($imageMdl->setSize($targetPath, 200, 'auto')) {						
				$this->unlinkFile($blog->picture);
				$data['picture'] = $targetName;
			} else {
				$this->message = $imageMdl->message;
				return false;
			}
		} else $data['picture'] = $blog->picture;

		$data['date_modified'] = time();
		$where = 'id = ' . $blog->id;
		
		try {
			$this->message = $this->update($data, $where);
			$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') die('Could not update item: ' . $e->getMessage());
			else die('Could not update item.');
			return false;
		}
	}
	
	
	//////////////////// DELETE ////////////////////
	public function deleteItem($blog)
	{
		$this->unlinkFile($blog->picture);
		
		$where = 'id = ' . $blog->id;
		
		try {
			$this->message = $this->delete($where);
			$this->setXml();
			return true;
		} catch (Zend_Exception $e) {
			if (APPLICATION_ENV != 'production') die('Could not delete item: ' . $e->getMessage());
			else die('Could not delete item.');
			return false;
		}
	}
	
	
	private function unlinkFile($filename)
	{
		$file = PUBLIC_PATH . '/uploads/blogs/' . $filename;
		if (file_exists($file)) unlink($file);
	}
	
	
	private function setXml()
	{
		$items = $this->fetchAll($this->select()
			->where('status = ?', 'active')
			->order('date')
		);
		
		$entries = array();
		foreach ($items as $item) {
			$entry = array(
				'title' => $item->title,
				'link' => 'http://www.aviyacrest.com/blog/entry/title/' . $item->title,
				'description' => nl2br($item->description),
				'lastUpdate' => $item->date,
				'author' => 'Aviya'
			);
			array_push($entries, $entry);
		}

		$feed = array(
			'title' => 'Aviya',
			'link' => 'http://www.aviyacrest.com/blog',
			'description' => 'Aviya\'s blog entails stories close to her heart',
			'language' => 'en-gb',
			'charset' => 'utf-8',
			'lastUpdate' => mktime(0, 0, 0, 6, 1, 2007),
			'generator' => 'Zend Framework Zend_Feed',
			'entries' => $entries
		);
		
		$rss = Zend_Feed::importArray($feed, 'rss');
		$rssFeed = $rss->saveXML();
		
		$fh = fopen(PUBLIC_PATH . '/blog.xml', 'w');
		fwrite($fh, $rssFeed);
		fclose($fh);
	}
}

