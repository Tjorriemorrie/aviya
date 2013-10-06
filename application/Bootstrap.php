<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH,
        ));
        return $autoloader;
    }


    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        //$view->headTitle('My First Zend Framework Application');

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    
    protected function _initDbAdapter()
    {
		$dbAdapter = $this->getPluginResource('db')->getDbAdapter();
        Zend_Registry::set('dbAdapter', $dbAdapter);
    }
    
    
    protected function _initTimezone()
    {
		date_default_timezone_set('Africa/Johannesburg');
	}
    
    
    protected function _initRecaptcha()
    {
		Zend_Registry::set('privKey', '6LdcTAYAAAAAAHfmi3Qt-vphGoPKQ1JanRXM31_y');
		Zend_Registry::set('pubKey', '6LdcTAYAAAAAAEG3_S99eAtFJIvN0eKQR5-TeeyD');
	}
	
	
	protected function _initCache()
	{
		$frontendOptions = array(
		   'lifetime' => 86400 * 30, // cache lifetime of 1 month
		   'automatic_serialization' => true
		);
		
		$backendOptions = array(
		    'cache_dir' => APPLICATION_PATH . '/cache/' // Directory where to put the cache files
		);
		
		// getting a Zend_Cache_Core object
		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		Zend_Registry::set('cache', $cache);
	}
}
