<?php

use Phinx\Seed\AbstractSeed;

class Services extends AbstractSeed
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
			->table('service')
			->insert([
				['name' => 'Монтаж'],
				['name' => 'Сканирование'],
				['name' => 'Запись на USB накопитель'],
				['name' => 'Запись на диск'],
				['name' => 'Интернет'],
				['name' => 'Реставрация']
			])
			->save()
		;

		$this
			->table('service_price_history')
			->insert([
				[
					'service_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 40
				],
				[
					'service_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30
				],
				[
					'service_id' => 3,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30
				],
				[
					'service_id' => 4,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 50
				],
				[
					'service_id' => 5,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30
				],
				[
					'service_id' => 6,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100
				]
			])
			->save()
		;
	}
}
