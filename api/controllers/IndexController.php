<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

	public function indexAction()
	{
		$a = Users::findFirst();
//Debug::dumpMethodDie($a);
		return $a;
	}

}