<?php

use Phinx\Seed\AbstractSeed;

class Printings extends AbstractSeed
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
			->table('printing')
			->insert([
				[
					'id' => 1,
					'name' => 'A4',
				],
				[
					'id' => 2,
					'name' => 'A4',
					'color' => 'true',
				],
			])
			->update()
		;

		$this
			->table('printing_price_history')
			->insert([
				[
					'printing_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 4,
				],
				[
					'printing_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 20,
				],
			])
			->update()
		;
	}

}
