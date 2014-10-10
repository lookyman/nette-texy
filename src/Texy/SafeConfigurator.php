<?php

namespace Texy;

use Nette\Object;


class SafeConfigurator extends Object implements ITexyConfigurator
{

	public function configure(\Texy $texy)
	{
		\TexyConfigurator::safeMode($texy);
	}

}
