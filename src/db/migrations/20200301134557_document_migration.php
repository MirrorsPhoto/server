<?php


use Phinx\Migration\AbstractMigration;

class DocumentMigration extends AbstractMigration
{

	public function change(): void
	{
		$this
			->table('document')
			->addColumn('name', 'text', ['comment' => 'Название документа'])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата создания услуги',
				'default' => 'CURRENT_TIMESTAMP',
			])
			->addIndex('datetime_create')
			->addIndex('name', ['unique' => true])
			->create()
		;

		$this
			->table('document_price_history')
			->addColumn('document_id', 'integer', ['comment' => 'Id документа'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
			->addColumn('price', 'float', ['comment' => 'Цена услуги'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'С какой даты действительна цена',
				'default' => 'CURRENT_TIMESTAMP',
			])
			->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
			->addIndex(['document_id', 'department_id', 'user_id', 'price', 'datetime_to'])
			->addForeignKey('document_id', 'document', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
			->create()
		;

		$this
			->table('document_sale')
			->addColumn('document_id', 'integer', ['comment' => 'Id документа'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором продавали эту услугу'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который продовал эту услугу'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
			->addIndex(['document_id', 'department_id', 'user_id', 'datetime'])
			->addForeignKey('document_id', 'document', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
			->create()
		;
	}

}
