<?php

abstract class Controller extends \Phalcon\Mvc\Controller
{
	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	protected function getQuery(string $name)
	{
		return $this->request->getQuery($name);
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	protected function getPost(string $name)
	{
		return $this->request->getPost($name);
	}
}
