<?php

/**
 * @RoutePrefix('/printing')
 */
class PrintingController extends Controller
{
	/**
	 * @Get('/')
	 */
	public function getAction()
	{
		$rowSet = Printing::find();

		$result = [];

		foreach ($rowSet as $row) {
			$array = $row->toArray([
			  'id',
        'name',
				'color',
				'photo',
				'ext'
      ]);

			$array['price'] = $row->price;

			$result[] = $array;
		}


		return $result;
	}

}