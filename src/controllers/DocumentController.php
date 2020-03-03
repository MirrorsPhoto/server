<?php

use Core\Exception\ServerError;

/**
 * @RoutePrefix('/document')
 */
class DocumentController extends Controller
{

	/**
	 * @Get('/')
	 *
	 * @throws ServerError
	 *
	 * @return mixed[]
	 */
	public function getAction(): array
	{
		$rowSet = Document::find();

		$result = [];

		/** @var Document $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'id',
				'name',
			]);

			$price = $row->price;
			if (empty($price)) {
				continue;
			}

			$array['price'] = $price;

			$result[] = $array;
		}

		if (!$result) {
			throw new ServerError('document.empty');
		}

		return $result;
	}

}
