<?php

use Phinx\Seed\AbstractSeed;

class Services extends AbstractSeed
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
			->table('service')
			->insert([
				[
					'id' => 1,
					'name' => 'Монтаж',
				],
				[
					'id' => 2,
					'name' => 'Сканирование',
				],
				[
					'id' => 3,
					'name' => 'Запись на USB накопитель',
				],
				[
					'id' => 4,
					'name' => 'Запись на диск',
				],
				[
					'id' => 5,
					'name' => 'Интернет',
				],
				[
					'id' => 6,
					'name' => 'Реставрация',
				],
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
					'price' => 40,
				],
				[
					'service_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30,
				],
				[
					'service_id' => 3,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30,
				],
				[
					'service_id' => 4,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 50,
				],
				[
					'service_id' => 5,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30,
				],
				[
					'service_id' => 6,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100,
				],
			])
			->save()
		;
	}

}
