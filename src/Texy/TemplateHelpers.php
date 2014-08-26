<?php

namespace Texy;

use Nette\Object,
	Nette\Utils\Html;


class TemplateHelpers extends Object
{
	
	/** @var \Texy */
	protected $texy;
	
	
	public function __construct(\Texy $texy)
	{
		$this->texy = $texy;
	}
	
	
	/**
	 * @param string
	 * @return \Nette\Utils\Html
	 */
	public function process($text, $singleLine = FALSE)
	{
		return Html::el()->setHtml($this->texy->process($text, $singleLine));
	}
	
}
