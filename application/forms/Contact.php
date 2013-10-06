<?php

class Form_Contact extends Zend_Form
{
	public function init()
	{
		$hash = new Zend_Form_Element_Hash('hash');
		$hash->setSalt('aviya');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true)
			->addValidator('Alpha', true, true);
			
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true)
			->addValidator('EmailAddress', true);
			
		$subject = new Zend_Form_Element_Text('subject');
		$subject->setLabel('Subject:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
			
		$message = new Zend_Form_Element_Textarea('message');
		$message->setLabel('Message:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
			
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Send');
		
		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setLabel('Cancel');
		

		$this
			->addElements(array($hash, $name, $email, $subject, $message, $submit, $cancel))
			->setName('contactForm')
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