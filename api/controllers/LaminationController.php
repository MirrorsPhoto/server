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
	 * @return array
	 * @throws ServerError
	 */
	public function getSizeAction()
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
