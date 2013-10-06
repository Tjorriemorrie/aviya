<?php

class Admin_Model_Image
{
	public $message;
	protected $source;
	protected $width;
	protected $height;
	protected $type;
	protected $attr;
	protected $bits;
	protected $channels;
	protected $mime;
	
	
	protected function isValid($image_path)
	{
        if (!file_exists($image_path)) {
			$this->message = "Image file not found.";
			return false;
		}
		
		$imageSize =  getimagesize($image_path);
		$this->width = $imageSize[0];
		$this->height = $imageSize[1];
		$this->type = $imageSize[2];
		$this->attr = $imageSize[3];
		$this->bits = $imageSize['bits'];
		$this->channels = $imageSize['channels'];
		$this->mime = $imageSize['mime'];
		
		switch ($this->mime) {
			case 'image/jpeg':
				if (imagetypes() & IMG_JPG) return true;
				else {
					$this->message = 'JPEG images are not supported';
					return false;
				}
				break;
			case 'image/png':
				if (imagetypes() & IMG_PNG) return true;
				else {
					$this->message = 'PNG images are not supported';
					return false;
				}
				break;
			case 'image/gif':
				if (imagetypes() & IMG_GIF) return true;
				else {
					$this->message = 'GIF images are not supported';
					return false;
				}
				break;
			default:
				$this->message = $this->mime . ' images are not supported';
				return false;
				break;
		}
	}
	
	
	protected function imageCreateFromType($image_path)
	{
		switch ($this->mime) {
			case 'image/jpeg': $this->source = imagecreatefromjpeg($image_path); return true; break;
			case 'image/png': $this->source = imagecreatefrompng($image_path); return true; break;
			case 'image/gif': $this->source = imagecreatefromgif($image_path); return true; break;
			default: $this->message = "Could not create image from type"; return false; break;
		}
	}
	
	
	protected function saveImageType($dst, $target_path)
	{
		switch ($this->mime) {
			case 'image/jpeg': return imagejpeg($dst, $target_path, 100); break;
			case 'image/png': return imagepng($dst, $target_path, 0, NULL); break;
			case 'image/gif': return imagegif($dst, $target_path); break;
			default: return false; break;
		}
	}
	
	
	protected function getCopyValues($target_width, $target_height)
	{
		$values = array();
		
		$ratio_width = $this->width / $target_width;
		$ratio_height = $this->height / $target_height;
		
		if ($ratio_width > $ratio_height) {
			$set_width = $target_width;
			$set_height = round($this->height / $ratio_width);
			$off_x = 0;
			$off_y = round(($target_height - $set_height) / 2);
		} else {
			$set_width = round($this->width / $ratio_height);
			$set_height = $target_height;
			$off_x = round(($target_width - $set_width) / 2);
			$off_y = 0;
		}
		
		$values[] = $off_x;
		$values[] = $off_y;
		$values[] = 0;
		$values[] = 0;
		$values[] = $set_width;
		$values[] = $set_height;
		
		return $values;
	}
	
	
	public function setSize($image_path, $target_width, $target_height, $target_path = NULL) {
		if (is_null($target_path)) $target_path = $image_path;
		if (!$this->isValid($image_path)) return false;
		
		if ($target_height == 'auto') {
			if ($this->width > $target_width) {
				$ratio = $this->width / $this->height;
				$target_height = $target_width / $ratio;
			}
		}
		
		if (!$this->imageCreateFromType($image_path)) return false;
		list ($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h) = $this->getCopyValues($target_width, $target_height);
				
        $dst = imagecreatetruecolor($target_width, $target_height);
        $white = imagecolorallocate($dst, 255, 255, 255);
		$black = imagecolorallocate($dst, 0, 0, 0);
        imagefill($dst, 0, 0, $black);
		// bool imagecopyresampled  ( resource $dst_image  , resource $src_image  , int $dst_x  , int $dst_y  , int $src_x  , int $src_y  , int $dst_w  , int $dst_h  , int $src_w  , int $src_h  )
		// imagecopyresampled() copies a rectangular portion of one image to another image, smoothly interpolating pixel values so that, in particular, reducing the size of an image still retains a great deal of clarity.
		// In other words, imagecopyresampled() will take an rectangular area from src_image of width src_w and height src_h at position (src_x ,src_y ) and place it in a rectangular area of dst_image of width dst_w and height dst_h at position (dst_x ,dst_y ).
		// If the source and destination coordinates and width and heights differ, appropriate stretching or shrinking of the image fragment will be performed. The coordinates refer to the upper left corner. This function can be used to copy regions within the same image (if dst_image is the same as src_image ) but if the regions overlap the results will be unpredictable.
		if (!imagecopyresampled($dst, $this->source, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $this->width, $this->height)) {
			$this->message = "Could not copy and resize part of an image with resampling.";
			return false;
		}
		
		if ($this->saveImageType($dst, $target_path)) return true;
		else { 
			$this->message = "Could not save image from type";
			return false;
		}
	}
	
	
	public function setWatermark($watermark, $newsletter, $name = NULL)
	{
		if (!is_null($name)) $filename = $name . '_' . time() . '.jpg';
		else $filename = 'tmp_' . time() . 'jpg';
		
		if (!file_exists($watermark)) {echo "watermark doesn't exist"; exit;}
		if (preg_match('/[.](jpg)$/', $watermark)) $src = imagecreatefromjpeg($watermark);
		if (preg_match('/[.](gif)$/', $watermark)) $src = imagecreatefromgif($watermark);
		if (preg_match('/[.](png)$/', $watermark)) $src = imagecreatefrompng($watermark);
		list($src_w, $src_h, $src_type, $src_attr) = getimagesize($watermark);
		$src_x = 0;
		$src_y = 0;
		
		$dst = imagecreatefromjpeg($newsletter);
		list($dst_w, $dst_h, $dst_type, $dst_attr) = getimagesize($newsletter);
		$dst_x = $dst_w - 200;
		$dst_y = 20;
		
		//bool imagecopymerge  ( resource $dst_im  , resource $src_im  , int $dst_x  , int $dst_y  , int $src_x  , int $src_y  , int $src_w  , int $src_h  , int $pct  )		
		$copied = imagecopy($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
		if (!$copied) {echo "could not copy image"; exit;}
		
		$dst_path = PUBLIC_PATH . '/uploads/tmp/' . $filename;
		if (imagejpeg($dst, $dst_path)) return $dst_path;
		else die('could not save newsletter');
	}
	
	
	public function setTextmark($image_path, $text, $name=NULL)
	{
		if (!is_null($name)) $filename = $name . '_' . time() . '.jpg';
		else $filename = 'tmp_' . time() . 'jpg';
		
		list($src_w, $src_h, $src_type, $src_attr) = getimagesize($image_path);
		if (preg_match('/[.](jpg)$/', $image_path)) $src = imagecreatefromjpeg($image_path);
		if (preg_match('/[.](gif)$/', $image_path)) $src = imagecreatefromgif($image_path);
		if (preg_match('/[.](png)$/', $image_path)) $src = imagecreatefrompng($image_path);
		
		$dst = imagecreatetruecolor($src_w, $src_h);
		
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $src_w, $src_h, $src_w, $src_h);
		
		$black = imagecolorallocate($dst, 168, 93, 105);
		// Set the enviroment variable for GD
		putenv('GDFONTPATH=' . APPLICATION_PATH . '/data/fonts/');

		// Name the font to be used (note the lack of the .ttf extension)
		$font = 'Newbaskn.ttf';
		$font_size = 20;
		imagettftext($dst, $font_size, 0, 260, 250, $black, $font, $text);

		$dst_path = PUBLIC_PATH . '/uploads/tmp/' . $filename;
		if (imagejpeg ($dst, $dst_path, 100)) return $dst_path;
		else die('could not textmark image');
	}
}




