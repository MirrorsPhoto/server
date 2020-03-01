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

		/** @var Service $row */
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
			throw new ServerError('service.empty');
		}

		return $result;
	}

}
