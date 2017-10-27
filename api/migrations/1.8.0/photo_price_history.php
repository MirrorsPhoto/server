<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class PhotoPriceHistoryMigration_180 extends Migration
{
	private $_tableName = 'photo_price_history';

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
						'photo_size_id',
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
						'count',
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
					new Index('photo_price_history_pkey', ['id'], 'PRIMARY KEY'),
					new Index('photo_price_history_photo_size_id', ['photo_size_id']),
					new Index('photo_price_history_department_id', ['department_id']),
					new Index('photo_price_history_user_id', ['user_id']),
					new Index('photo_price_history_count', ['count']),
					new Index('photo_price_history_datetime_to', ['datetime_to'])
				],
				'references' => [
					new \Phalcon\Db\Reference(

						'photo_price_history_photo_size_id',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'photo_size',
							'columns'           => ['photo_size_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'good_price_department',
						[
							'referencedSchema'  => 'public',
							'referencedTable'   => 'department',
							'columns'           => ['department_id'],
							'referencedColumns' => ['id'],
						]
					),
					new \Phalcon\Db\Reference(

						'photo_price_history_user',
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
				'photo_size_id',
				'department_id',
				'user_id',
				'count',
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
