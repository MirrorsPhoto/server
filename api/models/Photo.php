<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * @property float price
 * @method PhotoPriceHistory getPhotoPriceHistory(string $string)
 */
class Photo extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'photo';

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $photo_size_id;

	/**
	 * @var string
	 *
	 * @Column(type="integer", nullable=false)
	 */
	public $count;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'PhotoPriceHistory', 'photo_id', ['alias' => 'PhotoPriceHistory']);
	}

	public function validation(): bool
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

	/**
	 * @throws Unauthorized
	 */
	public function getPrice(): float
	{
		$department_id = Security::getUser()->department_id;

		$row = $this->getPhotoPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		return (float) $row->price ?? 0;
	}

	/**
	 * @throws ServerError
	 */
	public function sale(): PhotoSale
	{
		$newSaleRow = new PhotoSale([
			'photo_id' => $this->id
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}
}
