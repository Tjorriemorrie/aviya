<?php

class Model_Tracks extends Zend_Db_Table
{
	protected $_name = 'avi_tracks';
	public $message;
	
	
	////////// CREATE //////////
	public function createTrack($formData, $albumID, $trackNumber)
	{
		$data['albumID'] = $albumID;
		$data['trackNumber'] = $trackNumber;
		$data['trackTitle'] = ucwords($formData['trackTitle']);
		$data['trackLyrics'] = $formData['trackLyrics'];
		$data['date_modified'] = time();
		
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
		return $this->fetchAll();
	}
	
	
	public function retrieveAlbum($albumID)
	{
		return $this->fetchAll($this->select()
			->where('albumID = ?', $albumID)
			->order('trackNumber')
		);
	}
	
	
	////////// UPDATE //////////
	public function updateTrack($formData, $id)
	{
		$data['trackTitle'] = ucwords($formData['trackTitle']);
		$data['trackLyrics'] = $formData['trackLyrics'];
		
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