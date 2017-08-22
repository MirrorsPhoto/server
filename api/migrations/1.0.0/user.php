<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UserMigration_100
 */
class UserMigration_100 extends Migration
{
	private $_tableName = 'user';

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
	                        'notNull' => true,
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
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => true,
                        ]
                    )
                ],
                'indexes' => [
                    new Index('user_email_key', ['email'], 'unique'),
                    new Index('user_pkey', ['id'], 'PRIMARY KEY'),
                    new Index('user_username_key', ['username'], 'unique')
                ],
            ]
        );


	    $this->batchInsert('user', [
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
