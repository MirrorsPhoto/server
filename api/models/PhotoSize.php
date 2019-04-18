<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @property Simple Photo
 */
class PhotoSize extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'photo_size';

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $width;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $height;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->hasMany('id', 'Photo', 'photo_size_id', ['alias' => 'Photo']);
	}

	/**
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

	/**
	 * Возвращает массив с вариацией количества фотографий для данного размера и цены
	 * @return array
	 */
	public function getVariations()
	{
		$result = [];

		/** @var Photo $photo */
		foreach ($this->Photo as $photo) {
			$result[] = [
				'id' => $photo->id,
				'count' => $photo->count,
				'price' => $photo->price
			];
		}

		return $result;
	}
}
