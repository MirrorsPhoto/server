<?php


use Phinx\Migration\AbstractMigration;

class RefactoringCopy extends AbstractMigration
{
	public function change(): void
	{
		$copy = $this->table('copy');

		$copy
			->addColumn('format', 'text', ['comment' => 'Название формата (А4)'])
			->addColumn('datetime_create', 'timestamp', ['comment' => 'Дата создания', 'default' => 'CURRENT_TIMESTAMP'])
		;

		$copy
			->addIndex('datetime_create')
			->addIndex('format', ['unique' => true])
		;

		$copy->save();

		$copyPriceHistory = $this->table('copy_price_history');

		$copyPriceHistory
			->addColumn('copy_id', 'integer', ['comment' => 'Id формата ксерокопии', 'after' => 'id', 'default' => 1])
			->addIndex('copy_id')
			->addForeignKey('copy_id', 'copy', 'id', ['delete' => 'RESTRICT'])
			->update()
		;

		$copyPriceHistory->changeColumn('copy_id', 'integer', ['comment' => 'Id формата ксерокопии']);

		$copySale = $this->table('copy_sale');

		$copySale
			->addColumn('copy_id', 'integer', ['comment' => 'Id формата ксерокопии', 'after' => 'id', 'default' => 1])
			->addIndex('copy_id')
			->addForeignKey('copy_id', 'copy', 'id', ['delete' => 'RESTRICT'])
			->update()
		;

		$copySale->changeColumn('copy_id', 'integer', ['comment' => 'Id формата ксерокопии']);
	}
}
