<?php

class Form_Album extends Zend_Form
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
			->addValidator('Alpha', true, true);
									
		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel('Description:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
									
		$destination = PUBLIC_PATH . '/uploads/albums';
		$cover = new Zend_Form_Element_File('cover');
		$cover->setLabel('Cover:')
			->setDestination($destination)
			->setRequired(false)
			->addValidator('Count', false, 1)
			->addValidator('Extension', false, 'jpg, png, gif')
			->addValidator('Size', false, 2000000)
			->setMaxFileSize(2000000);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		
		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setLabel('Cancel');
		
		
		$this->addElements(array($hash, $title, $description, $cover, $submit, $cancel))
			->setName('albumForm')
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