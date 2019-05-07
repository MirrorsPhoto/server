<?php

use Phinx\Seed\AbstractSeed;

class Cities extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('city')
			->insert([[
				'id' => 1,
				'name' => 'Амвросиевка',
			]])
			->save()
		;
	}

}
