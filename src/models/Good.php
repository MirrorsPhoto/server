<?php

use Core\Exception\BadRequest;
use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @method static self findFirstByBarCode(int $barCode)
 * @method GoodPriceHistory getGoodPriceHistory(string $string)
 * @property float price
 */
class Good extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'good';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=true)
	 */
	public $description;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=true)
	 */
	public $bar_code;

	/**
	 * @var string
	 *
	 * @Column(type="boolean", nullable=false)
	 */
	public $is_delete;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'GoodPriceHistory', 'good_id', ['alias' => 'GoodPriceHistory']);
		$this->hasMany('id', 'GoodReceipt', 'good_id', ['alias' => 'Receipts']);
		$this->hasMany('id', 'GoodSale', 'good_id', ['alias' => 'Sales']);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'name',
			new Uniqueness(
				[
					'message' => 'Имя товара должно быть уникальным',
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

		$row = $this->getGoodPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) {
			throw new ServerError("Для товара {$this->name} не задана цена");
		}

		return (float) $row->price;
	}

	/**
	 * Возвращает товары в наличии или один определённый товар
	 *
	 * @param int $id
	 * @throws Unauthorized
	 *
	 * @return Simple
	 */
	public static function getAvaible(int $id = 0): Simple
	{
		$department_id = Security::getUser()->department_id;

		$query = "SELECT
					good.*,
       				r.count - COALESCE(s.count, 0) as total 
				FROM good
				JOIN (
					SELECT 
						good_id, 
						COUNT(*)
					FROM good_receipt 
					WHERE department_id = $department_id 
					GROUP BY good_id
				) r ON good.id = r.good_id
				LEFT JOIN (
					SELECT
						good_id,
						COUNT(*)
					FROM good_sale
					WHERE department_id = $department_id
					GROUP BY good_id
				) s ON good.id = s.good_id
				WHERE r.count > COALESCE(s.count, 0)";

		if ($id) {
			$query .= " AND id = $id";
		}

		$selfObj = new self();

		return new Simple(
			null,
			$selfObj,
			$selfObj->getReadConnection()->query($query)
		);
	}

	/**
	 * Функция проверки наличия товара
	 *
	 * @throws Unauthorized
	 */
	public function isAvailable(): bool
	{
		$available = self::getAvaible($this->id);

		return (bool) $available->getFirst();
	}

	/**
	 * Сколько единиц данного товара в наличии
	 *
	 * @throws Unauthorized
	 */
	public function getAvaibleCount(): int
	{
		return (self::getAvaible($this->id))->getFirst()->total | 0;
	}

	/**
	 * @param int $count
	 * @return bool
	 * @throws BadRequest
	 * @throws ServerError
	 * @throws Unauthorized
	 */
	public function sale(int $count): bool
	{
		if ($this->is_delete) {
			throw new BadRequest("Нельзя записать продажу удалённого товара {$this->name}");
		}

		if (!$this->isAvailable()) {
			throw new BadRequest("Нельзя записать продажу товара {$this->name}, т.к. его не в наличии");
		}

		$rowSale = new GoodSale([
			'good_id' => $this->id,
			'count' => $count,

		]);

		return $rowSale->save();
	}

	/**
	 * @param float $price
	 * @throws ServerError
	 * @throws BadRequest
	 */
	public function receipt(float $price): bool
	{
		if ($this->is_delete) {
			throw new BadRequest("Нельзя записать приход удалённого товара {$this->name}");
		}

		$rowReceipt = new GoodReceipt([
			'good_id' => $this->id,
			'price' => $price,
		]);

		return $rowReceipt->save();
	}

}
