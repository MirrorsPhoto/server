<?php

use Core\Exception\BadRequest;
use Core\Exception\ServerError;
use Validator\Auth\Login;

/**
 * @RoutePrefix('/auth')
 */
class AuthController extends Controller
{

	/**
	 * @throws BadRequest
	 * @throws ServerError
	 * @return array
	 */
	public function loginAction() {
		$validator = new Login();
		$validator->validate();

		$login = $this->getPost('login');
		$password = $this->getPost('password');

		/** @var User $user */
		$user = User::findFirstByUsername($login);

		if (!$user || !password_verify($password, $user->password)) {
			throw new BadRequest('auth.invalid_login_or_pass'); //Не верный логин или пароль
		}

		$jwt = JWT::getInstance();

		$token = $jwt->encode([
			'id' => $user->id,
			'username' => $user->username,
			'first_name' => $user->first_name,
			'middle_name' => $user->middle_name,
			'last_name' => $user->last_name,
			'email' => $user->email,
			'role_id' => $user->getRole()->id,
			'role_phrase' => $user->getRole()->getPhrase(),
			'avatar' => $user->getAvatar() ? $user->getAvatar()->fullPath : ''
		]);

		$user->token = $token;

		if (!$user->update()) {
			throw new ServerError();
		}

		return [
			'token' => $token
		];
	}

}