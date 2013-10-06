<?php

class Zend_View_Helper_TextBox
{
	public function textBox($text)
	{
		$lines = explode("\n", $text);
		echo '<textarea class="textBox" rows="' . (sizeof($lines)+5) . '">' . $text . '</textarea>';
	}	
}
