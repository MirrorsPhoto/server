<?php

use Phinx\Migration\AbstractMigration;

class GoodSoftDelete extends AbstractMigration
{

	public function change(): void
	{
		$this
			->table('good')
			->addColumn('is_delete', 'boolean', ['comment' => 'TRUE товар удалён', 'default' => false])
			->save();
	}

}
