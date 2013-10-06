<?php

class Admin_Form_Login extends Zend_Form
{
	public function init()
	{
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Username:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
			
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
			
		$captcha = new Zend_Form_Element_Captcha('captcha', array(
	        'captcha' => 'ReCaptcha',
	        'captchaOptions' => array(
		        'captcha' => 'ReCaptcha',
		        'pubKey' => Zend_Registry::get('pubKey'),
		        'privKey' => Zend_Registry::get('privKey')
        	)
	    ));
	    $captcha->setLabel('Verification:');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');

		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setLabel('Cancel');


		$this->addElements(array($username, $password, $captcha, $submit, $cancel))
			->setName('loginForm')
			->setMethod('post')
			->setDecorators(array(
				array('Description'),
				array('FormElements'),
				array('HtmlTag', array('tag' => 'dl')),
				array('Form')
			))
		;
	}	
}