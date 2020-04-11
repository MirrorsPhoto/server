<?php

use Apple\ApnPush\Exception\SendNotification\SendNotificationException;
use Core\APN;
use Phalcon\Cli\Task;

class CheckAPNTokenTask extends Task
{

	public function mainAction(): void
	{
		$feedback = new ApnsPHP_Feedback(
			ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
			__DIR__ . '/../apns-prod-cert.pem'
		);

		$feedback->connect();

		$apps = $feedback->receive();

		foreach ($apps as $app) {
			$deviceToken = $app['deviceToken'];

			$device = UserNotificationDevice::findFirstByDeviceToken($deviceToken);

			$device->delete();
		}

		$feedback->disconnect();
	}

}
