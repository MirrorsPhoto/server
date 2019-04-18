<?php
use Phinx\Migration\AbstractMigration;

class BarCodeAllowNull extends AbstractMigration
{
	public function change() {
		$table = $this->table('good');

		$table->changeColumn('bar_code', 'text', ['comment' => 'Штрих-код товара', 'null' => true]);

		$table->insert([
			[
				'name'          => 'Тестовый товар без штриф-кода',
				'description'   => 'Тестовое описание'
			]
		])->save();

		$this->table('good_price_history')->insert([
			[
				'good_id'       => 2,
				'department_id' => 1,
				'user_id'       => 1,
				'price'         => 400
			]
		])->save();
	}
}
