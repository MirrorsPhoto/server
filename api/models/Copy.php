<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Copy extends Model
{

	protected $_tableName = 'copy';

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

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->hasMany('id', 'CopyPriceHistory', 'copy_id', ['alias' => 'CopyPriceHistory']);
        $this->hasMany('id', 'CopySale', 'copy_id', ['alias' => 'CopySale']);
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
					'message' => 'Формат обезательное поле',
				]
			)
		);

		return $this->validate($validator);
	}

	public function getPrice()
  {
    $department_id = Core\UserCenter\Security::getUser()->department_id;

    $row = $this->getCopyPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

    if (!$row) throw new \Core\Exception\ServerError("Для копии формата {$this->format} не задана цена");

    return (float) $row->price;
  }

	public static function batch($data)
	{
    $copyId = $data->id;
    $count = $data->copies;

    $row = self::findFirst($copyId);

		for ($i = 1; $i <= $count; $i++) {
      $row->sale();
		}
	}

	public function sale()
	{
		$newSaleRow = new CopySale([
      'copy_id' => $this->id
    ]);

		$newSaleRow->save();

		return $newSaleRow;
	}

	public static function getCash(Datetime $datetime)
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$query = "SELECT SUM(copy_price_history.price) as summ FROM copy_sale
JOIN copy_price_history ON copy_sale.datetime >= copy_price_history.datetime_from AND (copy_sale.datetime < copy_price_history.datetime_to OR copy_price_history.datetime_to IS NULL) AND copy_price_history.department_id = copy_sale.department_id AND copy_sale.copy_id = copy_price_history.copy_id
WHERE copy_sale.datetime::date = '{$datetime->format('Y-m-d')}' AND copy_sale.datetime <= '{$datetime->format('Y-m-d H:i:s')}' AND copy_sale.department_id = " .  $department_id;

		$selfObj = new self();

		$result = new Resultset(null, $selfObj, $selfObj->getReadConnection()->query($query));

		return (int) $result->getFirst()->summ;
	}

}
