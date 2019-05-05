<?php

use Core\Exception\ServerError;

/**
 * @RoutePrefix('/lamination')
 */
class LaminationController extends Controller
{

	/**
	 * @Get('/size')
	 *
	 * @throws ServerError
	 *
	 * @return mixed[]
	 */
	public function getSizeAction(): array
	{
		$rowSet = Lamination::find();

		$result = [];

		/** @var Lamination $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'id',
				'format'
			]);

			$price = $row->price;
			if (empty($price)) {
				continue;
			}

			$array['price'] = $price;

			$result[] = $array;
		}

		if (!$result) {
			throw new ServerError('lamination.no_sizes');
		}

		return $result;
	}
}
