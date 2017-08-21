<?php

/**
 * @RoutePrefix('/auth')
 */
class AuthController extends Controller
{

	public function loginAction()
	{
		(new \Validator\Auth\Login())->validate();

		$login = $this->getPost('login');
		$password = $this->getPost('password');

		$user = Users::findFirstByUsername($login);

		if (!$user || ($user->password != $password)) throw new \Core\Exception\BadRequest('Не верный логин или пароль');

		$jwt = JWT::getInstance();

		$token = $jwt->encode($user->toArray([
			'id',
			'username',
			'first_name',
			'middle_name',
			'last_name',
			'email',
			'role',

		]));

		$user->token = $token;

		if ($user->update()) {
			return [
				'token' => $token
			];
		}
	}

}