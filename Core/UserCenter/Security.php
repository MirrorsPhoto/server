<?php

namespace Core\UserCenter;

use Core\UserCenter\Exception\AccessDenied;
use Core\UserCenter\Exception\Unauthorized;
use JWT;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory;
use User;

class Security extends Plugin
{
	/**
	 * @var Memory
	 */
	private $acl;

	/**
	 * @var array
	 */
	private $publicResources = [
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

	/**
	 * @var User
	 */
	protected static $user;

	/**
	 * @throws Unauthorized
	 * @return User
	 */
	public static function getUser()
	{
		if (is_null(self::$user)) {
			throw new Unauthorized();
		}
		return self::$user;
	}

	/**
	 * @return void
	 */
	private function setup()
	{
		$acl = new Memory();
		$acl->setDefaultAction(Acl::DENY);

		$this->acl = $acl;

		$this->setupRole();
		$this->setupResource();

		$this->acl->allow(\Role::ADMIN, '*', '*');
	}

	/**
	 * @return void
	 */
	private function setupRole()
	{
		$roles = \Role::find();

		/** @var \Role $role */
		foreach ($roles as $role) {
			$this->acl->addRole(new Role((string)$role->id, $role->name));
		}
	}

	/**
	 * @return void
	 */
	private function setupResource()
	{
		foreach ($this->publicResources as $resource => $actions) {
			$this->acl->addResource(new Acl\Resource($resource), $actions);
		}

		//Grant access to public areas to both users and guests
		foreach ($this->publicResources as $resource => $actions) {
			foreach ($actions as $action) {
				$this->acl->allow('*', $resource, $action);
			}
		}
	}

	/**
	 * @return Memory
	 */
	private function getAcl()
	{
		$this->setup();

		return $this->acl;
	}

	/**
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @throws AccessDenied
	 * @throws Unauthorized
	 * @return bool
	 */
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		// Получаем список ACL
		$acl = $this->getAcl();

		if ($acl->isAllowed(\Role::GUEST, $controller, $action)) {
			return true;
		}
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

		/** @var User $user */
		$user = User::findFirst([
			"token = '$token'"
		]);

		if (!$user) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Bad Unauthorized"');
			throw new Unauthorized();
		}

		$jwt = JWT::getInstance();

		$decoded = $jwt::decode($token);

		// Проверяем, имеет ли данная роль доступ к контроллеру (ресурсу)
		$allowed = $acl->isAllowed($decoded->role_id, $controller, $action);
		if ($allowed != Acl::ALLOW || $decoded->role_id != $user->role_id || $decoded->id != $user->id) {
			throw new AccessDenied();
		}

		$user->department_id = $user->currentDepartments->getFirst()->id;

		self::$user = $user;

		return true;
	}
}
