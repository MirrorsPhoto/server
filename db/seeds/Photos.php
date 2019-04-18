<?php

use Phinx\Seed\AbstractSeed;

class Photos extends AbstractSeed
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
			->table('photo_size')
			->insert([
				[
					'width' => 2.5,
					'height' => 3
				],
				[
					'width' => 3,
					'height' => 4
				],
				[
					'width' => 3.6,
					'height' => 4.6
				],
				[
					'width' => 4,
					'height' => 6
				],
				[
					'width' => 5,
					'height' => 5
				],
				[
					'width' => 9,
					'height' => 12
				],
				[
					'width' => 10,
					'height' => 15
				]
			])
			->save()
		;

		$this
			->table('photo')
			->insert([
				[
					'photo_size_id' => 1,
					'count' => 4
				],
				[
					'photo_size_id' => 2,
					'count' => 4
				],
				[
					'photo_size_id' => 2,
					'count' => 6
				],
				[
					'photo_size_id' => 3,
					'count' => 2
				],
				[
					'photo_size_id' => 3,
					'count' => 4
				],
				[
					'photo_size_id' => 4,
					'count' => 2
				],
				[
					'photo_size_id' => 5,
					'count' => 2
				],
				[
					'photo_size_id' => 6,
					'count' => 1
				],
				[
					'photo_size_id' => 7,
					'count' => 1
				]
			])
			->save()
		;

		$this
			->table('photo_price_history')
			->insert([
				[
					'photo_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100
				],
				[
					'photo_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100
				],
				[
					'photo_id' => 3,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 140
				],
				[
					'photo_id' => 4,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 60
				],
				[
					'photo_id' => 5,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120
				],
				[
					'photo_id' => 6,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100
				],
				[
					'photo_id' => 7,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120
				],
				[
					'photo_id' => 8,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120
				],
				[
					'photo_id' => 9,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120
				]
			])
			->save()
		;
	}
}
