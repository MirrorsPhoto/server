<?php

use Core\UserCenter\Exception\Unauthorized;
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

	/**
	 * @var bool
	 *
	 * @Column(type="bool", nullable=false)
	 */
	public $is_test;

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
		$users = $this->getCurrentPersonnel();
		$socket = WebSocket::getInstance();

		/** @var User $user */
		foreach ($users as $user) {
			$summary = $this->getSummary($user);

			$socket->send($user->id, $summary);
		}
	}

	/**
	 * @param User $user
	 * @throws Exception
	 *
	 * @return mixed[]
	 */
	public function getSummary(User $user): array
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
		$types = $user->getTypes();

		$query = [
			'cash' => [],
			'client' => [],
		];

		/** @var DateTime $time */
		foreach ($datetime as $moment => $time) {
			foreach ($types as $type) {
				$typeName = $type->name;

				$query['cash'][$moment][] = 'SELECT '
					. "'{$typeName}' as type, "
					. "SUM({$typeName}_price_history.price) as summ "
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
					. "AND {$typeName}_sale.department_id = $department_id "
					. (!$user->isAdmin() ? " AND {$typeName}_sale.user_id = {$user->id}" : '')
				;
			}

			if (!empty($query['cash'])) {
				$query['cash'][$moment] = implode(' UNION ALL ', $query['cash'][$moment]);
			}

			$typesForQuery = array_map(function ($item) {
				return "'$item'";
			}, array_column($types->toArray(), 'name'));

			$implodedTypes = !empty($typesForQuery) ? implode(', ', $typesForQuery) : "''";

			$query['client'][$moment] = 'SELECT'
				. "'{$moment}' as moment, "
				. 'COUNT(*) '
				. 'FROM '
				. '(SELECT '
				. 'id '
				. 'FROM '
				. '(SELECT '
				. '*, '
				. 'json_array_elements(data::json) as "data_json" '
				. 'FROM "check" '
				. 'WHERE '
				. "datetime::date = '{$time->format('Y-m-d')}' "
				. "AND date_trunc('second', datetime) <= '{$time->format('Y-m-d H:i:s')}' "
				. (!$user->isAdmin() ? "AND user_id = {$user->id} " : '')
				. "AND department_id = $department_id) "
				. 'as a '
				. "WHERE data_json->>'type' IN ($implodedTypes) "
				. 'GROUP BY id) '
				. 'as b'
			;
		}

		$query['client'] = implode(' UNION ALL ', $query['client']);

		if (!empty($query['cash'])) {
			$result = $this->getDI()->getShared('db')->query($query['cash']['today']);

			foreach ($result->fetchAll(Db::FETCH_ASSOC) as $res) {
				$amount = (int) $res['summ'];
				if ($this->is_test) {
					$amount += 1000;
				}

				$data['cash']['today'][$res['type']] = $amount;
			}

			unset($query['cash']['today']);

			$agoSql = [];

			foreach ($query['cash'] as $moment => $sql) {
				$agoSql[] = "(WITH {$moment} AS ({$sql}) SELECT '{$moment}' as moment, SUM(summ) FROM {$moment})";
			}

			$agoSql = implode(' UNION ALL ', $agoSql);

			$result = $this->getDI()->getShared('db')->query($agoSql);

			foreach ($result->fetchAll(Db::FETCH_ASSOC) as $res) {
				$amount = (int) $res['sum'];
				if ($this->is_test) {
					$amount += 1000;
				}

				$data['cash'][$res['moment']] = $amount;
			}
		} else {
			foreach (array_keys($datetime) as $moment) {
				$data['cash'][$moment] = 0;
			}

			$data['cash']['today'] = [
				'total' => 0,
			];
		}
		$data['cash']['today']['total'] = array_sum($data['cash']['today']);

		$result = $this->getDI()->getShared('db')->query($query['client']);
		foreach ($result->fetchAll(Db::FETCH_ASSOC) as $res) {
			$count = (int) $res['count'];
			if ($this->is_test) {
				$count += 1000;
			}

			$data['client'][$res['moment']] = $count;
		}

		return $data;
	}

}
