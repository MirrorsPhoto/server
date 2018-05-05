<?php


use Phinx\Migration\AbstractMigration;

class Printing extends AbstractMigration
{
    public function change()
    {
			$printing = $this->table('printing');

			$printing
				->addColumn('name', 'text', ['comment' => 'Название вида распечатки (формат, размер)'])
				->addColumn('color', 'boolean', ['comment' => 'TRUE если цветная', 'default' => false])
				->addColumn('photo', 'boolean', ['comment' => 'TRUE если фотобумага', 'default' => false])
				->addColumn('ext', 'boolean', ['comment' => 'TRUE если фотобумага', 'default' => false])
				->addColumn('datetime_create', 'timestamp', ['comment' => 'Дата создания', 'default' => 'CURRENT_TIMESTAMP'])
				->addIndex(['name', 'color', 'photo', 'ext', 'datetime_create'])
				->create()
			;

			$printing
				->insert([
					[
						'name' => 'A4',
					],
					[
						'name' => 'A4',
						'color' => 'true',
					]
				])
				->update()
			;

			$printingPriceHistory = $this->table('printing_price_history');

			$printingPriceHistory
				->addColumn('printing_id', 'integer', ['comment' => 'Id распечатки'])
				->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
				->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
				->addColumn('price', 'float', ['comment' => 'Цена распечатки'])
				->addColumn('datetime_from', 'timestamp', ['comment' => 'С какой даты действительна цена', 'default' => 'CURRENT_TIMESTAMP'])
				->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
				->addIndex(['printing_id', 'department_id', 'user_id', 'price', 'datetime_to'])
				->addForeignKey('printing_id', 'printing', 'id', ['delete'=> 'RESTRICT'])
				->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
				->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
				->create()
			;

			$printingPriceHistory
				->insert([
					[
						'printing_id' => 1,
						'department_id' => 1,
						'user_id' => 1,
						'price' => 4
					],
					[
						'printing_id' => 2,
						'department_id' => 1,
						'user_id' => 1,
						'price' => 20
					]
				])
				->update()
			;

			$this->table('printing_sale')
				->addColumn('printing_id', 'integer', ['comment' => 'Id распечатки'])
				->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором делали эту распечатку'])
				->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который делал эту распечатку'])
				->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
				->addIndex(['printing_id', 'department_id', 'user_id', 'datetime'])
				->addForeignKey('printing_id', 'printing', 'id', ['delete'=> 'RESTRICT'])
				->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
				->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
				->create()
			;
    }
}
