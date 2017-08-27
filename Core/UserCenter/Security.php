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

	protected static $_user;

	public static function getUser()
	{
		if (is_null(self::$_user))
		{
			throw new Unauthorized();
		}
		return self::$_user;
	}

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
					'index',
					'notFound',
					'deploy'
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

		if (!$header = $request->getHeader('AuthorizationS')) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Unauthorized"');
			throw new Unauthorized();
		}

		list($token) = sscanf($header, 'Bearer %s');
		
		if (!$token) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Bad Unauthorized"');
			throw new Unauthorized();
		}

		$user = \User::findFirst([
			"token = '$token'"
		]);

		if (!$user) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Bad Unauthorized"');
			throw new Unauthorized();
		}

		$jwt = \JWT::getInstance();

		$decoded = $jwt::decode($token);

		// Проверяем, имеет ли данная роль доступ к контроллеру (ресурсу)
		$allowed = $acl->isAllowed($userEnum->getName($decoded->role), $controller, $action);
		if ($allowed != Acl::ALLOW || $decoded->role != $user->role || $decoded->id != $user->id)
		{
			throw new AccessDenied($controller, $action, $user->role);
		}

		self::$_user = $user;

		return true;
	}
}