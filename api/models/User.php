<?php

use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class User
 *
 * @property int department_id
 * @property Simple currentDepartments
 *
 * @method getDepartments($con)
 * @method static self findFirstByUsername(string $login)
 * @method Role getRole()
 * @method File getAvatar()
 */
class User extends Model
{

	/**
	 * @var string
	 */
	protected $_tableName = 'user';

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $username;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $first_name;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $last_name;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	public $middle_name;

	/**
	 * @var int
	 * @Column(type="integer", nullable=false)
	 */
	public $role_id;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $password;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $email;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $token;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $avatar_id;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->belongsTo('avatar_id', '\File', 'id', ['alias' => 'Avatar']);
		$this->belongsTo('role_id', '\Role', 'id', ['alias' => 'Role']);
		$this->hasManyToMany(
			'id',
			'DepartmentPersonnelHistory',
			'user_id', 'department_id',
			'Department',
			'id',
			[
				'alias' => 'Departments'
			]
		);
	}

	/**
	 * @return boolean
	 */
	public function validation()
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
	 *
	 * @return Simple
	 */
	public function getCurrentDepartments()
	{
		return $this->getDepartments('datetime_to IS NULL');
	}

}
