<?php

use Phinx\Seed\AbstractSeed;

class Roles extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('role')
			->insert([
				[
					'id' => 1,
					'name' => 'Администратор',
				],
				[
					'id' => 2,
					'name' => 'Оператор',
				],
				[
					'id' => 3,
					'name' => 'Пользователь',
				],
			])
			->save()
		;
	}

}
