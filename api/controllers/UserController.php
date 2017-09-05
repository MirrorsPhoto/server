<?php

/**
 * @RoutePrefix('/user')
 */
class UserController extends Controller
{

	public function editSelfAction()
	{
		$user = \Core\UserCenter\Security::getUser();

		if ($this->request->hasFiles(true)) {
			$file = $this->request->getUploadedFiles(true)[0];

			$objFile = File::factory($file);

			$user->avatar_id = $objFile->id;
		}

		if ($username = $this->getPost('username')) {
			$user->username = $username;
		}

		$user->save();

		return "Профиль успешно изменён";
	}

}