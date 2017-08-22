<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class GoodMigration_102 extends Migration
{
	private $_tableName = 'good';

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
					),
					new Column(
						'description',
						[
							'type' => Column::TYPE_TEXT,
						]
					),
					new Column(
						'bar_code',
						[
							'type' => Column::TYPE_TEXT,
							'notNull' => true,
							'default' => 13
						]
					),
				],
				'indexes' => [
					new Index('good_bar_code', ['bar_code'], 'unique'),
					new Index('good_pkey', ['id'], 'PRIMARY KEY'),
					new Index('good_name', ['name'], 'unique')
				],
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
