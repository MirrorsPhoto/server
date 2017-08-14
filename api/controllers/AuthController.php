<?php

/**
 * @RoutePrefix('/auth')
 */
class AuthController extends Controller
{

	/**
	 * @Get('/login')
	 */
	public function loginAction()
	{
//		$a = Users::findFirst();
//Debug::dumpMethodDie($a);
		return 'Страница авторизации';
	}

}