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

}
