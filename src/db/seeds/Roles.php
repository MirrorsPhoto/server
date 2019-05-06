<?php

use Phinx\Seed\AbstractSeed;

class Roles extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function run(): void
	{
		$this
			->table('role')
			->insert([
				['name' => 'Администратор'],
				['name' => 'Оператор'],
				['name' => 'Пользователь'],
			])
			->save()
		;
	}

}
