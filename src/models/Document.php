<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Document
 *
 * @property float price
 * @method DocumentPriceHistory getDocumentPriceHistory(string $string)
 */
class Document extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'document';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'DocumentPriceHistory', 'document_id', ['alias' => 'DocumentPriceHistory']);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Название обязательно',
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

		$row = $this->getDocumentPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		return (float) $row->price ?? 0;
	}

	/**
	 * @throws ServerError
	 */
	public function sale(): DocumentSale
	{
		$newSaleRow = new DocumentSale([
			'document_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}

}
