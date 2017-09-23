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

	/**
	 * @return \User
	 * @throws Unauthorized
	 */
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
		$acl = new AclList();
		$acl->setDefaultAction(Acl::DENY);
		//Регистрация роллей
		foreach ($roles as $role)
		{
			$acl->addRole(new Role((string)$role->id, $role->name));
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
		$acl->allow(\Role::ADMIN, '*', '*');

		return $acl;
	}

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$roles = \Role::find();

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		// Получаем список ACL
		$acl = $this->_getAcl();

		if ($acl->isAllowed(\Role::GUEST, $controller, $action)) return true;

		if (!isset(getallheaders()['Authorization'])) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Unauthorized"');
			throw new Unauthorized();
		}

		$header = getallheaders()['Authorization'];

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
		$allowed = $acl->isAllowed($decoded->role_id, $controller, $action);
		if ($allowed != Acl::ALLOW || $decoded->role_id != $user->role_id || $decoded->id != $user->id)
		{
			throw new AccessDenied($controller, $action, $user->role_id);
		}

		self::$_user = $user;

		return true;
	}
}