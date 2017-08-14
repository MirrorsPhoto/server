<?php

use \Phalcon\Validation\Validator\PresenceOf;

class AuthValidation extends Validation
{

	public function initialize()
	{
		$this->add(
			'login',
			new PresenceOf(
				[
					'message' => 'The login is required',
				]
			)
		);

		$this->add(
			'password',
			new PresenceOf(
				[
					'message' => 'The password is required',
				]
			)
		);
	}

}