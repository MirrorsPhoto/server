<?php

use Phinx\Seed\AbstractSeed;

class Roles extends AbstractSeed
{
	public function run()
	{
		$this
			->table('role')
			->insert([
				['name' => 'Гость'],
				['name' => 'Администратор'],
				['name' => 'Оператор'],
				['name' => 'Пользователь']
			])
			->save()
		;
	}
}
