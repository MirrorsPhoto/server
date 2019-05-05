<?php

class ConfigIni extends Phalcon\Config\Adapter\Ini
{

	use Core\Singleton;

	public function __construct($filePath = 'api/config/config.ini', $mode = null)
	{
		parent::__construct($filePath, $mode);
	}
}
