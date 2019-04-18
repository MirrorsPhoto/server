<?php

/**
 * @RoutePrefix('/lamination')
 */
class LaminationController extends Controller
{

	/**
	 * @Get('/size')
	 *
	 * @return array
	 */
	public function getSizeAction()
	{
		$rowSet = Lamination::find();

		$result = [];

		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'id',
				'format'
			]);

			$array['price'] = $row->price;

			$result[] = $array;
		}

		return $result;
	}

}