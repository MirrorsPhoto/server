<?php

use Phinx\Seed\AbstractSeed;

class Cities extends AbstractSeed
{
	public function run()
	{
		$this
			->table('city')
			->insert([['name' => 'Амвросиевка']])
			->save()
		;

		$a = 1;
	}
}
