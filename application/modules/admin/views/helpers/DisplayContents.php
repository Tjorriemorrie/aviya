<?php

class Zend_View_Helper_DisplayContents
{
    function displayContents($text, $width)
    {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

		$lines = explode("\n", $text);
		$linesNum = sizeof($lines) * 3;
		
		echo '<textarea class="textRead" style="width:' . $width . 'px" readonly>';
			
			echo stripslashes($text);
			
		echo '</textarea>';
    }
}
