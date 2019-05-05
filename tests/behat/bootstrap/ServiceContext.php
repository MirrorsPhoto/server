<?php

use Behat\Gherkin\Node\TableNode;

class ServiceContext extends AbstractContext
{
	/**
	 * @When i want get service list
	 */
	public function get(): void
	{
		$response = $this->request('service');

		$this->data['response'] = $response;
	}

	/**
	 * @param TableNode $table
	 * @throws Exception
	 *
	 * @Given that there is a services:
	 */
	public function create(TableNode $table): void
	{
		$rows = $table->getColumnsHash();
		$userId = $this->data['user']['id'];

		foreach ($rows as $row) {
			$name = $row['name'];

			if (isset($this->data['service'][$name])) {
				$id = $this->data['service'][$name];
			} else {
				$id = $this->insertDb('service', [
					'name' => $name
				]);

				$this->data['service'][$name] = $id;
			}

			if (isset($row['price'])) {
				if (!isset($this->data['departments'][$row['department']])) {
					throw new InvalidArgumentException("department_id is not set");
				}

				$departmentId = $this->data['departments'][$row['department']];

				$this->insertDb('service_price_history', [
					'department_id' => $departmentId,
					'user_id' => $userId,
					'service_id' => $id,
					'price' => $row['price']
				]);
			}
		}
	}
}
