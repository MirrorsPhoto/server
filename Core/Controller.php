<?php

abstract class Controller extends \Phalcon\Mvc\Controller
{

	/**
	 * @param string $name
	 * @return mixed
	 */
	protected function getQuery($name = null)
	{
		return $this->request->getQuery($name);
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	protected function getPost($name = null)
	{
		return $this->request->getPost($name);
	}

}