<?php

use Phinx\Seed\AbstractSeed;

class Copies extends AbstractSeed
{
	public function getDependencies()
	{
		return [
			'Departments',
			'Users'
		];
	}

	public function run()
	{
		$this
			->table('copy')
			->insert(['format' => 'A4'])
			->save()
		;

		$this
			->table('copy_price_history')
			->insert([
				[
					'copy_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 3
				]
			])
			->save()
		;
	}
}
