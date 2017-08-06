<?php

namespace Core\UserCenter;

use Core\UserCenter\Exception\AccessDenied;
use Core\UserCenter\Exception\Unauthorized;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

class Security extends Plugin
{

	private function _getAcl()
	{
		$userEnum = Enum::getInstance();

		$acl = new AclList();
		$acl->setDefaultAction(Acl::DENY);

		//Регистрация роллей
		foreach ($userEnum->getAll() as $name => $value)
		{
			$acl->addRole(new Role($name, $value));
		}

		//Public area resources
		$publicResources = [
			'index' =>
				[
					'index'
				],
			'auth' =>
				[
					'login',
				]
		];

		foreach ($publicResources as $resource => $actions)
		{
			$acl->addResource(new Acl\Resource($resource), $actions);
		}

		//Grant access to public areas to both users and guests
		foreach ($publicResources as $resource => $actions)
		{
			foreach ($actions as $action)
			{
				$acl->allow('*', $resource, $action);
			}
		}

		//Разрешаем для группы ADMIN ВЕЗДЕ доступ
		$acl->allow($userEnum->getName($userEnum::ADMIN), '*', '*');

		return $acl;
	}

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$userEnum = Enum::getInstance();

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		// Получаем список ACL
		$acl = $this->_getAcl();

		if ($acl->isAllowed($userEnum->getName($userEnum::GUEST), $controller, $action)) return true;

		$request = $dispatcher->getDI()->get('request');

		if (!$header = $request->getHeader('Authorization')) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Unauthorized"');
			throw new Unauthorized();
		}

		$token = explode(' ', $header)[1];

		if (!$token) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Bad Unauthorized"');
			throw new Unauthorized();
		}

		$jwt = \JWT::getInstance();

		$decoded = $jwt::decode($token);

		$role = $decoded->type;

		// Проверяем, имеет ли данная роль доступ к контроллеру (ресурсу)
		$allowed = $acl->isAllowed($userEnum->getName($role), $controller, $action);
		if ($allowed != Acl::ALLOW)
		{
			throw new AccessDenied($controller, $action, $role);
		}
	}
}