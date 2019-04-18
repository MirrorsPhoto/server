<?php

use Core\Exception\ServerError;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * Class Model
 *
 * @property string _tableName
 *
 * @method update()
 * @method static self findFirst(int $id)
 * @method static Simple find(mixed $params = null)
 * @method int count()
 */
abstract class Model extends Phalcon\Mvc\Model
{

	/**
	 * @var int
	 * @Identity
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $id;

	/**
	 * @return void
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

	/**
	 * @param mixed $data
	 * @param null $whiteList
	 * @return bool
	 * @throws ServerError
	 */
	public function save($data = null, $whiteList = null)
	{
		if (method_exists($this,'beforeSave')) $this->beforeSave();

		if (parent::save($data, $whiteList)) {
			return true;
		}

		$messages = [];

		foreach ($this->getMessages() as $message) {
			$messages[] = $message->getMessage();
		}

		throw new ServerError($messages);
	}

}