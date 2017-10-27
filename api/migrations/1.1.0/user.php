<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

class UserMigration_110 extends Migration
{
	private $_tableName = 'user';

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
			new Index('user_token_key', ['token'], 'unique')
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
