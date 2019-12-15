<?php

use Core\Exception\ServerError;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class UserAppleAuth
 *
 * @method User getUser()
 * @method static self findFirstBySub($sub)
 */
class UserAppleAuth extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'user_apple_auth';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $sub;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasOne('user_id', '\User', 'id', ['alias' => 'User']);
	}

}
