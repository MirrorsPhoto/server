<?php

class ConfigIni extends Phalcon\Config\Adapter\Ini
{
	use Core\Singleton;

	public function __construct(string $filePath = 'api/config/config.ini', ?string $mode = null)
	{
		parent::__construct($filePath, $mode);
	}
}
