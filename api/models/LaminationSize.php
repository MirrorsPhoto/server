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
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$row = $this->getLaminationPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) throw new \Core\Exception\ServerError("Для ламинации размера {$this->format} не задана цена");

		return (float) $row->price;
	}

}
