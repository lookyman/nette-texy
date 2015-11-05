<?php

namespace Lookyman\NetteTexy\Configurator;

use Texy\Texy;

interface ConfiguratorInterface
{
	public function configure(Texy $texy);
}
