<?php

use Core\Exception\ServerError;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Role extends Model
{
	const GUEST = 1;
	const ADMIN = 2;
	const STAFF = 3;
	const USER  = 4;

	/**
	 * @var string
	 */
	protected $tableName = 'role';

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->hasMany('id', 'User', 'role_id', [ 'alias' => 'Users' ]);
	}

	/**
	 * @return boolean
	 */
	public function validation()
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
	 * @return string
	 */
	public function getPhrase()
	{
		switch ($this->id) {
			case 2:
				$phrase = 'admin';
				break;
			case 3:
				$phrase = 'staff';
				break;
			default:
				throw new ServerError('Not found phrase role for ' . $this->name);
		}

		return "user.roles.$phrase";
	}
}
