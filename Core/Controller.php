<?php

abstract class Controller extends \Phalcon\Mvc\Controller
{

	protected function getQuery($name = null)
	{
		return $this->request->getQuery($name);
	}

	protected function getPost($name = null)
	{
		return $this->request->getPost($name);
	}

}