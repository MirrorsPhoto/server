<?php

/**
 * @RoutePrefix('/service')
 */
class ServiceController extends Controller
{

	/**
	 * @Get('/')
	 *
	 * @return array
	 */
	public function getSizeAction() {
		$rowSet = Service::find();

		$result = [];

		/** @var Service $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'id',
				'name'
			]);

			$array['price'] = $row->price;

			$result[] = $array;
		}

		return $result;
	}

}