<?php

use Behat\Gherkin\Node\TableNode;

class CopyContext extends AbstractContext
{
	/**
	 * @Given that there is a copies:
	 *
	 * @param TableNode $table
	 * @throws Exception
	 */
	public function create(TableNode $table)
	{
		$rows = $table->getColumnsHash();
		$userId = $this->data['user']['id'];

		foreach ($rows as $row) {
			$format = $row['format'];

			if (isset($this->data['copy']['formats'][$format])) {
				$id = $this->data['copy']['formats'][$format];
			} else {
				$id = $this->insertDb('copy', [
					'format' => $format
				]);

				$this->data['copy']['formats'][$format] = $id;
			}

			if (isset($row['price'])) {
				if (!isset($this->data['departments'][$row['department']])) {
					throw new InvalidArgumentException("department_id is not set");
				}

				$departmentId = $this->data['departments'][$row['department']];

				$this->insertDb('copy_price_history', [
					'department_id' => $departmentId,
					'user_id' => $userId,
					'copy_id' => $id,
					'price' => $row['price']
				]);
			}
		}
	}

	/**
	 * @When i want get copy price format :format
	 *
	 * @param string $format
	 * @throws Exception
	 */
	public function get(string $format)
	{
		$formatId = $this->data['copy']['formats'][$format] ?? 569;

		$response = $this->request("copy/price/$formatId");

		$this->data['response'] = $response;
	}
}
