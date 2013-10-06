<?php

class Admin_Form_Yesno extends Zend_Form
{
	public function init()
	{
		$hash = new Zend_Form_Element_Hash('hash');
		$hash->setSalt('aviya');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Yes');
		
		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setLabel('No');
		
		
		$this->addElements(array($submit, $cancel))
			->setName('yesnoForm')
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

