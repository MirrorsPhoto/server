<?php

abstract class Model extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 * @Identity
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $id;

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		$this->setSchema("public");
	}

	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource()
	{
		return $this->_tableName;
	}

}