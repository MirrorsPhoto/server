<?php

use Phinx\Seed\AbstractSeed;

class Roles extends AbstractSeed
{
	public function run()
	{
		$this
			->table('role')
			->insert([
				['name' => 'Администратор'],
				['name' => 'Оператор'],
				['name' => 'Пользователь']
			])
			->save()
		;
	}
}
