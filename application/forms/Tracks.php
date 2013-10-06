<?php

class Form_Tracks extends Zend_Form
{
	public function init()
	{
		$hash = new Zend_Form_Element_Hash('hash');
		$hash->setSalt('aviya');
		
		$trackTitle = new Zend_Form_Element_Text('trackTitle');
		$trackTitle->setLabel('Track Title:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
									
		$trackLyrics = new Zend_Form_Element_Textarea('trackLyrics');
		$trackLyrics->setLabel('Track Lyrics:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty', true);
									
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		
		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setLabel('Cancel');
		
		
		$this->addElements(array($hash, $trackTitle, $trackLyrics, $submit, $cancel))
			->setName('tracksForm')
			->setMethod('post')
			//->setAttrib('enctype', 'multipart/form-data')
			->setDecorators(array(
				array('Description'),
				array('FormElements'),
				array('HtmlTag', array('tag' => 'dl')),
				array('Form')
			))
		;
	}
}