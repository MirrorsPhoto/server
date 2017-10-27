<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class ReceiptMigration_150 extends Migration
{
	private $_tableName = 'receipt';

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
						'price',
						[
							'type' => Column::TYPE_FLOAT,
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
					new Index('receipt_pkey', ['id'], 'PRIMARY KEY'),
					new Index('receipt_department_id', ['department_id']),
					new Index('receipt_user_id', ['user_id']),
					new Index('receipt_datetime', ['datetime'])
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'good_receipt',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'good',
							'columns'           => ['good_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'receipt_department',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'department',
							'columns'           => ['department_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'receipt_user',
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

		$this->batchInsert($this->_tableName, [
				'good_id',
				'department_id',
				'user_id',
				'price'
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