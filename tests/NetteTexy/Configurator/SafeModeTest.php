<?php

namespace Lookyman\NetteTexy\Tests\Configurator;

use Lookyman\NetteTexy\Configurator\SafeMode;
use Texy\Texy;

class SafeModeTest extends \PHPUnit_Framework_TestCase
{
	public function testConfigure()
	{
		$texy = new Texy();
		self::assertTrue($texy->allowedClasses);
		(new SafeMode())->configure($texy);
		self::assertFalse($texy->allowedClasses);
	}
}
