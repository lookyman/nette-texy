<?php

namespace Nette\Bridges\TexyDI;

use Nette\DI\CompilerExtension,
	Nette\Utils\Validators,
	Nette\InvalidStateException;


class TexyExtension extends CompilerExtension
{
	
	/** @var array */
	protected $defaults = array(
		'safeMode' => FALSE
	);
	
	
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		
		$this->validate($config, $this->defaults, $this->name);
		
		$texy = $builder->addDefinition($this->prefix('texy'))
			->setClass('Texy\Texy');

		Validators::assertField($config, 'safeMode', 'boolean');
		if ($config['safeMode']) {
			$texy->addSetup('TexyConfigurator::safeMode', array('@self'));
		}
		
		$builder->addDefinition($this->prefix('helpers'))
			->setClass('Texy\TemplateHelpers')
			->setFactory($this->prefix('@texy') . '::createTemplateHelpers')
			->setInject(FALSE);
		
		$latteFactory = $builder->hasDefinition('nette.latteFactory')
			? $builder->getDefinition('nette.latteFactory')
			: $builder->getDefinition('nette.latte');

		$latteFactory->addSetup('addFilter', array('texy', array($this->prefix('@helpers'), 'process')));
	}
	
	
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		
		$texy = $builder->getDefinition($this->prefix('texy'));
		foreach ($builder->findByTag($this->prefix('configurator')) as $name => $foo) {
			$texy->addSetup('addConfigurator', array("@$name"));
		}
		$texy->addSetup('configure');
	}
	
	
	private function validate(array $config, array $expected, $name)
	{
		if ($extra = array_diff_key($config, $expected)) {
			$extra = implode(", $name.", array_keys($extra));
			throw new InvalidStateException("Unknown option $name.$extra.");
		}
	}
	
}
