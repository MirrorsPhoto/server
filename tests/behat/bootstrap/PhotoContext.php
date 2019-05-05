<?php

use Behat\Gherkin\Node\TableNode;

class PhotoContext extends AbstractContext
{
	/**
	 * @Given that there is a sizes:
	 *
	 * @param TableNode $table
	 * @throws Exception
	 */
	public function create(TableNode $table)
	{
		if (!isset($this->data['user']['id'])) {
			throw new InvalidArgumentException("user_id is not set");
		}

		$photos = $table->getColumnsHash();
		$userId = $this->data['user']['id'];
		$temp = [];

		foreach ($photos as $photo) {
			if (!isset($this->data['departments'][$photo['department']])) {
				throw new InvalidArgumentException("department_id is not set");
			}

			$photoSizeKey = $photo['width'] . 'x' . $photo['height'];
			$departmentId = $this->data['departments'][$photo['department']];

			if (!isset($temp[$photoSizeKey])) {
				$photoSizeId = $this->insertDb('photo_size', [
					'width' => $photo['width'],
					'height' => $photo['height']
				]);

				$temp[$photoSizeKey] = $photoSizeId;
			} else {
				$photoSizeId = $temp[$photoSizeKey];
			}

			$photoId = $this->insertDb('photo', [
				'photo_size_id' => $photoSizeId,
				'count' => $photo['count']
			]);

			$this->insertDb('photo_price_history', [
				'photo_id' => $photoId,
				'department_id' => $departmentId,
				'user_id' => $userId,
				'price' => $photo['price']
			]);
		}

		$this->data['photos'] = $photos;
	}

	/**
	 * @When i want get photo sizes
	 */
	public function get()
	{
		$response = $this->request('photo/size', 'GET');

		$this->data['response'] = $response;
	}
}
