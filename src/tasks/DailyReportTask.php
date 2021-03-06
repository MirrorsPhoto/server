<?php

use Apple\ApnPush\Exception\SendNotification\SendNotificationException;
use Core\APN;
use Phalcon\Cli\Task;

class DailyReportTask extends Task
{

	const REPORT_TIME = '17:00';

	/**
	 * @var APN
	 */
	private $apn;

	/**
	 * @var string
	 */
	private $currentTime;

	public function mainAction(): void
	{
		$currentTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
		$this->currentTime = $currentTime->format('H:i');

		$this->apn = new APN();

		$departments = Department::find();

		foreach ($departments as $department) {
			$this->notifyPersonnel($department);
		}
	}

	private function notifyPersonnel(Department $department): void
	{
		$users = $department->getCurrentPersonnel();

		/** @var User $user */
		foreach ($users as $user) {
			if ($this->currentTime !== self::REPORT_TIME) {
				continue;
			}

			$devices = $user->NotificationDevices;
			$info = $department->getSummary($user);

			foreach ($devices as $device) {
				$this->sendToDevice($device, $info);
			}
		}
	}

	/**
	 * @param UserNotificationDevice $device
	 * @param mixed[] $info
	 */
	private function sendToDevice(UserNotificationDevice $device, array $info): void
	{
		try {
			$this->apn->send($device->device_token, [
				'key' => 'Today report title',
			], [
				'key' => 'Today report body',
				'args' => [
					(string) $info['client']['today'],
					(string) $info['cash']['today']['total'] . '₽',
				],
			], [
				'data' => json_encode($info),
				'time' => (string) time(),
			], 'todayReport');
		} catch (SendNotificationException $e) {
			return;
		}
	}

}
