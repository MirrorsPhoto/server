<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Photo extends Model
{

	protected $_tableName = 'photo';

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $photo_size_id;

    /**
     *
     * @var string
     * @Column(type="integer", nullable=false)
     */
    public $count;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_create;

    public function initialize()
    {
	    parent::initialize();

      $this->hasMany('id', 'PhotoPriceHistory', 'photo_id', ['alias' => 'PhotoPriceHistory']);
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
      'photo_size_id',
      new Numericality(
        [
          'message' => 'Id размера фотографии должно быть числом',
        ]
      )
    );

    $validator->add(
      'count',
      new Numericality(
        [
          'message' => 'Количество штук должно быть числом',
        ]
      )
    );

    $validator->add(
      'photo_size_id',
      new PresenceOf(
        [
          'message' => 'Id размера фотографии обязательно',
        ]
      )
    );

    $validator->add(
      'count',
      new PresenceOf(
        [
          'message' => 'Количество штук обязательно',
        ]
      )
    );

		return $this->validate($validator);
	}

  public function getPrice()
  {
    $department_id = Core\UserCenter\Security::getUser()->department_id;

    $row = $this->getPhotoPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

    if (!$row) throw new \Core\Exception\ServerError("Для фотографии {$this->width}x{$this->height} с количеством {$this->count}шт не задана цена");

    return (float) $row->price;
  }

  public static function batch($data)
  {
    $photoId = $data->id;
    $count = $data->copies;

    $row = self::findFirst($photoId);

    for ($i = 1; $i <= $count; $i++) {
      $row->sale();
    }
  }

  public function sale()
  {
    $newSaleRow = new PhotoSale([
      'photo_id' => $this->id
    ]);

    $newSaleRow->save();

    return $newSaleRow;
  }

}
