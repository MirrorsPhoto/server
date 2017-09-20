<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UserMigration_111
 */
class UserMigration_111 extends Migration
{
	private $_tableName = 'user';

	public function up()
	{
		self::$_connection->modifyColumn(
			$this->_tableName,
			'public',
			new Column(
				'role_id',
				[
					'type' => Column::TYPE_INTEGER,
					'notNull' => true,
					'default' => 3
				]
			),
			new Column(
				'role',
				[
					'type' => Column::TYPE_INTEGER,
					'notNull' => true,
					'default' => 2
				]
			)
		);

		self::$_connection->addIndex(
			$this->_tableName,
			'public',
			new Index('user_role_id', ['role_id'])
		);

		self::$_connection->addForeignKey(
			$this->_tableName,
			'public',
			new \Phalcon\Db\Reference(

				'user_role',
				[
					'referencedSchema'  => 'public',
					'referencedTable'   => 'role',
					'columns'           => ['role_id'],
					'referencedColumns' => ['id'],
				]
			)
		);
	}

	/**
	 * Reverse the migrations
	 *
	 * @return void
	 */
	public function down()
	{
		self::$_connection->modifyColumn(
			$this->_tableName,
			'public',
			new Column(
				'role',
				[
					'type' => Column::TYPE_INTEGER,
					'notNull' => true,
					'default' => 2
				]
			),
			new Column(
				'role_id',
				[
					'type' => Column::TYPE_INTEGER,
					'notNull' => true,
					'default' => 3
				]
			)
		);

		self::$_connection->dropForeignKey($this->_tableName, 'public', 'user_role');

		self::$_connection->dropIndex($this->_tableName, 'public', 'user_role_id');
	}

}
