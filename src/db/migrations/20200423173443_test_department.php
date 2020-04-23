<?php

use Phinx\Migration\AbstractMigration;

class TestDepartment extends AbstractMigration
{

	public function change()
	{
		$this
			->table('department')
			->addColumn('is_test', 'boolean', ['comment' => 'TRUE если тестовый', 'default' => false])
			->save();
	}

}
