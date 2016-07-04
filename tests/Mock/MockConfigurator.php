<?php

namespace Lookyman\NetteTexy\Tests\Mock;

use Lookyman\NetteTexy\Configurator\ConfiguratorInterface;
use Texy\Texy;

class MockConfigurator implements ConfiguratorInterface
{
	/** @var mixed */
	private $priority;

	/**
	 * @param mixed $priority
	 */
	public function setPriority($priority)
	{
		$this->priority = $priority;
	}

	public function configure(Texy $texy)
	{
		echo $this->priority;
	}
}
