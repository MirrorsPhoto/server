<?php

use Phinx\Seed\AbstractSeed;

class Cities extends AbstractSeed
{
	public function run(): void
	{
		$this
			->table('city')
			->insert([['name' => 'Амвросиевка']])
			->save()
		;
	}
}
