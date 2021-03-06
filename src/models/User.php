<?php

use Core\Exception\ServerError;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class User
 *
 * @property int department_id
 * @property Simple currentDepartments
 * @method getDepartments($con)
 * @method static self findFirstByUsername(string $login)
 * @method Role getRole()
 * @method File getAvatar()
 * @method UserAppleAuth getAppleAuth()
 */
class User extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'user';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $username;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $first_name;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $last_name;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=true)
	 */
	public $middle_name;

	/**
	 * @var int
	 *
	 * @Column(type="integer", nullable=false)
	 */
	public $role_id;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $password;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $email;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $token;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $avatar_id;

	public function initialize(): void
	{
		parent::initialize();

		$this->belongsTo('avatar_id', '\File', 'id', ['alias' => 'Avatar']);
		$this->belongsTo('role_id', '\Role', 'id', ['alias' => 'Role']);
		$this->hasManyToMany(
			'id',
			'DepartmentPersonnelHistory',
			'user_id',
			'department_id',
			'Department',
			'id',
			[
				'alias' => 'Departments',
			]
		);
		$this->hasOne('id', '\UserAppleAuth', 'user_id', ['alias' => 'AppleAuth']);
		$this->hasMany('id', '\UserNotificationDevice', 'user_id', ['alias' => 'NotificationDevices']);
		$this->hasManyToMany(
			'id',
			'UserType',
			'user_id',
			'type_id',
			'Type',
			'id',
			[
				'alias' => 'Types',
			]
		);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'email',
			new EmailValidator(
				[
					'model'   => $this,
					'message' => 'Введите корректный адрес',
				]
			)
		);

		return $this->validate($validator);
	}

	/**
	 * Метод для получения всех салонов где работает данный пользователь в настоящее время
	 */
	public function getCurrentDepartments(): Simple
	{
		return $this->getDepartments('datetime_to IS NULL');
	}

	/**
	 * @throws ServerError
	 */
	public function generateToken(): string
	{
		$jwt = JWT::getInstance();

		$token = $jwt->encode([
			'id' => $this->id,
			'username' => $this->username,
			'first_name' => $this->first_name,
			'middle_name' => $this->middle_name,
			'last_name' => $this->last_name,
			'email' => $this->email,
			'role_id' => $this->getRole()->id,
			'role_phrase' => $this->getRole()->getPhrase(),
			'avatar' => $this->getAvatar()->fullPath ?: null,
			'apple_sub' => $this->getAppleAuth()->sub ?: null,
			'allowed_types' => array_column($this->getTypes()->toArray(), 'name'),
		]);

		$this->token = $token;

		if (!$this->update()) {
			throw new ServerError();
		}

		return $token;
	}

	public function getTypes(): Simple
	{
		if ($this->isAdmin()) {
			return Type::find();
		}

		return $this->Types;
	}

	public function isAdmin(): bool
	{
		return $this->role_id === Role::ADMIN;
	}

}
