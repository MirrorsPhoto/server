<?php


use Phinx\Migration\AbstractMigration;

class AppleAuth extends AbstractMigration
{

	public function change(): void
	{
		$table = $this->table('user_apple_auth');

		$table
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя'])
			->addColumn('sub', 'text', ['comment' => 'Уникальный Apple идентификатор пользователя'])
			->addIndex(['sub'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'CASCADE'])
			->create()
		;
	}

}
