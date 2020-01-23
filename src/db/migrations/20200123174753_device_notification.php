<?php


use Phinx\Migration\AbstractMigration;

class DeviceNotification extends AbstractMigration
{

	public function change(): void
	{
		$table = $this->table('user_notification_device');

		$table
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя'])
			->addColumn('device_token', 'text', ['comment' => 'Идентификатор устроства куда слать уведомление'])
			->addIndex(['device_token'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'CASCADE'])
			->create()
		;
	}

}
