<?php

namespace Texy;


class Texy extends \Texy
{
	
	/** @var \Texy\ITexyConfigurator[] */
	protected $configurators = array();
	
	
	/**
	 * @return \Texy\TemplateHelpers
	 */
	public function createTemplateHelpers()
	{
		return new TemplateHelpers($this);
	}
	
	
	public function addConfigurator(ITexyConfigurator $configurator)
	{
		$this->configurators[] = $configurator;
	}
	
	
	public function configure()
	{
		foreach ($this->configurators as $configurator) {
			$configurator->configure($this);
		}
	}
	
}
