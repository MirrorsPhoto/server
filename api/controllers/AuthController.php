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

		$user = User::findFirstByUsername($login);

		if (!$user || !password_verify($password, $user->password)) throw new \Core\Exception\BadRequest('auth.invalid_login_or_pass'); //Не верный логин или пароль

		$jwt = JWT::getInstance();

		$token = $jwt->encode([
			'id' => $user->id,
			'username' => $user->username,
			'first_name' => $user->first_name,
			'middle_name' => $user->middle_name,
			'last_name' => $user->last_name,
			'email' => $user->email,
			'role_id' => $user->role->id,
			'role_phrase' => $user->role->getPhrase(),
			'avatar' => $user->avatar ? $user->avatar->fullPath : ''
		]);

		$user->token = $token;

		if ($user->update()) {
			return [
				'token' => $token
			];
		}
	}

}