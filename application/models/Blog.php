<?php

class Model_Blog extends Zend_Db_Table
{
	protected $_name = 'avi_blog';
	public $message;
	
	
	//////////////////// CREATE ////////////////////
	public function insertBlog($form)
	{
		$formData = $form->getValues();
		
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(0, 0, 0, $month, $day, $year);
		$data['title'] = $formData['title'];
		$data['description'] = $formData['description'];
		$data['content'] = $formData['content'];
		$data['date_modified'] = time();
		
		if ($form->picture->isUploaded()) {
			if ($form->picture->receive()) {
		        $fullFilePath = $form->picture->getFileName();
		        $targetPath = PUBLIC_PATH . '/uploads/blogs/';
				$targetName = $formData['title'] . '_' . time() . substr($formData['picture'], -4);
				$targetFullFilePath = $targetPath . $targetName;
                $filterRename = new Zend_Filter_File_Rename(array('target' => $targetFullFilePath, 'overwrite' => true));
                $filterRename->filter($fullFilePath);
				$image = new Image();
				if ($image->setSize($targetFullFilePath, 200, 'auto')) {
					$data['picture'] = $targetName;
				} else {$this->message = $image->message; return false;}
			} else {$this->message = 'Did not receive picture'; return false;}
		}

		$result = $this->insert($data);
		if (is_numeric($result)) {
			$this->message = $result;
			return true;
		} else {
			$this->message = 'Could not insert into database.';
			return false;
		}
	}
	
	
	//////////////////// RETRIEVE ////////////////////
	public function retrieveItem($title)
	{
		return $this->fetchRow($this->select()
			->where('title = ?', $title)
		);
	}
	
	
	public function retrieveAllAdmin()
	{
		return $this->fetchAll($this->select()
			->order('date DESC')
		);
	}


	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->where('status = ?', 'active')
			->where('date < ?', time())
			->order(array('date DESC'))
		);
	}

	public function getAllBlogs()
	{
		return $this->fetchAll($this->select()
			->order('date DESC')
		);
	}


	public function getLatestBlogs()
	{
		return $this->fetchAll($this->select()
			->order('date DESC')
			->limit(5)
		);
	}


	public function getBlogById($id)
	{
		return $this->fetchRow($this->select()
			->where('id = ?', $id)
		);
	}
	
	
	//////////////////// UPDATE ////////////////////
	public function editBlog($form)
	{
		$formData = $form->getValues();
		
		$blog = $this->getBlogById($formData['id']);
		if (!$blog) {$this->message = 'Could not find blog'; return false;}

		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(0, 0, 0, $month, $day, $year);
		$data['title'] = $formData['title'];
		$data['description'] = $formData['description'];
		$data['content'] = $formData['content'];
		$data['date_modified'] = time();

		if ($form->picture->isUploaded()) {
			if ($form->picture->receive()) {
		        $fullFilePath = $form->picture->getFileName();
		        $targetPath = PUBLIC_PATH . '/uploads/blogs/';
				$targetName = $formData['title'] . '_' . time() . substr($formData['picture'], -4);
				$targetFullFilePath = $targetPath . $targetName;
                $filterRename = new Zend_Filter_File_Rename(array('target' => $targetFullFilePath, 'overwrite' => true));
                $filterRename->filter($fullFilePath);
				$image = new Image();
				if ($image->setSize($targetFullFilePath, 200, 'auto')) {
					$this->deletePicture($blog->picture);
					$data['picture'] = $targetName;
				} else {$this->message = $image->message; return false;}
			} else {$this->message = 'Did not receive picture'; return false;}
		} else $data['picture'] = $blog->picture;

		$where = 'id = ' . $formData['id'];
		
		if ($this->update($data, $where)) {
			return true;
		} else {
			$this->message = 'Could not update database.';
			return false;
		}
	}
	
	
	//////////////////// DELETE ////////////////////
	public function deleteBlog($formData)
	{
		$blog = $this->getBlogById($formData['id']);
		if (!$blog) {$this->message = 'Could not find blog'; return false;}
		
		$this->deletePicture($blog->picture);
		
		$where = 'id = ' . $formData['id'];
		
		if ($this->delete($where)) {
			return true;
		} else {
			$this->message = 'Could not delete from database';
			return false;
		}
	}
	
	
	protected function deletePicture($pic)
	{
		if (!empty($pic)) {
			$file = PUBLIC_PATH . '/uploads/blogs/' . $pic;
			if (file_exists($file)) {
				unlink($file);
			}
		}
	}
}