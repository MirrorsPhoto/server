<?php

namespace Core\Enum;

use Core\Singleton;
use ReflectionClass;
use ReflectionException;

abstract class Abstractes
{
	use Singleton;

	/**
	 * @var ReflectionClass
	 */
	protected $_objReflection;

	/**
	 * @var array
	 */
	protected $_arrValues;

	/**
	 * @var array
	 */
	protected $_rulesMap = [];

	/**
	 * Constructor
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function __construct() {
		$this->_objReflection = new ReflectionClass($this);
		$this->_arrValues = array_flip($this->_objReflection->getConstants());

		if (!is_array($this->_arrValues)) {
			throw new Exception('No values in ' . $this->_objReflection->getName());
		}
	}

	/**
	 * Validate if value exists in list.
	 * @param mixed $mixValue
	 * @param bool $bThrowException
	 * @return boolean
	 * @throws Exception
	 */
	public function validate($mixValue, $bThrowException = true) {
		$bExists = isset($this->_arrValues[$mixValue]);

		if (!$bExists && $bThrowException) {
			throw new Exception($mixValue . ' not found in ' . $this->_objReflection->getName());
		}

		return $bExists;
	}

	/**
	 * Get all enum items
	 * Format [name => value]
	 *
	 * @return array
	 */
	public function getAll() {
		return $this->_objReflection->getConstants();
	}

	/**
	 * Get constant name by value
	 * @param mixed $mixValue
	 * @throws Exception
	 * @return string
	 */
	public function getName($mixValue) {
		$this->validate($mixValue);

		return $this->_arrValues[$mixValue];
	}

	/**
	 * @param string $strName
	 * @throws Exception
	 * @return mixed
	 */
	public function getValue($strName) {
		$value = $this->_objReflection->getConstant($strName);
		if ($value === false) {
			throw new Exception($strName . ' name not found in ' . $this->_objReflection->getName());
		}

		return $value;
	}
}