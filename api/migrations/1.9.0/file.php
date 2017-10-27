<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class FileMigration_190 extends Migration
{
	private $_tableName = 'file';

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
						'path',
						[
							'type' => Column::TYPE_TEXT,
							'notNull' => true,
						]
					),
					new Column(
						'datetime_create',
						[
							'type' => Column::TYPE_TIMESTAMP,
							'default' => "CURRENT_TIMESTAMP",
							'notNull' => true,
						]
					)
				],
				'indexes' => [
					new Index('file_pkey', ['id'], 'PRIMARY KEY'),
					new Index('file_path', ['path'], 'unique')
				],
			]
		);

		$this->batchInsert($this->_tableName, [
				'path'
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
