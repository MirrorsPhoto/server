<?php

use Phinx\Seed\AbstractSeed;

class TestUser extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function getDependencies(): array
	{
		return [
			'City',
			'Roles',
		];
	}

	public function run(): void
	{
		$testDepartmentName = 'ТЕСТ';
		$testUsername = 'test';

		$this
			->table('user')
			->insert([
				'id' => $this->fetchRow("SELECT * FROM \"user\" ORDER BY id DESC LIMIT 1")['id'] + 1,
				'username' => $testUsername,
				'first_name' => 'Jon',
				'last_name' => 'Kofee',
				'role_id' => 1,
				'password' => '$2y$10$s6fBj2xEDJfwJikk2kRt8elkqfGX9YI/zEzpf9pxE2jiliXznSIWS',
				'email' => 'info@' . $_ENV['DOMAIN'],
			])
			->saveData()
		;

		$this
			->table('department')
			->insert([
				'id' => $this->fetchRow("SELECT * FROM department ORDER BY id DESC LIMIT 1")['id'] + 1,
				'city_id' => 1,
				'name' => $testDepartmentName,
				'address' => $testDepartmentName,
				'is_test' => true,
			])
			->saveData()
		;

		$this
			->table('department_personnel_history')
			->insert([
				'department_id' => $this->fetchRow("SELECT * FROM department WHERE name = '$testDepartmentName'")['id'],
				'user_id' => $this->fetchRow("SELECT * FROM \"user\" WHERE username = '$testUsername'")['id'],
			])
			->saveData()
		;
	}

}
