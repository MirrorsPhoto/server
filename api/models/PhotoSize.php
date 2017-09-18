<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class PhotoSize extends Model
{

	protected $_tableName = 'photo_size';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $width;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $height;

    public function initialize()
    {
	    parent::initialize();

	    $this->hasMany('id', 'PhotoPriceHistory', 'photo_size_id', ['alias' => 'PhotoPriceHistory']);
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
			'width',
			new PresenceOf(
				[
					'message' => 'Ширина обязательна'
				]
			)
		);

		$validator->add(
			'height',
			new PresenceOf(
				[
					'message' => 'Высота обязательна'
				]
			)
		);

		$validator->add(
			'width',
			new Numericality(
				[
					'message' => 'Ширина должна быть числовым значением',
				]
			)
		);

		$validator->add(
			'height',
			new Numericality(
				[
					'message' => 'Высота должна быть числовым значением',
				]
			)
		);

		return $this->validate($validator);
	}

	public function getPrice($count)
	{
		$rowPrice =  $this->PhotoPriceHistory->filter(function ($price) use ($count) {
			if ($price->photo_size_id == $this->id && !$price->datetime_to && $price->count == $count) return $price;
		});

		if (isset($rowPrice[0]))
		{
			return $rowPrice[0]->price;
		}

		return null;
	}

	/**
	 * Возвращает массив с вариацией количства фотографий для данного размера
	 * @return array
	 */
	public function getCounts()
	{
		$arrCounts = [];

		$this->PhotoPriceHistory->filter(function ($price) use (&$arrCounts) {
			if (!$price->datetime_to) $arrCounts[] = $price->count;
		});

		return $arrCounts;
	}

}
