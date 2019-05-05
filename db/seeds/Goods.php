<?php

use Phinx\Seed\AbstractSeed;

class Goods extends AbstractSeed
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
			->table('good')
			->insert([
				[
					'name' => 'Тестовый товар',
					'description' => 'Тестовое описание',
					'bar_code' => '1234567890123'
				],
				[
					'name' => 'Тестовый товар без штриф-кода',
					'description'   => 'Тестовое описание'
				]
			])
			->save()
		;

		$this
			->table('good_price_history')
			->insert([
				[
					'good_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 500
				],
				[
					'good_id'   => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 400
				]
			])
			->save()
		;

		$dataToInsert = [];

		for ($i = 0; $i < 100; $i++) {
			$dataToInsert[] = [
				'good_id' => 1,
				'department_id' => 1,
				'user_id' => 1,
				'price' => 420
			];
		}

		$this
			->table('good_receipt')
			->insert($dataToInsert)
			->save()
		;
	}
}
