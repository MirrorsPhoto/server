<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UsersMigration_101
 */
class UsersMigration_101 extends Migration
{
	private $_tableName = 'users';

	public function up()
	{
		self::$_connection->addColumn(
			$this->_tableName,
			'public',
			new Column(
				'token',
				[
					'type' => Column::TYPE_TEXT
				]
		));

		self::$_connection->addIndex(
			$this->_tableName,
			'public',
			new Index('users_token_key', ['token'], null)
		);
	}

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        self::$_connection->dropColumn($this->_tableName, 'public', 'token');
    }

}
