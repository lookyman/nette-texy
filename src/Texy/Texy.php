<?php

namespace Texy;

use Nette\Caching\IStorage,
	Nette\Caching\Cache;


class Texy extends \Texy
{
	
	/** @var \Texy\ITexyConfigurator[] */
	protected $configurators = array();
	
	
	/** @var \Nette\Caching\IStorage */
	protected $storage;
	
	
	public function __construct(IStorage $storage)
	{
		parent::__construct();
		$this->storage = $storage;
	}
	
	
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
	
	
	public function process($text, $singleLine = FALSE)
	{
		$key = array($text, $singleLine);
		$cache = new Cache($this->storage, str_replace('\\', '.', get_class()));
		if (NULL === ($html = $cache->load($key))) {
			$html = parent::process($text, $singleLine);
			$cache->save($key, $html);
		}
		
		return $html;
	}
	
}
