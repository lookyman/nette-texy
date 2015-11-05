<?php

namespace Lookyman\NetteTexy\Configurator;

use Texy\Configurator;
use Texy\Texy;

class SafeMode implements ConfiguratorInterface
{
	public function configure(Texy $texy)
	{
		Configurator::safeMode($texy);
	}
}
