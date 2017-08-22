<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Date;

class GoodPriceHistory extends Model
{

	protected $_tableName = 'good_price_history';

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $good_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $user_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
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
        $this->belongsTo('good_id', '\Good', 'id', ['alias' => 'Good']);
        $this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
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
			'good_id',
			new Numericality(
				[
					'message' => 'Id товара должно быть числом',
				]
			)
		);

		$validator->add(
			'user_id',
			new Numericality(
				[
					'message' => 'Id пользователя должно быть числом',
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
			'datetime_from',
			new Date(
				[
					'message' => 'Не верный формат даты',
				]
			)
		);

		$validator->add(
			'datetime_to',
			new Date(
				[
					'message' => 'Не верный формат даты',
				]
			)
		);

		return $this->validate($validator);
	}

}