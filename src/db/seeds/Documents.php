<?php

use Phinx\Seed\AbstractSeed;

class Documents extends AbstractSeed
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
			->table('document')
			->insert([
				[
					'id' => 1,
					'name' => 'Гражданство РФ',
				],
				[
					'id' => 2,
					'name' => 'Загран паспорт РФ',
				],
			])
			->save()
		;

		$this
			->table('document_price_history')
			->insert([
				[
					'document_id' => 1,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 40,
				],
				[
					'document_id' => 2,
					'department_id' => 1,
					'user_id' => 1,
					'price' => 30,
				],
			])
			->save()
		;
	}

}
