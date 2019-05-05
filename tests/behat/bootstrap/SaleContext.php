<?php

use Behat\Gherkin\Node\TableNode;

class SaleContext extends AbstractContext
{

	/**
	 * @When i want sale:
	 *
	 * @param TableNode $table
	 * @throws Exception
	 */
	public function create(TableNode $table): void
	{
		$rows = $table->getColumnsHash();

		$response = $this->request('/sale/batch', 'POST', ['items' => $rows]);

		$this->data['response'] = $response;
	}

}
