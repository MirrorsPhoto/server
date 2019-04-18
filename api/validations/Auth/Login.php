<?php

namespace Validator\Auth;

use Phalcon\Validation\Validator\PresenceOf;
use Validation;

class Login extends Validation
{

	/**
	 * @return void
	 */
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