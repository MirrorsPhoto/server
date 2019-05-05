<?php

use Phinx\Seed\AbstractSeed;

class Departments extends AbstractSeed
{
	public function getDependencies()
	{
		return [
			'Cities',
			'Users'
		];
	}

	public function run()
	{
		$this
			->table('department')
			->insert([
				[
					'city_id' => 1,
					'name' => 'Амвросиевка',
					'address' => 'Фрунзе 16'
				]
			])
			->save()
		;

		$this
			->table('department_personnel_history')
			->insert([
				[
					'department_id' => 1,
					'user_id' => 1
				],
				[
					'department_id' => 1,
					'user_id' => 2
				],
				[
					'department_id' => 1,
					'user_id' => 3
				]
			])
			->save()
		;
	}
}
