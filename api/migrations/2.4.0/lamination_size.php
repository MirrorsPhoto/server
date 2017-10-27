<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class LaminationSizeMigration_240 extends Migration
{
	private $_tableName = 'lamination_size';

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
						'format',
						[
							'type' => Column::TYPE_TEXT,
							'notNull' => true,
						]
					)
				],
				'indexes' => [
					new Index('lamination_size_pkey', ['id'], 'PRIMARY KEY'),
					new Index('lamination_size_format', ['format']),
				],
			]
		);

		$this->batchInsert($this->_tableName, [
				'format',
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
