<?php

namespace Lookyman\NetteTexy\Configurator;

use Texy\Configurator;
use Texy\Texy;

class DisableImages implements ConfiguratorInterface
{
	public function configure(Texy $texy)
	{
		Configurator::disableImages($texy);
	}
}
