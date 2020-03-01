<?php

use Phinx\Seed\AbstractSeed;

class Types extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('type')
			->insert([
				[
					'id' => 1,
					'name' => 'photo',
				],
				[
					'id' => 2,
					'name' => 'good',
				],
				[
					'id' => 3,
					'name' => 'copy',
				],
				[
					'id' => 4,
					'name' => 'lamination',
				],
				[
					'id' => 5,
					'name' => 'printing',
				],
				[
					'id' => 6,
					'name' => 'service',
				],
				[
					'id' => 7,
					'name' => 'document',
				],
			])
			->save()
		;
	}

}
