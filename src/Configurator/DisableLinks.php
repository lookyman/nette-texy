<?php

namespace Lookyman\NetteTexy\Configurator;

use Texy\Configurator;
use Texy\Texy;

class DisableLinks implements ConfiguratorInterface
{
	public function configure(Texy $texy)
	{
		Configurator::disableLinks($texy);
	}
}
