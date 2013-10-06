<?php

class Model_Albums extends Zend_Db_Table
{
	protected $_name = 'avi_albums';
	public $message;
	
	
	////////// CREATE //////////
	public function createAlbum($form)
	{
		$formData = $form->getValues();
		
		$data['title'] = $formData['title'];
		$data['description'] = $formData['description'];
		$data['date_modified'] = time();
		
		if (isset($formData['cover'])) {
			$path = PUBLIC_PATH . '/uploads/albums/';
	        $tmpFilePath =  $path . $formData['cover'];
			$tgtName = $formData['title'] . '_' . time() . substr($formData['cover'], -4);
			$tgtFilePath = $path . $tgtName;
            $filterRename = new Zend_Filter_File_Rename(array('target' => $tgtFilePath, 'overwrite' => true));
            $filterRename->filter($tmpFilePath);
			$image = new Model_Image();
			if ($image->setSize($tgtFilePath, 200, 'auto')) {
				$data['cover'] = $tgtName;
			} else {$this->message = $image->message; return false;}
		}

		try {
			$this->message = $this->insert($data);
			return true;
		} catch (Zend_Exception $e) {
			$this->message = $e->getMessage();
			return false;
		}
	}
	
	
	////////// RETRIEVE //////////
	public function retrieveItem($id)
	{
		return $this->fetchRow($this->select()
			->where('id = ?', $id)
		);
	}
	
	
	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->order(array('date_created DESC'))
		);
	}
	
	
	////////// UPDATE //////////
	public function updateAlbum($form, $id)
	{
		$formData = $form->getValues();
		
		$album = $this->retrieveAlbum($id);
		
		$data['title'] = $formData['title'];
		$data['description'] = $formData['description'];
		$data['date_modified'] = time();
		
		if (isset($formData['cover'])) {
			$path = PUBLIC_PATH . '/uploads/albums/';
	        $tmpFilePath =  $path . $formData['cover'];
			$tgtName = $formData['title'] . '_' . time() . substr($formData['cover'], -4);
			$tgtFilePath = $path . $tgtName;
            $filterRename = new Zend_Filter_File_Rename(array('target' => $tgtFilePath, 'overwrite' => true));
            $filterRename->filter($tmpFilePath);
			$image = new Model_Image();
			if ($image->setSize($tgtFilePath, 200, 'auto')) {
				$this->deletePicture($album->cover);
				$data['cover'] = $tgtName;
			} else {$this->message = $image->message; return false;}
		} else $data['cover'] = $album->cover;

		$where = 'id = ' . $id;

		try {
			$this->update($data, $where);
			return true;
		} catch (Zend_Exception $e) {
			$this->message = $e->getMessage();
			return false;
		}		
	}
	
	
	////////// DELETE //////////
	private function deletePicture($pic)
	{
		if (!empty($pic)) {
			$picpath = PUBLIC_PATH . '/uploads/albums/' . $pic;
			if (file_exists($picpath)) unlink($picpath);
		}
	}
}