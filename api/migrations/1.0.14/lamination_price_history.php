<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class LaminationPriceHistoryMigration_114 extends Migration
{
	private $_tableName = 'lamination_price_history';

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
						'lamination_size_id',
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
							'type' => Column::TYPE_INTEGER,
							'notNull' => true,
						]
					),
					new Column(
						'datetime_from',
						[
							'type' => Column::TYPE_TIMESTAMP,
							'default' => "CURRENT_TIMESTAMP",
							'notNull' => true,
						]
					),
					new Column(
						'datetime_to',
						[
							'type' => Column::TYPE_TIMESTAMP,
						]
					),
				],
				'indexes' => [
					new Index('lamination_price_history_pkey', ['id'], 'PRIMARY KEY'),
					new Index('lamination_price_history_photo_size_id', ['lamination_size_id']),
					new Index('lamination_price_history_user_id', ['user_id']),
					new Index('lamination_price_history_datetime_to', ['datetime_to'])
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'lamination_price_history_lamination_size_id',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'lamination_size',
							'columns'           => ['lamination_size_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'lamination_price_history_user',
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
				'lamination_size_id',
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
