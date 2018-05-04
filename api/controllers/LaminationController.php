<?php

/**
 * @RoutePrefix('/lamination')
 */
class LaminationController extends Controller
{
	/**
	 * @Get('/size')
	 */
	public function getSizeAction()
	{
		$rowSet = Lamination::find();

		$result = [];

		foreach ($rowSet as $row) {
			$array = $row->toArray();

			$array['price'] = $row->price;

			$result[] = $array;
		}


		return $result;
	}

}