<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Numericality;

class Good extends Model
{

	protected $_tableName = 'good';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $bar_code;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->hasMany('id', 'GoodPriceHistory', 'good_id', ['alias' => 'GoodPriceHistory']);
        $this->hasMany('id', 'Receipt', 'good_id', ['alias' => 'Receipt']);
        $this->hasMany('id', 'Sale', 'good_id', ['alias' => 'Sale']);
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
			'name',
			new Uniqueness(
				[
					'message' => 'Имя товара должно быть уникальным',
				]
			)
		);

		$validator->add(
			'bar_code',
			new Uniqueness(
				[
					'message' => 'Код товара должно быть уникальным',
				]
			)
		);



		$validator->add(
			'bar_code',
			new Numericality(
				[
					'message' => 'Код товара должно быть числовым значением',
				]
			)
		);

		return $this->validate($validator);
	}

	public function getPrice()
	{
		return $this->goodPriceHistory->filter(function ($price) {
			if ($price->good_id == 1 && !$price->datetime_to) return $price;
		})[0]->price;
	}

}
