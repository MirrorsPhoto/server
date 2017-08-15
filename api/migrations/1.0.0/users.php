<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UsersMigration_100
 */
class UsersMigration_100 extends Migration
{
	private $_tableName = 'users';

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
                        'username',
                        [
                            'type' => Column::TYPE_TEXT,
                        ]
                    ),
                    new Column(
                        'first_name',
                        [
                            'type' => Column::TYPE_TEXT,
                        ]
                    ),
                    new Column(
                        'last_name',
                        [
                            'type' => Column::TYPE_TEXT,
                        ]
                    ),
                    new Column(
                        'middle_name',
                        [
                            'type' => Column::TYPE_TEXT,
                        ]
                    ),
	                new Column(
		                'role',
		                [
			                'type' => Column::TYPE_INTEGER,
			                'notNull' => true,
			                'default' => 2
		                ]
	                ),

                    new Column(
                        'password',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                        ]
                    ),
                    new Column(
                        'datetime_create',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "now()",
                            'notNull' => true,
                        ]
                    )
                ],
                'indexes' => [
                    new Index('users_email_key', ['email'], null),
                    new Index('users_pkey', ['id'], 'PRIMARY KEY'),
                    new Index('users_username_key', ['username'], null)
                ],
            ]
        );


	    $this->batchInsert('users', [
			    'id',
			    'username',
			    'role',
			    'password',
			    'email',
			    'datetime_create'
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
