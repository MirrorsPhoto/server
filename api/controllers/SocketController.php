<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;

/**
 * @RoutePrefix('/socket')
 */
class SocketController extends Controller
{

	/**
	 * @Get('/update')
	 *
	 * @throws Unauthorized
	 * @return string
	 */
	public function updateAction()
	{
		$department = Security::getUser()->getCurrentDepartments()->getLast();

		$department->notifyPersonnels();

		return 'socket.update.success';
	}
}
