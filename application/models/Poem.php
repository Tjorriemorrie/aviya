<?php

class Model_Poem extends Zend_Db_Table
{
	protected $_name = 'avi_poems';
	public $message;
		
	
	//////////////////// CREATE ////////////////////
	public function createPoem($form)
	{
		$formData = $form->getValues();
		
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(8, 0, 0, $month, $day, $year);
		$data['title'] = $formData['title'];
		$data['content'] = $formData['content'];
		$data['date_modified'] = time();
		
		if (isset($formData['picture'])) {
	        $path = PUBLIC_PATH . '/uploads/poems/';
	        $tmpPic = $path . $formData['picture'];
			$targetName = $formData['title'] . '_' . time() . substr($formData['picture'], -4);
			$targetPicPath = $path . $targetName;
            $filterRename = new Zend_Filter_File_Rename(array('target' => $targetPicPath, 'overwrite' => true));
            $filterRename->filter($tmpPic);
			$image = new Model_Image();
			if ($image->setSize($targetPicPath, 200, 'auto')) {
				$data['picture'] = $targetName;
			} else {$this->message = $image->message; return false;}
		}
		
		try {
			$this->message = $this->insert($data);
			return true;
		} catch (Zend_Exception $e) {
			$this->message = 'Could not insert into database';
			$this->deletePicture($data['picture']);
			return false;
		}
	}
	
	
	//////////////////// RETRIEVE ////////////////////
	public function retrievePoem($id)
	{
		return $this->fetchRow($this->select()
			->where('date < ?', time())
			->where('id = ?', $id)
		);
	}
	
	
	public function retrievePoemAdmin($id)
	{
		return $this->fetchRow($this->select()
			->where('id = ?', $id)
		);
	}
	
	
	public function retrievePoemByTitle($title)
	{
		return $this->fetchRow($this->select()
			->where('date < ?', time())
			->where('title = ?', $title)
		);
	}
	
	
	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->where('status = ?', 'active')
			->where('date < ?', time())
			->order('date DESC')
		);
	}


	public function retrieveAllAdmin()
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
	public function editPoem($form, $id)
	{
		$poem = $this->retrievePoemAdmin($id);
		
		$formData = $form->getValues();
		
		list($year, $month, $day) = explode('/', $formData['date']);
		$data['date'] = mktime(8, 0, 0, $month, $day, $year);
		$data['title'] = $formData['title'];
		$data['content'] = $formData['content'];
		$data['date_modified'] = time();
		
		if (isset($formData['picture'])) {
	        $path = PUBLIC_PATH . '/uploads/poems/';
	        $tmpPic = $path . $formData['picture'];
			$targetName = $formData['title'] . '_' . time() . substr($formData['picture'], -4);
			$targetPicPath = $path . $targetName;
            $filterRename = new Zend_Filter_File_Rename(array('target' => $targetPicPath, 'overwrite' => true));
            $filterRename->filter($tmpPic);
			$image = new Model_Image();
			if ($image->setSize($targetPicPath, 200, 'auto')) {
				$this->deletePicture($poem->picture);
				$data['picture'] = $targetName;
			} else {$this->message = $image->message; return false;}
		} else $formData['picture'] = $poem->picture;

		$where = 'id = ' . $id;
		
		try {
			$this->update($data, $where);
			return true;
		} catch (Zend_Exception $e) {
			$this->message = 'Could not update database';
			$this->deletePicture($data['picture']);
			return false;
		}
	}
	
	
	//////////////////// DELETE ////////////////////
	public function deletePoem($id)
	{
		$poem = $this->retrievePoemAdmin($id);
		
		$where = 'id = ' . $id;
		
		try {
			$this->delete($where);
			$this->deletePicture($poem->picture);	
			return true;
		} catch (Zend_Exception $e) {
			$this->message = 'Could not detele from database';
			return false;
		}
	}
	
	
	protected function deletePicture($pic)
	{
		if (!empty($pic)) {
			$file = PUBLIC_PATH . '/uploads/poems/' . $pic;
			if (file_exists($file)) {
				unlink($file);
			}
		}
	}	
}