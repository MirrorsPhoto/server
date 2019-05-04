<?php

use Core\Exception\ServerError;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Role extends Model
{
	const ADMIN = 1;
	const STAFF = 2;
	const USER  = 3;
	const GUEST = 4;

	/**
	 * @var string
	 */
	protected $tableName = 'role';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'User', 'role_id', [ 'alias' => 'Users' ]);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Название роли обязательно для заполнения',
				]
			)
		);

		return $this->validate($validator);
	}

	/**
	 * @throws ServerError
	 */
	public function getPhrase(): string
	{
		switch ($this->id) {
			case 1:
				$phrase = 'admin';
				break;
			case 2:
				$phrase = 'staff';
				break;
			default:
				throw new ServerError('Not found phrase role for ' . $this->name);
		}

		return $phrase;
	}
}
