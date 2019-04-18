<?php


use Phinx\Migration\AbstractMigration;

class Service extends AbstractMigration
{
	public function change()
	{
		$service = $this->table('service');

		$service
			->addColumn("name", 'text', ['comment' => 'Название услуги'])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата создания услуги',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addIndex('datetime_create')
			->addIndex('name', ['unique' => true])
			->create()
		;

		$servicePriceHistory = $this->table('service_price_history');

		$servicePriceHistory
			->addColumn('service_id', 'integer', ['comment' => 'Id услуги'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
			->addColumn('price', 'float', ['comment' => 'Цена услуги'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'С какой даты действительна цена',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
			->addIndex(['service_id', 'department_id', 'user_id', 'price', 'datetime_to'])
			->addForeignKey('service_id', 'service', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
			->create()
		;

		$this
			->table('service_sale')
			->addColumn('service_id', 'integer', ['comment' => 'Id услуги'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором продавали эту услугу'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который продовал эту услугу'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
			->addIndex(['service_id', 'department_id', 'user_id', 'datetime'])
			->addForeignKey('service_id', 'service', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
			->create()
		;
	}
}
