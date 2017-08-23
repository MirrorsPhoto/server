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

	public function save($data = null, $whiteList = null)
	{
		if (method_exists($this,'beforeSave')) $this->beforeSave();

		if (parent::save($data, $whiteList)) return true;

		$messages = [];

		foreach ($this->getMessages() as $message) {
			$messages[] = $message->getMessage();
		}

		throw new \Core\Exception\ServerError($messages);
	}

}