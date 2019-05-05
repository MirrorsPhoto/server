<?php


use Phinx\Migration\AbstractMigration;

class RefactoringLamination extends AbstractMigration
{
	public function change()
	{
		$lamination = $this->table('lamination_size');

		$lamination->rename('lamination');

		$laminationPriceHistory = $this->table('lamination_price_history');

		$laminationPriceHistory
			->removeIndex(['lamination_size_id'])
			->dropForeignKey('lamination_size_id')
			->renameColumn('lamination_size_id', 'lamination_id')
			->addIndex('lamination_id')
			->addForeignKey('lamination_id', 'lamination', 'id', ['delete' => 'RESTRICT'])
			->update()
		;

		$laminationSale = $this->table('lamination_sale');

		$laminationSale
			->removeIndex(['lamination_size_id'])
			->dropForeignKey('lamination_size_id')
			->renameColumn('lamination_size_id', 'lamination_id')
			->addIndex('lamination_id')
			->addForeignKey('lamination_id', 'lamination', 'id', ['delete' => 'RESTRICT'])
			->update()
		;
	}
}
