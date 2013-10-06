<?php

class Zend_View_Helper_Lastlogin
{
	function lastlogin($user)
	{
		$baseUrl = dirname(Zend_Controller_Front::getInstance()->getBaseUrl());
		
		echo '<span class="italic smaller"> ';
		
			$lapse = time() - $user->date_login;
			$days = date('j', $lapse);
			if ($days < 1) echo 'last logged in today.';
			elseif ($days < 2) echo 'last logged in yesterday.';
			else echo 'last logged in ' . $days . ' days ago.';
		
		echo '</span>';
	}
}

