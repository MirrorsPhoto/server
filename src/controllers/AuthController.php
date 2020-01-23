<?php

use CoderCat\JWKToPEM\Exception\Base64DecodeException;
use CoderCat\JWKToPEM\Exception\JWKConverterException;
use CoderCat\JWKToPEM\JWKConverter;
use Core\Curl;
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
	 * @throws BadRequest
	 * @throws ServerError
	 * @throws Base64DecodeException
	 * @throws JWKConverterException
	 *
	 * @return string[]
	 */
	public function appleLoginAction(): array
	{
		$token = $this->getPost('token');

		if (empty($token)) {
			throw new BadRequest('Token field is required');
		}

		$key = Curl::getInstance()->get('https://appleid.apple.com/auth/keys')->keys[0];

		$jwkConverter = new JWKConverter();

		$jwt = JWT::decode($token, $jwkConverter->toPEM((array) $key), [$key->alg]);

		if ($jwt->iss !== 'https://appleid.apple.com') {
			throw new BadRequest('Invalid token');
		}

		if ($jwt->aud !== $_ENV['APPLE_CLIENT_ID']) {
			throw new BadRequest('Invalid token');
		}

		$appleAuth = UserAppleAuth::findFirstBySub($jwt->sub);

		if (empty($appleAuth)) {
			throw new BadRequest('You are not signuped');
		}

		return [
			'token' => $appleAuth->getUser()->generateToken(),
		];
	}

	/**
	 * @throws BadRequest
	 * @throws Base64DecodeException
	 * @throws JWKConverterException
	 * @throws ServerError
	 *
	 * @return string[]
	 */
	public function appleSubscribeAction(): array
	{
		$token = $this->getPost('token');

		if (empty($token)) {
			throw new BadRequest('Token field is required');
		}
		$key = Curl::getInstance()->get('https://appleid.apple.com/auth/keys')->keys[0];

		$jwkConverter = new JWKConverter();

		$jwt = JWT::decode($token, $jwkConverter->toPEM((array) $key), [$key->alg]);

		if ($jwt->iss !== 'https://appleid.apple.com') {
			throw new BadRequest('Invalid token');
		}

		if ($jwt->aud !== $_ENV['APPLE_CLIENT_ID']) {
			throw new BadRequest('Invalid token');
		}

		$user = \Core\UserCenter\Security::getUser();

		$appleAuth = new UserAppleAuth([
			'user_id' => $user->id,
			'sub' => $jwt->sub,
		]);

		$appleAuth->save();

		return [
			'token' => $appleAuth->getUser()->generateToken(),
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
