<?php

class Router extends \Phalcon\Mvc\Router\Annotations
{

	public function __construct($defaultRoutes = null)
	{
		$this->_setRoutes();
		$this->_setResources();

		parent::__construct($defaultRoutes);
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
		$this->addPost('/login', [
			'controller' => 'auth',
			'action'     => 'login',
		]);

		$this->notFound(
			[
				'controller' => 'index',
				'action'     => 'notFound',
			]
		);

	}

}