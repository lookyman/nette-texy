<?php

namespace Lookyman\NetteTexy\Tests\DI;

use Latte\Engine;
use Latte\Loaders\StringLoader;
use Lookyman\NetteTexy\DI\TexyExtension;
use Lookyman\NetteTexy\Tests\Mock\MockConfigurator;
use Nette\Bridges\ApplicationDI\LatteExtension;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Texy\Texy;

class TexyExtensionTest extends \PHPUnit_Framework_TestCase
{
	public function testExtension()
	{
		$container = $this->createContainer([
			'latte' => new LatteExtension(TEMP_DIR, true),
			'texy' => new TexyExtension(),
		], [
			'services' => [
				['class' => MockConfigurator::class, 'tags' => [TexyExtension::TAG_CONFIGURATOR => 3]],
				['class' => MockConfigurator::class, 'tags' => [TexyExtension::TAG_CONFIGURATOR => 1]],
				['class' => MockConfigurator::class, 'tags' => [TexyExtension::TAG_CONFIGURATOR => 2]],
			],
		]);

		foreach ($container->findByTag(TexyExtension::TAG_CONFIGURATOR) as $name => $priority) {
			$container->getService($name)->setPriority($priority);
		}

		self::expectOutputString('123');
		self::assertInstanceOf(Texy::class, $container->getByType(Texy::class));

		/** @var Engine $latte */
		$latte = $container->getByType(ILatteFactory::class)->create();
		$latte->setLoader(new StringLoader());
		self::assertEquals("<p>a</p>\n&lt;p&gt;b&lt;/p&gt;\n", $latte->renderToString('{texy}a{/texy}{$var |texy}', ['var' => 'b']));
	}

	/**
	 * @param array $extensions
	 * @param array $config
	 * @return Container
	 */
	private function createContainer(array $extensions = [], array $config = [])
	{
		$args = [function (Compiler $compiler) use ($extensions, $config) {
			foreach ($extensions as $name => $extension) {
				$compiler->addExtension($name, $extension);
			}
			$compiler->addConfig($config);
			$code = $compiler->compile();
			return is_array($code) ? implode("\n\n\n", $code) : $code; // https://github.com/nette/di/commit/0ed70b30ee9932412cf3016380679a0ffd3e30c1
		}];

		$loader = new ContainerLoader(TEMP_DIR, true);
		if ((new \ReflectionMethod($loader, 'load'))->getNumberOfRequiredParameters() > 1) { // https://github.com/nette/di/commit/d0a885678727e40e704fc562f1e4c296a6c6fc16
			array_unshift($args, '');
		}
		/** @var string $class */
		$class = call_user_func_array([$loader, 'load'], $args);
		return new $class();
	}
}
