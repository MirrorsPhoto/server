<?php

use Phinx\Seed\AbstractSeed;

class Copies extends AbstractSeed
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
			->table('copy')
			->insert([
				'id' => 1,
				'format' => 'A4'
			])
			->save()
		;

		$this
			->table('copy_price_history')
			->insert([
				[
					'copy_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 3,
				],
			])
			->save()
		;
	}

}
