<?php

/**
 * @RoutePrefix('/socket')
 */
class SocketController extends Controller
{

	/**
	 * @Get('/update')
	 */
	public function updateAction()
	{
		$department = \Core\UserCenter\Security::getUser()->getCurrentDepartments()->getLast();

		$department->notifyPersonnels();
	}

}