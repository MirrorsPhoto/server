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

	/**
	 * @var mixed[]
	 */
	private $roles = [
		'admin' => [
			'name' => 'Администратор',
			'value' => 1,
		],
		'staff' => [
			'name' => 'Оператор',
			'value' => 2,
		],
		'user' => [
			'name' => 'Пользователь',
			'value' => 3,
		],
		'guest' => [
			'name' => 'Гость',
			'value' => 4,
		],
	];

	/**
	 * @var string
	 */
	protected $token;

	/**
	 * @var mixed[]
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

	/**
	 * AbstractContext constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->connection = pg_connect("
			host={$_ENV['DATABASE_HOST']}
			port={$_ENV['DATABASE_PORT']}
			dbname={$_ENV['DATABASE_NAME']}
			user={$_ENV['DATABASE_USERNAME']}
			password={$_ENV['DATABASE_PASSWORD']}
		");

		if (!$this->connection) {
			throw new Exception('postgresql connection failed');
		}

		$this->faker = Factory::create('ru_RU');

		$this->client = new Client([
			'base_uri' => 'http://api.' . $_ENV['DOMAIN'],
		]);
	}

	/**
	 * @BeforeScenario
	 */
	public function beforeScenario(): void
	{
		$this->eraseAllTables();
	}

	/**
	 * @Then resp having error message:
	 *
	 * @param TableNode $table
	 */
	public function checkErrorMessage(TableNode $table): void
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
	 * @Then the resp status code should be :code
	 *
	 * @param int $code
	 */
	public function checkResponseCode(int $code): void
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
	public function checkResponseHeader(string $name, string $value): void
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
	public function createRole(string $role): void
	{
		if (!isset($this->roles[$role])) {
			throw new InvalidArgumentException("$role role not supported");
		}

		$role = $this->roles[$role];

		$id = $this->insertDb('role', [
			'id' => $role['value'],
			'name' => $role['name'],
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
	public function registerUser(string $username, string $password): void
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
			'avatar' => null,
		];
	}

	/**
	 * @When i am login as :username with :password password
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function loginUser(string $username, string $password): void
	{
		$credentials = [
			'login' => $username,
			'password' => $password,
		];

		$response = $this->request('login', 'POST', $credentials);

		$this->data['response'] = $response;
	}

	/**
	 * @Given i have token
	 */
	public function storeToken(): void
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
	public function createCity(string $city): void
	{
		$id = $this->insertDb('city', [
			'name' => $city,
		]);

		$this->data['city_id'] = $id;
	}

	/**
	 * @Given in this city exist department :name
	 *
	 * @param string $name
	 * @throws Exception
	 */
	public function createDepartment(string $name): void
	{
		if (!isset($this->data['city_id'])) {
			throw new InvalidArgumentException('city_id is not set');
		}

		$cityId = $this->data['city_id'];
		$address = $this->faker->address;

		$id = $this->insertDb('department', [
			'city_id' => $cityId,
			'name' => $name,
			'address' => $address,
		]);

		$this->data['departments'][$name] = $id;
	}

	/**
	 * @Given i work in :name department
	 *
	 * @param string $name
	 * @throws Exception
	 */
	public function addUserToDepartment(string $name): void
	{
		if (!isset($this->data['departments'][$name])) {
			throw new InvalidArgumentException('department_id is not set');
		}

		if (!isset($this->data['user'])) {
			throw new InvalidArgumentException('user is not set');
		}

		$departmentId = $this->data['departments'][$name];
		$userId = $this->data['user']['id'];

		$this->insertDb('department_personnel_history', [
			'department_id' => $departmentId,
			'user_id' => $userId,
		]);

		$this->data['user']['department'] = $name;
	}

	/**
	 * @Then resp must contain correct data
	 *
	 * @param PyStringNode $text
	 */
	public function checkResponse(PyStringNode $text): void
	{
		$text = $text->getRaw();
		$text = $string = preg_replace('/\s+/', '', $text);

		$response = $this->data['response']['body']['response'];
		$response = json_encode($response);

		$pattern = '@^' . preg_quote($text, '@') . '$@';
		$pattern = str_replace('\\\\d\\+', '\\d+', $pattern);
		$result = (bool) preg_match($pattern, $response);

		self::assertTrue($result);
	}

	/**
	 * @Then resp must contain numeric :value
	 */
	public function checkResponseNumeric(int $value): void
	{
		$response = $this->data['response']['body']['response'];

		self::assertEquals($response, $value);
	}

	/** @noinspection PhpDocMissingThrowsInspection */
	/**
	 * @param string $path
	 * @param string $method
	 * @param mixed[] $body
	 * @param string[] $headers
	 *
	 * @return mixed[]
	 */
	protected function request(string $path, string $method = 'GET', array $body = [], array $headers = []): array
	{
		$defaultHeaders = [
			'Content-Type' => 'application/json',
		];

		if (!empty($this->token)) {
			$defaultHeaders['Authorization'] = "Bearer {$this->token}";
		}

		/** @noinspection PhpUnhandledExceptionInspection */
		$response = $this->client->request($method, $path, [
			'headers' => $headers + $defaultHeaders,
			'json' => $body,
			'http_errors' => false,
		]);

		return [
			'headers' => $response->getHeaders(),
			'status' => $response->getStatusCode(),
			'body' => json_decode($response->getBody()->getContents(), true),
		];
	}

	/**
	 * @param string $table
	 * @param mixed[] $data
	 * @param string $idColumnName
	 * @throws Exception
	 */
	protected function insertDb(string $table, array $data, string $idColumnName = 'id'): int
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

	protected function queryDb(string $query): void
	{
		$this->pgQuery($query);
	}

	/**
	 * @param string $query
	 *
	 * @return mixed[]
	 */
	protected function fetchDb(string $query): array
	{
		$result = $this->pgQuery($query);

		return pg_fetch_all($result);
	}

	private function getRolePhrase(int $id): string
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

	private function eraseAllTables(): void
	{
		$truncateQuery = $this->fetchDb("
			SELECT 'TRUNCATE TABLE ' || string_agg(oid::regclass::text, ', ') || ' CASCADE' as \"query\"
			FROM pg_catalog.pg_class
			WHERE
				relkind = 'r'
				AND relnamespace = 'public'::regnamespace
		");

		$truncateQuery = $truncateQuery[0]['query'];

		if (empty($truncateQuery)) {
			return;
		}

		$this->queryDb($truncateQuery);
	}

	/**
	 * @param string $query
	 *
	 * @return bool|object|resource
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
