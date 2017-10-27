<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class PhotoSizeMigration_170 extends Migration
{
	private $_tableName = 'photo_size';

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
						'width',
						[
							'type' => Column::TYPE_FLOAT,
							'notNull' => true,
						]
					),
					new Column(
						'height',
						[
							'type' => Column::TYPE_FLOAT,
							'notNull' => true,
						]
					),
				],
				'indexes' => [
					new Index('photo_size_pkey', ['id'], 'PRIMARY KEY'),
					new Index('photo_size_width', ['width']),
					new Index('photo_size_height', ['height'])
				],
			]
		);

		$this->batchInsert($this->_tableName, [
				'width',
				'height'
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
