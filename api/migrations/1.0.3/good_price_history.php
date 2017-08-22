<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class GoodPriceHistoryMigration_103 extends Migration
{
	private $_tableName = 'good_price_history';

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
					new Index('good_price_pkey', ['id'], 'PRIMARY KEY'),
					new Index('good_price_user_id', ['user_id']),
					new Index('good_price_good_id', ['good_id']),
					new Index('good_price_datetime_to', ['datetime_to'])
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'good_price',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'good',
							'columns'           => ['good_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'good_price_user',
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
