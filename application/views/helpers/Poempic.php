<?php

class Zend_View_Helper_Poempic
{
	function poempic($poem)
	{
		$baseUrl = dirname(Zend_Controller_Front::getInstance()->getBaseUrl());
		$src = $baseUrl . '/uploads/poems/' . $poem->picture;
		
		// check if picture file exists
		if (!empty($poem->picture)) {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . $src)) {
				return '<img class="clearRight imgRight" src="' . $src . '" alt="' . $poem->title . '" />';
			}
		}
	}
}

