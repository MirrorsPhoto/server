<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class RoleMigration_210 extends Migration
{
	private $_tableName = 'role';

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
						'name',
						[
							'type' => Column::TYPE_TEXT,
							'notNull' => true,
						]
					)
				],
				'indexes' => [
					new Index('role_name', ['name'], 'unique'),
					new Index('role_pkey', ['id'], 'PRIMARY KEY'),
				],
			]
		);


		$this->batchInsert($this->_tableName, [
				'name'
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
