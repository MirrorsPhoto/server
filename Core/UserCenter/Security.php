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
	private $_acl;

	private $_publicResources = [
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

	private function _setup()
	{
		$acl = new AclList();
		$acl->setDefaultAction(Acl::DENY);

		$this->_acl = $acl;

		$this->_setupRole();
		$this->_setupResource();

		$this->_acl->allow(\Role::ADMIN, '*', '*');
	}

	private function _setupRole()
	{
		$roles = \Role::find();

		foreach ($roles as $role)
		{
			$this->_acl->addRole(new Role((string)$role->id, $role->name));
		}
	}

	private function _setupResource()
	{
		foreach ($this->_publicResources as $resource => $actions)
		{
			$this->_acl->addResource(new Acl\Resource($resource), $actions);
		}

		//Grant access to public areas to both users and guests
		foreach ($this->_publicResources as $resource => $actions)
		{
			foreach ($actions as $action)
			{
				$this->_acl->allow('*', $resource, $action);
			}
		}
	}

	private function _getAcl()
	{
		$this->_setup();

		return $this->_acl;
	}

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		// Получаем список ACL
		$acl = $this->_getAcl();

		if ($acl->isAllowed(\Role::GUEST, $controller, $action)) return true;
        $header = $dispatcher->getDI()->get('request')->getHeader('Authorization');

		if (!$header) {
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
		$allowed = $acl->isAllowed($decoded->role_id, $controller, $action);
		if ($allowed != Acl::ALLOW || $decoded->role_id != $user->role_id || $decoded->id != $user->id)
		{
			throw new AccessDenied($controller, $action, $user->role_id);
		}

		$user->department_id = $user->currentDepartments->getFirst()->id;

		self::$_user = $user;

		return true;
	}
}