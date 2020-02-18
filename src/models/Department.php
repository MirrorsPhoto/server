<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Db;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Department
 *
 * @method getUsers($con)
 */
class Department extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'department';

	/**
	 * @var int
	 *
	 * @Column(type="integer", nullable=false)
	 */
	public $city_id;

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
	public $address;

	public function initialize(): void
	{
		parent::initialize();

		$this->belongsTo('city_id', '\City', 'id', ['alias' => 'City']);
		$this->hasManyToMany(
			'id',
			'DepartmentPersonnelHistory',
			'department_id',
			'user_id',
			'User',
			'id',
			[
				'alias' => 'Users',
			]
		);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'city_id',
			new PresenceOf(
				[
					'message' => 'Город обязателен',
				]
			)
		);

		$validator->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Название салона обязательно',
					]
			)
		);

		$validator->add(
			'address',
			new PresenceOf(
				[
					'message' => 'Адрес салона обязателен',
					]
			)
		);

		return $this->validate($validator);
	}


	/**
	 * Метод для получения всех текущих сотрудников данного салона
	 */
	public function getCurrentPersonnel(): Simple
	{
		return $this->getUsers('datetime_to IS NULL');
	}

	/**
	 * @throws Exception
	 * @throws Unauthorized
	 */
	public function notifyPersonnels(): void
	{
		$userRows = $this->getCurrentPersonnel();
		$arrUserIds = array_column($userRows->toArray(), 'id');
		$data = $this->getSummary();

		$socket = WebSocket::getInstance();

		$socket->send($arrUserIds, $data);
	}

	/**
	 * @throws Unauthorized
	 *
	 * @return mixed[]
	 */
	public function getSummary(): array
	{
		$department_id = $this->id;

		$data = [];

		$timeZone = new DateTimeZone('Europe/Moscow');
		$datetime = [
			'today' => new DateTime('now', $timeZone),
			'yesterday' => new DateTime('previous day', $timeZone),
			'week' => new DateTime('previous weeks', $timeZone),
			'month' => new DateTime('previous month', $timeZone),
			'year' => new DateTime('previous year', $timeZone),
		];

		/** @var Type[] $types */
		$types = Type::find();

		$query = [];

		/** @var DateTime $time */
		foreach ($datetime as $moment => $time) {
			foreach ($types as $type) {
				$typeName = $type->name;

				$query['cash'][$moment][] = "SELECT '{$typeName}' as type, COALESCE(SUM(summ), 0) as summ from (SELECT "
					. "{$typeName}_price_history.price * {$typeName}_sale.count as summ "
					. "FROM {$typeName}_sale "
					. "JOIN {$typeName}_price_history ON "
					. "{$typeName}_sale.{$typeName}_id = {$typeName}_price_history.{$typeName}_id "
					. "AND {$typeName}_sale.datetime >= {$typeName}_price_history.datetime_from "
					. "AND ({$typeName}_sale.datetime < {$typeName}_price_history.datetime_to "
					. 'OR '
					. "{$typeName}_price_history.datetime_to IS NULL) "
					. "AND {$typeName}_sale.department_id = {$typeName}_price_history.department_id "
					. 'WHERE '
					. "{$typeName}_sale.datetime::date = '{$time->format('Y-m-d')}' "
					. "AND date_trunc('second', {$typeName}_sale.datetime) <= '{$time->format('Y-m-d H:i:s.u')}' "
					. "AND {$typeName}_sale.department_id = $department_id) as a"
				;
			}
			$query['cash'][$moment] = implode(' UNION ALL ', $query['cash'][$moment]);

			$query['client'][$moment] = 'SELECT '
				. "'{$moment}' as moment, "
				. 'COUNT(*) as count '
				. 'FROM "check" '
				. 'WHERE '
				. "datetime::date = '{$time->format('Y-m-d')}' "
				. "AND date_trunc('second', datetime) <= '{$time->format('Y-m-d H:i:s')}' "
				. "AND department_id = $department_id"
			;
		}

		$query['client'] = implode(' UNION ALL ', $query['client']);

		$result = $this->getDI()->getShared('db')->query($query['cash']['today']);

		foreach ($result->fetchAll(Db::FETCH_ASSOC) as $res) {
			$data['cash']['today'][$res['type']] = (int) $res['summ'];
		}

		unset($query['cash']['today']);

		$agoSql = [];

		foreach ($query['cash'] as $moment => $sql) {
			$agoSql[] = "(WITH {$moment} AS ({$sql}) SELECT '{$moment}' as momemt, SUM(summ) FROM {$moment})";
		}

		$agoSql = implode(' UNION ALL ', $agoSql);

		$result = $this->getDI()->getShared('db')->query($agoSql);

		foreach ($result->fetchAll(Db::FETCH_ASSOC) as $res) {
			$data['cash'][$res['momemt']] = (int) $res['sum'];
		}

		$data['cash']['today']['total'] = array_sum($data['cash']['today']);

		$result = $this->getDI()->getShared('db')->query($query['client']);
		foreach ($result->fetchAll(Db::FETCH_ASSOC) as $res) {
			$data['client'][$res['moment']] = (int) $res['count'];
		}

		return $data;
	}

}
