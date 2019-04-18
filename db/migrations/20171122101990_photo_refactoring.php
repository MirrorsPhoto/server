<?php
use Phinx\Migration\AbstractMigration;

class PhotoRefactoring extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('photo');

		$table
			->addColumn('photo_size_id', 'integer', ['comment' => 'Размер'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата добавления этого варианта размер-кол-во',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex(['photo_size_id', 'count', 'datetime_create'])
		;

		$table
			->addForeignKey('photo_size_id', 'photo_size', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();

		$rows = $this->fetchAll('SELECT * FROM photo_price_history');

		foreach ($rows as $row) {
			$table->insert([
				'photo_size_id' => $row['photo_size_id'],
				'count' => $row['count']
			]);
		}

		$table->saveData();

		$table = $this->table('photo_price_history');
		$table->dropForeignKey('photo_size_id')->save();

		foreach ($this->fetchAll('SELECT * FROM photo_price_history') as $row) {
			$rowPhoto = $this->fetchRow("
				SELECT * 
				FROM photo 
				WHERE 
					photo_size_id = {$row['photo_size_id']} 
					AND count = {$row['count']}");

			$this->execute("UPDATE photo_price_history SET photo_size_id = {$rowPhoto['id']} WHERE id = {$row['id']}");
		}

		$table
			->changeColumn('photo_size_id', 'integer', ['comment' => 'Id фотографии'])
			->renameColumn('photo_size_id', 'photo_id')
			->removeColumn('count')
			->addForeignKey('photo_id', 'photo', 'id', ['delete' => 'RESTRICT'])
			->update()
		;

		$table = $this->table('photo_sale');
		$table
			->dropForeignKey('photo_size_id')
			->changeColumn('photo_size_id', 'integer', ['comment' => 'Id фотографии'])
			->renameColumn('photo_size_id', 'photo_id')
			->removeColumn('count')
			->addForeignKey('photo_id', 'photo', 'id', ['delete' => 'RESTRICT'])
			->update()
		;
	}
}
