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
	protected $objReflection;

	/**
	 * @var array
	 */
	protected $arrValues;

	/**
	 * @var array
	 */
	protected $rulesMap = [];

	/**
	 * Constructor
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->objReflection = new ReflectionClass($this);
		$this->arrValues = array_flip($this->objReflection->getConstants());

		if (!is_array($this->arrValues)) {
			throw new Exception('No values in ' . $this->objReflection->getName());
		}
	}

	/**
	 * Validate if value exists in list.
	 * @param mixed $mixValue
	 * @param bool $bThrowException
	 * @return boolean
	 * @throws Exception
	 */
	public function validate($mixValue, $bThrowException = true)
	{
		$bExists = isset($this->arrValues[$mixValue]);

		if (!$bExists && $bThrowException) {
			throw new Exception($mixValue . ' not found in ' . $this->objReflection->getName());
		}

		return $bExists;
	}

	/**
	 * Get all enum items
	 * Format [name => value]
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->objReflection->getConstants();
	}

	/**
	 * Get constant name by value
	 * @param mixed $mixValue
	 * @throws Exception
	 * @return string
	 */
	public function getName($mixValue)
	{
		$this->validate($mixValue);

		return $this->arrValues[$mixValue];
	}

	/**
	 * @param string $strName
	 * @throws Exception
	 * @return mixed
	 */
	public function getValue($strName)
	{
		$value = $this->objReflection->getConstant($strName);
		if ($value === false) {
			throw new Exception($strName . ' name not found in ' . $this->objReflection->getName());
		}

		return $value;
	}
}
