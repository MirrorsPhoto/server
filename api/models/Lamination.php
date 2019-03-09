<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Lamination extends Model
{

	protected $_tableName = 'lamination';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $format;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_create;

    public function initialize()
    {
	    parent::initialize();

	    $this->hasMany('id', 'LaminationPriceHistory', 'lamination_id', ['alias' => 'LaminationPriceHistory']);
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

	public static function batch($data)
	{
		$row = self::findFirst($data->id);

		for ($i = 1; $i <= $data->copies; $i++) {
			$row->sale();
		}
	}

	public function sale()
	{
		$newSaleRow = new LaminationSale([
			'lamination_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}

}
