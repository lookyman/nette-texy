<?php

namespace Texy;


class Texy extends \Texy
{
	
	/**
	 * @return \Texy\TemplateHelpers
	 */
	public function createTemplateHelpers()
	{
		return new TemplateHelpers($this);
	}
	
}
