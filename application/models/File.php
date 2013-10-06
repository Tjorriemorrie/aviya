<?php

class Model_File
{
	protected $upload;
	protected $upload_name;
	protected $upload_type;
	protected $upload_error;
	protected $upload_size;
	
	public $fileFormName;
	public $message;
	public $fileMovedName;
	public $upload_tmpname;
	
	
	public function __construct($name)
	{
		$this->fileFormName = $name;
	}
	
	
	protected function getExtension()
	{
		return substr($this->upload_name, -3);
	}
	
	
	public function isValid() {
		if (!empty($_FILES[$this->fileFormName]["name"])) {
			$this->upload = $_FILES[$this->fileFormName];
			$this->upload_name = $this->upload["name"];
			$this->upload_type = $this->upload["type"];
			$this->upload_tmpname = $this->upload["tmp_name"];
			$this->upload_error = $this->upload["error"];
			$this->upload_size = $this->upload["size"];
			
			switch ($this->upload_error) {
				case 1: $this->message = "The uploaded file exceeds the possible limit of the server."; break;
				case 2: $this->message = "The uploaded file exceeds the maximum file size stipulated."; break;
				case 3: $this->message = "The uploaded file was only partially uploaded."; break;
				case 4: $this->message = "No file was uploaded to the server."; break;
				case 6: $this->message = "Missing a temporary folder."; break;
				case 7: $this->message = "Failed to write file to disk."; break;
				case 8: $this->message = "File upload stopped by extension."; break;
				default: return true; break;
			}
		} else {
			$this->message = 'File ' . $this->fileFormName . ' is not valid';
			return false;
		}
	}
	
	
	public function setDirectory($target_dir, $target_name)
	{
		while (strpos($target_name, " ") != false) {$target_name = str_replace(" ", "_", $target_name);} // convert spaces to underscores
		while (strpos($target_name, ":") != false) {$target_name = str_replace(":", "_-_", $target_name);} // remove double colons
		while (strpos($target_name, "\"") != false) {$target_name = str_replace("\"", "", $target_name);} // remove quotes
		while (strpos($target_name, "'") != false) {$target_name = str_replace("'", "", $target_name);} // remove apostraphes
		while (strpos($target_name, "?") != false) {$target_name = str_replace("?", "", $target_name);} // remove question marks
		
		$full_name = $target_name . '.' . $this->getExtension();
		$target_path = $target_dir . $full_name;
		
		if (move_uploaded_file($this->upload_tmpname, $target_path)) {
			$this->fileMovedName = $full_name;
			return true;
		} else {
			$this->message = "There was an error moving the file, please try again!<br />From: " . $this->upload_tmpname . "<br />To: $target_path";
			return false;
		}
	}
}




