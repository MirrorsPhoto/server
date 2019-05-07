<?php

use Phinx\Seed\AbstractSeed;

class Laminations extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function getDependencies(): array
	{
		return [
			'Departments',
			'Users',
		];
	}

	public function run(): void
	{
		$this
			->table('lamination')
			->insert([
				[
					'id' => 1,
					'format' => 'A4',
				],
				[
					'id' => 2,
					'format' => 'A5',
				],
			])
			->save()
		;

		$this
			->table('lamination_price_history')
			->insert([
				[
					'lamination_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30,
				],
				[
					'lamination_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 20,
				],
			])
			->save()
		;
	}

}
