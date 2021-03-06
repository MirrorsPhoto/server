<?php
use Phinx\Migration\AbstractMigration;

class BarCodeAllowNull extends AbstractMigration
{

	public function change(): void
	{
		$table = $this->table('good');

		$table->changeColumn('bar_code', 'text', ['comment' => 'Штрих-код товара', 'null' => true]);
	}

}
