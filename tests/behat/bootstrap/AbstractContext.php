<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;

abstract class AbstractContext extends Assert implements Context
{
	private $roles = [
		'admin' => [
			'name' => 'Администратор',
			'value' => 1
		],
		'staff' => [
			'name' => 'Оператор',
			'value' => 2
		],
		'user' => [
			'name' => 'Пользователь',
			'value' => 3
		],
		'guest' => [
			'name' => 'Гость',
			'value' => 4
		]
	];

	protected $token;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var Generator
	 */
	protected $faker;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var resource
	 */
	private $connection;

	public function __construct()
	{
		$this->connection = pg_connect("
			host={$_ENV['DATABASE_HOST']}
			port={$_ENV['DATABASE_PORT']}
			dbname={$_ENV['DATABASE_NAME']}
			user={$_ENV['DATABASE_USERNAME']}
			password={$_ENV['DATABASE_PASSWORD']}
		");

		$this->faker = Factory::create('ru_RU');

		$this->client = new Client([
			'base_uri' => 'http://api.' . $_ENV['DOMAIN']
		]);
	}

	/**
	 * @BeforeScenario
	 */
	public function beforeScenario()
	{
		$this->eraseAllTables();
	}

	/**
	 * @Then response having error message:
	 * @param TableNode $table
	 */
	public function checkErrorMessage(TableNode $table)
	{
		/** @var Response $response */
		$response = $this->data['response'];
		$responseBody = $response['body'];

		self::assertArrayHasKey('status', $responseBody);
		$responseStatus = $responseBody['status'];
		self::assertEquals($responseStatus, 'ERROR');

		self::assertArrayHasKey('message', $responseBody);
		$responseMessage = $responseBody['message'];
		self::assertEquals($responseMessage, $table->getRow(0));
	}

	/**
	 * @Then the response status code should be :code
	 *
	 * @param int $code
	 */
	public function checkResponseCode(int $code)
	{
		/** @var Response $response */
		$response = $this->data['response'];
		$responseCode = $response['status'];

		self::assertEquals($responseCode, $code);

		if ($code >= 200 && $code < 300) {
			$responseBody = $response['body'];

			self::assertArrayHasKey('status', $responseBody);
			$responseStatus = $responseBody['status'];
			self::assertEquals($responseStatus, 'OK');
		}
	}

	/**
	 * @Then the :name header should be :value
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function checkResponseHeader(string $name, string $value)
	{
		/** @var Response $response */
		$response = $this->data['response'];
		$responseHeaders = $response['headers'];

		self::assertArrayHasKey($name, $responseHeaders);
		self::assertEquals($responseHeaders[$name][0], $value);
	}

	/**
	 * @Given that there is a :role role
	 *
	 * @param string $role
	 * @throws Exception
	 */
	public function createRole(string $role)
	{
		if (!isset($this->roles[$role])) {
			throw new InvalidArgumentException("$role role not supported");
		}

		$role = $this->roles[$role];

		$id = $this->insertDb('role', [
			'id' => $role['value'],
			'name' => $role['name']
		]);

		$this->data['role_id'] = $id;
	}

	/**
	 * @Given that there is a registered user :username with :password password
	 *
	 * @param string $username
	 * @param string $password
	 * @throws Exception
	 */
	public function registerUser(string $username, string $password)
	{
		$firstName = $this->faker->firstNameMale;
		$lastName = $this->faker->lastName;
		$middleName = $this->faker->firstNameMale;
		$email = $this->faker->email;
		$roleId = $this->data['role_id'] | 1;
		$password = password_hash($password, PASSWORD_BCRYPT);

		$id = $this->insertDb('user', [
			'role_id' => $roleId,
			'first_name' => $firstName,
			'middle_name' => $middleName,
			'last_name' => $lastName,
			'email' => $email,
			'username' => $username,
			'password' => $password,
		]);

		$this->data['user'] = [
			'id' => $id,
			'username' => $username,
			'first_name' => $firstName,
			'middle_name' => $middleName,
			'last_name' => $lastName,
			'email' => $email,
			'role_id' => $roleId,
			'role_phrase' => $this->getRolePhrase($roleId),
			'avatar' => null
		];
	}

	/**
	 * @When i am login as :username with :password password
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function loginUser(string $username, string $password)
	{
		$credentials = [
			'login' => $username,
			'password' => $password
		];

		$response = $this->request('login', 'POST', $credentials);

		$this->data['response'] = $response;
	}

	/**
	 * @Given i have token
	 */
	public function storeToken()
	{
		$response = $this->data['response'];
		$responseBody = $response['body'];

		$token = $responseBody['response']['token'];

		$this->token = $token;
	}

	/**
	 * @Given that there is exist city :city
	 *
	 * @param string $city
	 * @throws Exception
	 */
	public function createCity(string $city)
	{
		$id = $this->insertDb('city', [
			'name' => $city
		]);

		$this->data['city_id'] = $id;
	}

	/**
	 * @Given in this city exist department :name
	 *
	 * @param string $name
	 * @throws Exception
	 */
	public function createDepartment(string $name)
	{
		if (!isset($this->data['city_id'])) {
			throw new InvalidArgumentException("city_id is not set");
		}

		$cityId = $this->data['city_id'];
		$address = $this->faker->address;

		$id = $this->insertDb('department', [
			'city_id' => $cityId,
			'name' => $name,
			'address' => $address
		]);

		$this->data['departments'][$name] = $id;
	}

	/**
	 * @Given i work in :name department
	 *
	 * @param string $name
	 * @throws Exception
	 */
	public function addUserToDepartment(string $name)
	{
		if (!isset($this->data['departments'][$name])) {
			throw new InvalidArgumentException("department_id is not set");
		}

		if (!isset($this->data['user'])) {
			throw new InvalidArgumentException("user is not set");
		}

		$departmentId = $this->data['departments'][$name];
		$userId = $this->data['user']['id'];

		$this->insertDb('department_personnel_history', [
			'department_id' => $departmentId,
			'user_id' => $userId
		]);

		$this->data['user']['department'] = $name;
	}

	/** @noinspection PhpDocMissingThrowsInspection */
	/**
	 * @param string $path
	 * @param string $method
	 * @param array $body
	 * @param array $headers
	 * @return array
	 */
	protected function request(string $path, string $method = 'GET', array $body = [], array $headers = []) {
		$defaultHeaders = [
			'Content-Type' => 'application/json'
		];

		if (!empty($this->token)) {
			$defaultHeaders['Authorization'] = "Bearer {$this->token}";
		}

		/** @noinspection PhpUnhandledExceptionInspection */
		$response = $this->client->request($method, $path, [
			'headers' => $headers + $defaultHeaders,
			'json' => $body,
			'http_errors' => false
		]);

		return [
			'headers' => $response->getHeaders(),
			'status' => $response->getStatusCode(),
			'body' => json_decode($response->getBody()->getContents(), TRUE)
		];
	}

	/**
	 * @param string $table
	 * @param array $data
	 * @param string $idColumnName
	 * @throws Exception
	 * @return int
	 */
	protected function insertDb(string $table, array $data, string $idColumnName = 'id')
	{
		if (empty($data)) {
			throw new Exception('empty data to insert');
		}

		$colums = '"' . implode('", "', array_keys($data)) . '"';
		$values = "'" . implode("', '", $data) . "'";

		$query = "
			INSERT INTO public.{$table}
				({$colums})
				VALUES
				({$values})
			RETURNING {$idColumnName}
		";

		$result = $this->fetchDb($query);

		return (int) $result[0][$idColumnName];
	}

	/**
	 * @param string $query
	 * @return void
	 */
	protected function queryDb(string $query)
	{
		$this->pgQuery($query);
	}

	/**
	 * @param string $query
	 * @return array
	 */
	protected function fetchDb(string $query)
	{
		$result = $this->pgQuery($query);

		return pg_fetch_all($result);
	}

	/**
	 * @param $id
	 * @return string
	 */
	private function getRolePhrase($id)
	{
		$phrase = '';

		foreach ($this->roles as $name => $value) {
			if ($id === $value['value']) {
				$phrase = $name;
				break;
			}
		}

		return $phrase;
	}

	private function eraseAllTables()
	{
		$truncateQuery = $this->fetchDb("
			SELECT 'TRUNCATE TABLE ' || string_agg(oid::regclass::text, ', ') || ' CASCADE' as \"query\"
			FROM pg_catalog.pg_class
			WHERE
				relkind = 'r'
				AND relnamespace = 'public'::regnamespace
		");

		$truncateQuery = $truncateQuery[0]['query'];

		$this->queryDb($truncateQuery);
	}

	/**
	 * @param string $query
	 * @return resource
	 */
	private function pgQuery(string $query)
	{
		$result = pg_query($this->connection, $query);

		if ($result === false) {
			new Exception(pg_result_error($result));
		}

		return $result;
	}
}
