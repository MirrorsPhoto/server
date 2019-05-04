<?php

namespace Validator\Auth;

use Phalcon\Validation\Validator\PresenceOf;
use Validation;

class Login extends Validation
{
	public function initialize(): void
	{
		$this->add(
			'login',
			new PresenceOf(
				[
					'message' => 'auth.login.required',
				]
			)
		);

		$this->add(
			'password',
			new PresenceOf(
				[
					'message' => 'auth.password.required',
				]
			)
		);
	}
}
