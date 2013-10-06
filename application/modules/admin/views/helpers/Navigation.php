<?php

class Zend_View_Helper_Navigation
{
    function navigation()
    {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		
		if (Zend_Auth::getInstance()->hasIdentity()) {
			
			echo '<ul id="adminNav">';
				echo '<li id="navDashboard"><a href="' . $baseUrl . '/admin" title="Administration Dashboard"></a></li>';
				echo '<li id="navAlbums"><a href="' . $baseUrl . '/admin/albums" "title="View albums"></a></li>';
				echo '<li id="navBlog"><a href="' . $baseUrl . '/admin/blog" "title="View blog"></a></li>';
				echo '<li id="navPoems"><a href="' . $baseUrl . '/admin/poems" "title="Poems"></a></li>';
				echo '<li id="navMessages"><a href="' . $baseUrl . '/admin/messages" "title="View messages"></a></li>';
				echo '<li id="navUsers"><a href="' . $baseUrl . '/admin/users" title="Users"></a></li>';
				echo '<li id="navLogout"><a href="' . $baseUrl . '/admin/auth/logout" "title="Log out"></a></li>';
			echo '</ul>';
			
		} else {
			
			echo '<ul id="navigation">';
				echo '<li id="navalbums"><a href="' . $baseUrl . '/albums" "title="Albums of Aviya"></a></li>';
				echo '<li id="navgallery"><a href="' . $baseUrl . '/gallery" title="Galleries of Aviya"></a></li>';
				echo '<li id="navblog"><a href="' . $baseUrl . '/blog" title="Blog of Aviya"></a></li>';
				echo '<li id="navpoems"><a href="' . $baseUrl . '/poems" title="Poems"></a></li>';
				echo '<li id="navabout"><a href="' . $baseUrl . '/about" title="About Aviya"></a></li>';
				echo '<li id="navcontact"><a href="' . $baseUrl . '/contact" title="Contact Aviya"></a></li>';
			echo '</ul>';
			
		}
    }
}
