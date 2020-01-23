<?php

use Core\Exception\BadRequest;
use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;

/**
 * @RoutePrefix('/user')
 */
class UserController extends Controller
{

	/**
	 * @throws Unauthorized
	 * @throws ServerError
	 */
	public function editSelfAction(): string
	{
		$user = Security::getUser();

		if ($this->request->hasFiles(true)) {
			$file = $this->request->getUploadedFiles(true)[0];

			$objFile = File::factory($file);

			$user->avatar_id = $objFile->id;
		}

		$username = $this->getPost('username');
		if ($username) {
			$user->username = $username;
		}

		$user->save();

		return 'Профиль успешно изменён';
	}

	/**
	 * @throws ServerError
	 * @throws Unauthorized
	 * @throws BadRequest
	 */
	public function notificationDeviceSubscribeAction(): bool
	{
		$user = Security::getUser();

		$token = $this->getPost('token');

		$data = [
			'user_id' => $user->id,
			'device_token' => $token,
		];

		$device = UserNotificationDevice::find($data);

		if (!$device->count()) {
			throw new BadRequest('Это устройство уже зарегистрировано');
		}

		$device = new UserNotificationDevice([
			'user_id' => $user->id,
			'device_token' => $token,
		]);

		$device->save();

		return true;
	}

	public function notificationDeviceUnsubscribeAction(): bool
	{
		$token = $this->getPost('token');

		$device = UserNotificationDevice::findFirstByDeviceToken($token);

		if ($device) {
			$device->delete();
		}

		return true;
	}

}
