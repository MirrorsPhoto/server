<?php

class Router extends \Phalcon\Mvc\Router\Annotations
{

	public function __construct($defaultRoutes = null)
	{
		parent::__construct($defaultRoutes);

		$this->_setRoutes();
		$this->_setResources();

		$this->removeExtraSlashes(true);
	}

	private function _setResources()
	{
		$arrControllers = array_diff(scandir(__DIR__ . '/../api/controllers'), ['..', '.']);

		foreach ($arrControllers as $controller) {
			$name = str_replace('Controller.php', '', $controller);

			$prefix = '/' . strtolower($name);

			$this->addResource($name, $prefix);

		}
	}

	private function _setRoutes()
	{
		$routes = new \Phalcon\Config\Adapter\Ini(__DIR__ . '/../api/config/route.ini');

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

		$this->add(
			'/',
			[
				'controller' => 'index',
				'action'     => 'index',
			]
		);
	}

}