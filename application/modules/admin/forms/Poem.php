<?php

class Admin_Form_Poem extends Zend_Form
{
	public function init()
	{
		$hash = new Zend_Form_Element_Hash('hash');
		$hash->setSalt('aviya');
		
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true)
			->addValidator('Alnum', true, true);
		
		$date = new Zend_Form_Element_Text('date');
		$date->setLabel('Date:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true)
			->addValidator('StringLength', true, array('min'=>10, 'max'=>10));
			
		$content = new Zend_Form_Element_Textarea('content');
		$content->setLabel('Content:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
			
		$picture = new Zend_Form_Element_File('picture');
		$picture->setLabel('Picture:')
			->setDestination(PUBLIC_PATH . '/uploads')
			->setRequired(false)
			->addValidator('Count', false, 1)
			->addValidator('Extension', false, 'jpg, png, gif')
			->addValidator('Size', false, 20000000)
			->setMaxFileSize(20000000);
						
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save');
		
		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setLabel('Cancel');
		
		$this->addElements(array($title, $date, $content, $picture, $submit, $cancel))
			->setName('poemForm')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')
			->setDecorators(array(
				array('Description'),
				array('FormElements'),
				array('HtmlTag', array('tag' => 'dl')),
				array('Form')
			))
		;
	}
}