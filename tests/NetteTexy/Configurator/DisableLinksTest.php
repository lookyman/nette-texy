<?php

namespace Lookyman\NetteTexy\Tests\Configurator;

use Lookyman\NetteTexy\Configurator\DisableLinks;
use Texy\Texy;

class DisableLinksTest extends \PHPUnit_Framework_TestCase
{
	public function testConfigure()
	{
		$texy = new Texy();
		self::assertTrue($texy->allowed['link/reference']);
		(new DisableLinks())->configure($texy);
		self::assertFalse($texy->allowed['link/reference']);
	}
}
