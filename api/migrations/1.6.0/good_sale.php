<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class GoodSaleMigration_160 extends Migration
{
	private $_tableName = 'good_sale';

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
						'good_id',
						[
							'type' => Column::TYPE_INTEGER,
							'notNull' => true,
						]
					),
					new Column(
						'department_id',
						[
							'type' => Column::TYPE_INTEGER,
							'notNull' => true,
						]
					),
					new Column(
						'user_id',
						[
							'type' => Column::TYPE_INTEGER,
							'notNull' => true,
						]
					),
					new Column(
						'datetime',
						[
							'type' => Column::TYPE_TIMESTAMP,
							'default' => "CURRENT_TIMESTAMP",
							'notNull' => true,
						]
					),
				],
				'indexes' => [
					new Index('good_sale_pkey', ['id'], 'PRIMARY KEY'),
					new Index('good_sale_department_id', ['department_id']),
					new Index('good_sale_user_id', ['user_id']),
					new Index('good_sale_datetime', ['datetime'])
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'good_sale_good',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'good',
							'columns'           => ['good_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'good_sale_department',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'department',
							'columns'           => ['department_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'good_sale_user',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'user',
							'columns'           => ['user_id'],
							'referencedColumns' => ['id'],
						]
					),
				]
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