<?php

abstract class Controller extends \Phalcon\Mvc\Controller
{

	protected function getQuery($name)
	{
		return $this->request->getQuery($name);
	}

	protected function getPost($name)
	{
		return $this->request->getPost($name);
	}

}