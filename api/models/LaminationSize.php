<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class LaminationSize extends Model
{

	protected $_tableName = 'lamination_size';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $format;

    public function initialize()
    {
	    parent::initialize();

	    $this->hasMany('id', 'LaminationPriceHistory', 'lamination_size_id', ['alias' => 'LaminationPriceHistory']);
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
			'format',
			new PresenceOf(
				[
					'message' => 'Формат обязательн'
				]
			)
		);

		return $this->validate($validator);
	}

	public function getPrice()
	{
		$rowPrice =  $this->LaminationPriceHistory->filter(function ($price) {
			if ($price->lamination_size_id == $this->id && !$price->datetime_to) return $price;
		});

		if (isset($rowPrice[0]))
		{
			return $rowPrice[0]->price;
		}

		return null;
	}

}
