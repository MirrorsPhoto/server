<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UserMigration_109
 */
class UserMigration_109 extends Migration
{
	private $_tableName = 'user';

	public function up()
	{
		self::$_connection->addColumn(
			$this->_tableName,
			'public',
			new Column(
				'avatar_id',
				[
					'type' => Column::TYPE_INTEGER,
					'after' => 'middle_name',
				]
		));

		self::$_connection->addIndex(
			$this->_tableName,
			'public',
			new Index('user_avatar_id', ['avatar_id'])
		);

		self::$_connection->addForeignKey(
			$this->_tableName,
			'public',
			new \Phalcon\Db\Reference(

				'user_avatar',
				[
					'referencedSchema'  => 'public',
					'referencedTable'   => 'file',
					'columns'           => ['avatar_id'],
					'referencedColumns' => ['id'],
				]
			)
		);

		self::$_connection->query('UPDATE "user" SET avatar_id = 1 WHERE id = 3 ');
	}

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        self::$_connection->dropColumn($this->_tableName, 'public', 'avatar_id');

        self::$_connection->dropIndex($this->_tableName, 'public', 'user_avatar_id');

	    self::$_connection->dropForeignKey($this->_tableName, 'public', 'user_avatar');
    }

}
