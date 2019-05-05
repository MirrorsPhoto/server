<?php

use Behat\Gherkin\Node\TableNode;

class LaminationContext extends AbstractContext
{
	/**
	 * @param TableNode $table
	 * @throws Exception
	 *
	 * @Given that there is a sizes:
	 */
	public function create(TableNode $table): void
	{
		$rows = $table->getColumnsHash();
		$userId = $this->data['user']['id'];

		foreach ($rows as $row) {
			$format = $row['format'];

			if (isset($this->data['lamination']['formats'][$format])) {
				$id = $this->data['lamination']['formats'][$format];
			} else {
				$id = $this->insertDb('lamination', [
					'format' => $format
				]);

				$this->data['lamination']['formats'][$format] = $id;
			}

			if (isset($row['price'])) {
				if (!isset($this->data['departments'][$row['department']])) {
					throw new InvalidArgumentException("department_id is not set");
				}

				$departmentId = $this->data['departments'][$row['department']];

				$this->insertDb('lamination_price_history', [
					'department_id' => $departmentId,
					'user_id' => $userId,
					'lamination_id' => $id,
					'price' => $row['price']
				]);
			}
		}
	}

	/**
	 * @throws Exception
	 *
	 * @When i want get lamination sizes
	 */
	public function get(): void
	{
		$response = $this->request('lamination/size', 'GET');

		$this->data['response'] = $response;
	}
}
