<?php

class Admin_Model_Users extends Zend_Db_Table
{
	public $message;
	protected $_name = 'avi_users';
	
	
	//////////////////// RETRIEVE ////////////////////
	public function retrieveUser($id)
	{
		return $this->fetchRow($this->select()
			->where('id = ?', $id)
		);
	}


	public function retrieveAll()
	{
		return $this->fetchAll($this->select()
			->order('date_login DESC')
		);
	}
	
	
	//////////////////// UPDATE ////////////////////
	public function setLogin($user)
	{
		$data['date_prev'] = $user->date_login;
		$data['date_login'] = time();
		
		$where = 'id = ' . $user->id;
		
		$this->update($data, $where);
	}
	
	
	public function changePermission($username, $setting)
	{
		$user = $this->retrieveUserByUsername($username);
		
		if ($user->$setting == 'no') $data[$setting] = 'yes';
		else $data[$setting] = 'no';
		$data['date_modified'] = time();
		
		$where = 'id = ' . $user->id;
		
		try {
			$this->update($data, $where);
			return true;
		} catch (Zend_Exception $e) {
			$this->message = $e->getMessage();
			return false;
		}
	}


	//////////////////// DELETE ////////////////////
	public function deleteUser($id)
	{
		$user = $this->getUser($id);
		$file_path = './img/logos/' . $user->logo;
		if (file_exists($file_path)) unlink($file_path);
		$where = 'id = ' . $id;
		return $this->delete($where);
	}
}