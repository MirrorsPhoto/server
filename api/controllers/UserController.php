<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;

/**
 * @RoutePrefix('/user')
 */
class UserController extends Controller
{

	/**
	 * @throws ServerError
	 * @throws Unauthorized
	 * @return string
	 */
	public function editSelfAction()
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

		return "Профиль успешно изменён";
	}

}