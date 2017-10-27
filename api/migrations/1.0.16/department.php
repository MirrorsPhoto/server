<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class DepartmentMigration_116
 */
class DepartmentMigration_116 extends Migration
{
	private $_tableName = 'department';

	/**
	 * Define the table structure
	 *
	 * @return void
	 */
	public function morph()
	{
		$this->morphTable($this->_tableName, [
				'columns' => [
					new Column(
						'id',
						[
							'type' => Column::TYPE_INTEGER,
							'notNull' => true,
							'autoIncrement' => true,
							'first' => true
						]
					),
					new Column(
						'city_id',
						[
							'type' => Column::TYPE_INTEGER,
							'notNull' => true,
						]
					),
					new Column(
						'name',
						[
							'type' => Column::TYPE_TEXT,
							'notNull' => true,
						]
					),
					new Column(
						'address',
						[
							'type' => Column::TYPE_TEXT,
							'notNull' => true,
						]
					)
				],
				'indexes' => [
					new Index('department_pkey', ['id'], 'PRIMARY KEY'),
					new Index('department_city_id', ['city_id']),
					new Index('department_name', ['name']),
					new Index('department_address', ['address']),
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'department_city',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'city',
							'columns'           => ['city_id'],
							'referencedColumns' => ['id'],
						]
					),
				]
			]
		);


		$this->batchInsert($this->_tableName, [
				'city_id',
				'name',
				'address'
			]
		);
	}

	/**
	 * Reverse the migrations
	 *
	 * @return void
	 */
	public function down()
	{
		self::$_connection->dropTable($this->_tableName);
	}

}
