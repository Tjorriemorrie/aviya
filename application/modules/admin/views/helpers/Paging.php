<?php

class Zend_View_Helper_Paging
{
    function paging($page, $pagesTotal)
    {
		$fc = Zend_Controller_Front::getInstance();
		$baseUrl = $fc->getBaseUrl();
		$controllerName = $fc->getRequest()->getControllerName();
		$actionName = $fc->getRequest()->getActionName();

		$href = $baseUrl . '/admin/' . $controllerName . '/page/number/';
		$msg = '';
		
		if ($pagesTotal > 1) {
			// previous
			if ($page > 1) {
				$msg .= '<a href="' . $href . ($page-1) . '">prev</a>';
			}
			
			// all pages
			for ($i=1; $i<=$pagesTotal; $i++) {
				$msg .= '<a ';
				if ($page == $i) $msg .= 'class="pageHere" ';
				$msg .= 'href="' . $href . $i . '">' . $i . '</a>';
			}
			
			// next
			if ($page < $pagesTotal) {
				$msg .= '<a href="' . $href . ($page+1) . '">next</a>';
			}
			
		} else {
			$msg .= '1';
		}

		return $msg;
    }
}
