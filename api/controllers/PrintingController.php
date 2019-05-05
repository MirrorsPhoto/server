<?php

/**
 * @RoutePrefix('/printing')
 */
class PrintingController extends Controller
{

	/**
	 * @Get('/')
	 *
	 * @return mixed[]
	 */
	public function getAction(): array
	{
		$rowSet = Printing::find();

		$result = [];

		/** @var Printing $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'id',
				'name',
				'color',
				'photo',
				'ext',
			]);

			$array['price'] = $row->price;

			$result[] = $array;
		}

		return $result;
	}

}
