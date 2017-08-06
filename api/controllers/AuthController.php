<?php

use Phalcon\Mvc\Controller;

class AuthController extends Controller
{

	public function loginAction()
	{
		$a = Users::findFirst();
//Debug::dumpMethodDie($a);
		return $a;
	}

}