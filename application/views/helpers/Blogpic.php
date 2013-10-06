<?php

class Zend_View_Helper_Blogpic
{
	function blogpic($blog)
	{
		$baseUrl = dirname(Zend_Controller_Front::getInstance()->getBaseUrl());
		$src = $baseUrl . '/uploads/blogs/' . $blog->picture;
		
		// check if picture file exists
		if (!empty($blog->picture)) {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . $src)) {
				return '<img class="clearRight imgRight" src="' . $src . '" alt="' . $blog->title . '" />';
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
}

