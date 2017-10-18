<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class CopyPriceHistoryMigration_112 extends Migration
{
	private $_tableName = 'copy_price_history';

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
					new Index('copy_price_history_pkey', ['id'], 'PRIMARY KEY'),
					new Index('copy_price_history_user_id', ['user_id']),
					new Index('copy_price_history_datetime_to', ['datetime_to'])
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'copy_price_history_user',
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
