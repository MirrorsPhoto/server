<?php

use Phinx\Migration\AbstractMigration;

class DeviceNotificationUnique extends AbstractMigration
{

	public function change(): void
	{
		$table = $this->table('user_notification_device');

		$table
			->addIndex(['user_id', 'device_token'], ['unique' => true])
		;

		$table->save();
	}

}
