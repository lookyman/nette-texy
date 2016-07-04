<?php

namespace Lookyman\NetteTexy\Tests\Configurator;

use Lookyman\NetteTexy\Configurator\DisableImages;
use Texy\Texy;

class DisableImagesTest extends \PHPUnit_Framework_TestCase
{
	public function testConfigure()
	{
		$texy = new Texy();
		self::assertTrue($texy->allowed['image']);
		(new DisableImages())->configure($texy);
		self::assertFalse($texy->allowed['image']);
	}
}
