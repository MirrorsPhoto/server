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
	 * @var mixed[]
	 */
	private $publicResources = [
		'index' =>
			[
				'notFound',
			],
		'auth' =>
			[
				'login',
				'appleLogin',
				'check',
			],
	];

	/**
	 * @var User
	 */
	protected static $user;

	/**
	 * @throws Unauthorized
	 */
	public static function getUser(): User
	{
		if (is_null(self::$user)) {
			throw new Unauthorized();
		}
		return self::$user;
	}

	private function setup(): void
	{
		$acl = new Memory();
		$acl->setDefaultAction(Acl::DENY);

		$this->acl = $acl;

		$this->setupRole();
		$this->setupResource();
	}

	private function setupRole(): void
	{
		$roles = \Role::find();

		/** @var \Role $role */
		foreach ($roles as $role) {
			$this->acl->addRole(new Role((string) $role->id, $role->name));

			$this->acl->allow((string) $role->id, '*', '*');//@todo для всех ролей разрешаем всё. в будущем нужно разбить
		}

		$this->acl->addRole(new Role((string) \Role::GUEST, 'Гость'));
	}

	private function setupResource(): void
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

	private function getAcl(): Memory
	{
		$this->setup();

		return $this->acl;
	}

	/**
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @throws AccessDenied
	 * @throws Unauthorized
	 */
	// @codingStandardsIgnoreLine SlevomatCodingStandard.Variables.UnusedVariable
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher): bool
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
			"token = '$token'",
		]);

		if (!$user) {
			$dispatcher->getDI()->get('response')->setHeader('WWW-Authenticate', 'Bearer realm="Bad Unauthorized"');
			throw new Unauthorized('auth.wrong_token');
		}

		$jwt = JWT::getInstance();

		$decoded = $jwt::decode($token);

		// Проверяем, имеет ли данная роль доступ к контроллеру (ресурсу)
		$allowed = (int) $acl->isAllowed($decoded->role_id, $controller, $action);

		if ($allowed !== Acl::ALLOW || $decoded->role_id !== $user->role_id || $decoded->id !== $user->id) {
			throw new AccessDenied();
		}

		$user->department_id = $user->currentDepartments->getFirst()->id;

		self::$user = $user;

		return true;
	}

}
