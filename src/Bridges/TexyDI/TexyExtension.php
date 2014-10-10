<?php

namespace Nette\Bridges\TexyDI;

use Nette\DI\CompilerExtension;
use	Nette\Utils\Validators;


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

		$builder->addDefinition($this->prefix('texy'))
			->setClass('Texy\Texy');

		Validators::assertField($config, 'safeMode', 'boolean');
		if ($config['safeMode']) {
			$builder->addDefinition($this->prefix('safeConfigurator'))
				->setClass('Texy\SafeConfigurator')
				->addTag($this->prefix('configurator'));
		}

		$builder->addDefinition($this->prefix('helpers'))
			->setClass('Texy\TemplateHelpers')
			->setFactory($this->prefix('@texy') . '::createTemplateHelpers')
			->setInject(FALSE);
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$texy = $builder->getDefinition($this->prefix('texy'));

		$configurators = $builder->findByTag($this->prefix('configurator'));
		$this->sortConfigurators($configurators);

		foreach (array_keys($configurators) as $name) {
			$builder->getDefinition($name)
				->setAutowired(FALSE)
				->setInject(FALSE);
			$texy->addSetup('addConfigurator', array('@' . $name));
		}

		if (!empty($configurators)) {
			$texy->addSetup('configure');
		}

		$latteFactory = $builder->hasDefinition('nette.latteFactory')
			? $builder->getDefinition('nette.latteFactory')
			: $builder->getDefinition('nette.latte');

		$latteFactory->addSetup('addFilter', array('texy', array($this->prefix('@helpers'), 'process')));
	}


	protected function sortConfigurators(array & $configurators)
	{
		uasort($configurators, function($a, $b) {
			if (!is_int($a)) {
				$a = 0;
			}
			if (!is_int($b)) {
				$b = 0;
			}
			return $a === $b ? 0 : ($a < $b ? 1 : -1);
		});
	}


	private function validate(array $config, array $expected, $name)
	{
		if ($extra = array_diff_key($config, $expected)) {
			$extra = implode(", $name.", array_keys($extra));
			throw new InvalidStateException("Unknown option $name.$extra.");
		}
	}

}
