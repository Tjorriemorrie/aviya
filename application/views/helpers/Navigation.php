<?php

class Zend_View_Helper_Navigation
{
    function navigation()
    {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		
		if (Zend_Auth::getInstance()->hasIdentity()) {
			
			echo '<ul id="adminNav">';
				echo '<li id="navDashboard"><a href="' . $baseUrl . '/admin/dashboard" title="Dashboard"></a></li>';
				echo '<li id="navAlbums"><a href="' . $baseUrl . '/admin/albums" "title="View albums"></a></li>';
				echo '<li id="navBlogs"><a href="' . $baseUrl . '/admin/blogs" "title="View blogs"></a></li>';
				echo '<li id="navPoems"><a href="' . $baseUrl . '/poems/all" "title="Poems"></a></li>';
				echo '<li id="navMessages"><a href="' . $baseUrl . '/messages/all" "title="View messages"></a></li>';
				echo '<li id="navUsers"><a href="' . $baseUrl . '/users" title="Users"></a></li>';
				echo '<li id="navLogout"><a href="' . $baseUrl . '/auth/logout" "title="Log out"></a></li>';
			echo '</ul>';
			
		} else {
			
			echo '<ul>';
				echo '<li id="navAlbums"><a href="' . $baseUrl . '/albums"></a></li>';
				echo '<li id="navGallery"><a href="' . $baseUrl . '/gallery"></a></li>';
				echo '<li id="navBlog"><a href="' . $baseUrl . '/blog"></a></li>';
				echo '<li id="navPoems"><a href="' . $baseUrl . '/poems"></a></li>';
				echo '<li id="navAbout"><a href="' . $baseUrl . '/about"></a></li>';
				echo '<li id="navContact"><a href="' . $baseUrl . '/contact"></a></li>';
			echo '</ul>';
			
		}
    }
}
