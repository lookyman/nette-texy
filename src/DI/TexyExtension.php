<?php

namespace Lookyman\NetteTexy\DI;

use Lookyman\NetteTexy\Configurator\ConfiguratorInterface;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceCreationException;
use Nette\DI\ServiceDefinition;
use Texy\Texy;

class TexyExtension extends CompilerExtension
{
	const TAG_CONFIGURATOR = 'texy.configurator';

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('texy'))
			->setClass(Texy::class);
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$texy = $builder->getDefinition($this->prefix('texy'));
		$configurators = $this->getSortedConfigurators($builder);
		foreach ($configurators as $name => $priority) {
			if (!self::isOfType($builder->getDefinition($name)->getClass(), ConfiguratorInterface::class)) {
				throw new ServiceCreationException();
			}
			$texy->addSetup('?->configure(?)', [
				sprintf('@%s', $name), '@self'
			]);
		}

		$latteFactory = $builder->getByType('Nette\Bridges\ApplicationLatte\ILatteFactory');
		if ($latteFactory === null || !self::isOfType($builder->getDefinition($latteFactory)->getClass(), 'Latte\Engine')) {
			$latteFactory = 'nette.latteFactory';
		}

		if ($builder->hasDefinition($latteFactory) && self::isOfType($builder->getDefinition($latteFactory)->getClass(), 'Latte\Engine')) {
			$this->registerToLatte($builder->getDefinition($latteFactory));
		}

		if ($builder->hasDefinition('nette.latte')) {
			$this->registerToLatte($builder->getDefinition('nette.latte'));
		}
	}

	/**
	 * @param ContainerBuilder $builder
	 * @return array
	 */
	private function getSortedConfigurators(ContainerBuilder $builder)
	{
		$configurators = $builder->findByTag(self::TAG_CONFIGURATOR);
		uasort($configurators, function ($a, $b) {
			$a = is_numeric($a) ? (float) $a : 0;
			$b = is_numeric($b) ? (float) $b : 0;
			return $a < $b ? -1 : ($a > $b ? 1 : 0);
		});
		return $configurators;
	}

	/**
	 * @param ServiceDefinition $def
	 */
	private function registerToLatte(ServiceDefinition $def)
	{
		$def->addSetup('?->onCompile[] = function (Latte\Engine $engine) { (new Texy\Bridges\Latte\TexyMacro($engine, ?))->install(); }', ['@self', $this->prefix('@texy')])
			->addSetup('addFilter', ['texy', [$this->prefix('@texy'), 'process']]);

		if (method_exists('Latte\Engine', 'addProvider')) {
			$def->addSetup('addProvider', ['texy', $this->prefix('@texy')]);

		} else {
			$def->addSetup('?->addFilter(\'getTexy\', function () { return ?;})', ['@self', $this->prefix('@texy')]);
		}
	}

	/**
	 * @param string $class
	 * @param string $type
	 * @return bool
	 */
	private static function isOfType($class, $type)
	{
		return $class === $type || is_subclass_of($class, $type);
	}
}
