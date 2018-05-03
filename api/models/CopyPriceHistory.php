<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class CopyPriceHistory extends Model
{

	protected $_tableName = 'copy_price_history';

  /**
   *
   * @var integer
   * @Column(type="integer", length=11, nullable=false)
   */
  public $copy_id;

	/**
	 *
	 * @var integer
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $department_id;

	/**
	 *
	 * @var integer
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $price;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_from;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $datetime_to;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
        $this->belongsTo('copy_id', '\Copy', 'id', ['alias' => 'Copy']);
    }

	/**
	 * Validations and business logic
	 *
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'user_id',
			new Numericality(
				[
					'message' => 'Id пользователя должно быть числом',
				]
			)
		);

    $validator->add(
      'copy_id',
      new Numericality(
        [
          'message' => 'Id копии должно быть числом',
        ]
      )
    );

		$validator->add(
			'price',
			new Numericality(
				[
					'message' => 'Цена должна быть числом',
				]
			)
		);

    $validator->add(
      'copy_id',
      new PresenceOf(
        [
          'message' => 'Id копии обезательное поле',
        ]
      )
    );

		$validator->add(
			'user_id',
			new PresenceOf(
				[
					'message' => 'Id пользователя обезательное поле',
				]
			)
		);

		$validator->add(
			'price',
			new PresenceOf(
				[
					'message' => 'Цена обезательное поле',
				]
			)
		);

		return $this->validate($validator);
	}

	public function beforeSave()
	{
		$user = \Core\UserCenter\Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}

}
