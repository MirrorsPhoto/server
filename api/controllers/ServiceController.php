<?php

use Core\Exception\ServerError;

/**
 * @RoutePrefix('/service')
 */
class ServiceController extends Controller
{

	/**
	 * @Get('/')
	 *
	 * @return array
	 * @throws ServerError
	 */
	public function getSizeAction()
	{
		$rowSet = Service::find();

		$result = [];

		/** @var Service $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'id',
				'name'
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
