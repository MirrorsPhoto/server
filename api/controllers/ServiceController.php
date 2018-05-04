<?php

/**
 * @RoutePrefix('/service')
 */
class ServiceController extends Controller
{
	/**
	 * @Get('/')
	 */
	public function getSizeAction()
	{
		$rowSet = Service::find();

		$result = [];

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