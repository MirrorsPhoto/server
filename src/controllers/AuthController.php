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
	 * @throws ServerError
	 * @throws BadRequest
	 *
	 * @return mixed[]
	 */
	public function loginAction(): array
	{
		$validator = new Login();
		$validator->validate();

		$login = $this->getPost('login');
		$password = $this->getPost('password');

		/** @var User $user */
		$user = User::findFirstByUsername($login);

		if (!$user || !password_verify($password, $user->password)) {
			throw new BadRequest('auth.invalid_login_or_pass'); //Не верный логин или пароль
		}

		return [
			'token' => $user->generateToken(),
		];
	}

	/**
	 * @throws ServerError
	 *
	 * @return mixed[]
	 */
	public function appleLoginAction(): array
	{
		/** @var User $user */
		$user = User::findFirstByUsername('admin');

		return [
			'token' => $user->generateToken(),
		];
	}

	/**
	 * @Get('/check')
	 */
	public function checkAction(): bool
	{
		return true;
	}

}
