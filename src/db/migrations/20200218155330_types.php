<?php


use Phinx\Migration\AbstractMigration;

class Types extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('type');

		$table
			->addColumn('name', 'text', ['comment' => 'Название вида предоставляемой услуги (фото, копии)']);

		$table
			->addIndex([
				'name',
			])
		;

		$table->create();

		$table = $this->table('user_type');

		$table
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя'])
			->addColumn('type_id', 'integer', ['comment' => 'Id услуги']);

		$table
			->addIndex([
				'user_id',
				'type_id',
			])
		;

		$table
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'CASCADE'])
			->addForeignKey('type_id', 'type', 'id', ['delete' => 'CASCADE']);

		$table->create();
	}
}
