<?php

use Phinx\Seed\AbstractSeed;

class Photos extends AbstractSeed
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
			->table('photo_size')
			->insert([
				[
					'id' => 1,
					'width' => 2.5,
					'height' => 3,
				],
				[
					'id' => 2,
					'width' => 3,
					'height' => 4,
				],
				[
					'id' => 3,
					'width' => 3.6,
					'height' => 4.6,
				],
				[
					'id' => 4,
					'width' => 4,
					'height' => 6,
				],
				[
					'id' => 5,
					'width' => 5,
					'height' => 5,
				],
				[
					'id' => 6,
					'width' => 9,
					'height' => 12,
				],
				[
					'id' => 7,
					'width' => 10,
					'height' => 15,
				],
				[
					'id' => 8,
					'width' => 3.5,
					'height' => 3.5,
				],
			])
			->save()
		;

		$this
			->table('photo')
			->insert([
				[
					'id' => 1,
					'photo_size_id' => 1,
					'count' => 4,
				],
				[
					'id' => 2,
					'photo_size_id' => 2,
					'count' => 4,
				],
				[
					'id' => 3,
					'photo_size_id' => 2,
					'count' => 6,
				],
				[
					'id' => 4,
					'photo_size_id' => 3,
					'count' => 2,
				],
				[
					'id' => 5,
					'photo_size_id' => 3,
					'count' => 4,
				],
				[
					'id' => 6,
					'photo_size_id' => 4,
					'count' => 2,
				],
				[
					'id' => 7,
					'photo_size_id' => 5,
					'count' => 2,
				],
				[
					'id' => 8,
					'photo_size_id' => 6,
					'count' => 1,
				],
				[
					'id' => 9,
					'photo_size_id' => 7,
					'count' => 1,
				],
				[
					'id' => 10,
					'photo_size_id' => 8,
					'count' => 2,
				],
				[
					'id' => 11,
					'photo_size_id' => 8,
					'count' => 4,
				],
				[
					'id' => 12,
					'photo_size_id' => 8,
					'count' => 8,
				],
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
					'price' => 100,
				],
				[
					'photo_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100,
				],
				[
					'photo_id' => 3,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 140,
				],
				[
					'photo_id' => 4,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 60,
				],
				[
					'photo_id' => 5,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120,
				],
				[
					'photo_id' => 6,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 100,
				],
				[
					'photo_id' => 7,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120,
				],
				[
					'photo_id' => 8,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120,
				],
				[
					'photo_id' => 9,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120,
				],
				[
					'photo_id' => 10,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 60,
				],
				[
					'photo_id' => 11,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 120,
				],
				[
					'photo_id' => 12,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 240,
				],
			])
			->save()
		;
	}

}
