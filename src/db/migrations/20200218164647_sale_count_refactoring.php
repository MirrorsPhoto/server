<?php


use Phinx\Migration\AbstractMigration;

class SaleCountRefactoring extends AbstractMigration
{
	public function change()
	{
		$data = $this->collectData();

		$this->dropOldTable();
		$this->createNewTable();
		$this->insertData($data);
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	private function collectData()
	{
		$types = $this->fetchAll('SELECT name FROM type');
		$result = [];

		foreach ($types as $type) {
			$typeName = $type['name'];
			$sales = $this->fetchAll('SELECT * FROM ' . $typeName . '_sale');

			foreach ($sales as $sale) {
				$datetime = (new DateTime($sale['datetime']))->format('Y-m-d H:i:s');

				if (isset($result[$typeName][$datetime][$sale[$typeName . '_id']])) {
					$result[$typeName][$datetime][$sale[$typeName . '_id']]['count'] += 1;
				} else {
					$result[$typeName][$datetime][$sale[$typeName . '_id']] = [
						'department_id' => $sale['department_id'],
						'user_id' => $sale['user_id'],
						'count' => 1,
					];
				}
			}
		}

		return $result;
	}

	private function dropOldTable()
	{
		$types = $this->fetchAll('SELECT name FROM type');

		foreach ($types as $type) {
			$this->dropTable($type['name'] . '_sale');
		}
	}

	private function createNewTable()
	{
		$photoTable = $this->table('photo_sale');

		$photoTable
			->addColumn('photo_id', 'integer', ['comment' => 'Id размера фотографии'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором делали эту фотографию'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который делал фотографию'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', [
				'comment' => 'Дата продажи',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$photoTable
			->addIndex([
				'photo_id',
				'department_id',
				'user_id',
				'count',
				'datetime'
			])
		;

		$photoTable
			->addForeignKey('photo_id', 'photo', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$photoTable->create();

		$copyTable = $this->table('copy_sale');

		$copyTable
			->addColumn('copy_id', 'integer', ['comment' => 'Id формата ксерокопии', 'default' => 1])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором сделали ксерокопию'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который сделал ксерокопию'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', [
				'comment' => 'Дата продажи',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$copyTable
			->addIndex([
				'copy_id',
				'department_id',
				'user_id',
				'count',
				'datetime'
			])
		;

		$copyTable
			->addForeignKey('copy_id', 'copy', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$copyTable->create();

		$goodTable = $this->table('good_sale');

		$goodTable
			->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором продали данный товар'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который продавал товар'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', [
				'comment' => 'Дата продажи',
				'default' => 'CURRENT_TIMESTAMP',
			])
		;

		$goodTable
			->addIndex([
				'good_id',
				'department_id',
				'user_id',
				'count',
				'datetime',
			])
		;

		$goodTable
			->addForeignKey('good_id', 'good', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$goodTable->create();

		$laminationTable = $this->table('lamination_sale');

		$laminationTable
			->addColumn('lamination_id', 'integer', ['comment' => 'Id формата ламинации'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором делали эту ламинацию'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который делал ламинацию'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', [
				'comment' => 'Дата продажи',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$laminationTable
			->addIndex([
				'lamination_id',
				'department_id',
				'user_id',
				'count',
				'datetime'
			])
		;

		$laminationTable
			->addForeignKey('lamination_id', 'lamination', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$laminationTable->create();

		$this
			->table('service_sale')
			->addColumn('service_id', 'integer', ['comment' => 'Id услуги'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором продавали эту услугу'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который продовал эту услугу'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
			->addIndex(['service_id', 'department_id', 'user_id', 'datetime', 'count'])
			->addForeignKey('service_id', 'service', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
			->create()
		;

		$this
			->table('printing_sale')
			->addColumn('printing_id', 'integer', ['comment' => 'Id распечатки'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором делали эту распечатку'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который делал эту распечатку'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
			->addIndex(['printing_id', 'department_id', 'user_id', 'datetime', 'count'])
			->addForeignKey('printing_id', 'printing', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
			->create()
		;
	}

	private function insertData(array $result)
	{
		foreach ($result as $type => $items) {
			$toInsert = [];
			foreach ($items as $datetime => $data) {
				foreach ($data as $id => $array) {
					$toInsert[] = [
						'department_id' => $array['department_id'],
						'user_id' => $array['user_id'],
						$type. '_id' => $id,
						'count' => $array['count'],
						'datetime' => $datetime,
					];
				}
			}

			$this->table($type . '_sale')->insert($toInsert)->save();
		}
	}
}
