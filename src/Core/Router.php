<?php

use Phalcon\Config\Adapter\Ini;

class Router extends Phalcon\Mvc\Router\Annotations
{

	public function __construct(bool $defaultRoutes = false)
	{
		parent::__construct($defaultRoutes);

		$this->setRoutes();
		$this->setResources();

		$this->removeExtraSlashes(true);
	}

	private function setResources(): void
	{
		$arrControllers = array_diff(scandir(__DIR__ . '/../controllers'), ['..', '.']);

		foreach ($arrControllers as $controller) {
			$name = str_replace('Controller.php', '', $controller);

			$prefix = '/' . strtolower($name);

			$this->addResource($name, $prefix);
		}
	}

	private function setRoutes(): void
	{
		$routes = new Ini(__DIR__ . '/../config/route.ini');

		foreach ($routes->toArray() as $pattern => $paths) {
			$pattern = "/$pattern";

			$method = $paths['method'] ?? 'Get';

			$methodName = "add{$method}";

			$this->{$methodName}($pattern, $paths);
		}

		$this->notFound(
			[
				'controller' => 'index',
				'action'     => 'notFound',
			]
		);
	}

}
