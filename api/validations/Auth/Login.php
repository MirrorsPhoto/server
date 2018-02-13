<?php

namespace Validator\Auth;

use \Phalcon\Validation\Validator\PresenceOf;

class Login extends \Validation
{

	public function initialize()
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