<?php

namespace Texy;

use Nette\Object;
use Nette\Utils\Html;


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


	/**
	 * @param string
	 * @return \Nette\Utils\Html
	 */
	public function processUncached($text, $singleLine = FALSE)
	{
		if (!$this->texy instanceof Texy) {
			throw new \Nette\InvalidStateException("Texy is not an instance of Texy\Texy.");
		}
		return Html::el()->setHtml($this->texy->processUncached($text, $singleLine));
	}

}
