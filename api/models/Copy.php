<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Copy
 *
 * @property float price
 * @method CopyPriceHistory getCopyPriceHistory(string $string)
 */
class Copy extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'copy';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $format;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'CopyPriceHistory', 'copy_id', ['alias' => 'CopyPriceHistory']);
		$this->hasMany('id', 'CopySale', 'copy_id', ['alias' => 'CopySale']);
	}

	public function validation(): bool
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

	/**
	 * @throws ServerError
	 * @throws Unauthorized
	 */
	public function getPrice(): float
	{
		$department_id = Security::getUser()->department_id;

		$row = $this->getCopyPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) {
			throw new ServerError('copy.not_price');
		}

		return (float) $row->price;
	}

	/**
	 * @throws ServerError
	 */
	public function sale(): CopySale
	{
		$newSaleRow = new CopySale([
			'copy_id' => $this->id
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}
}
