<?php

use Core\Exception\BadRequest;

/**
 * @RoutePrefix('/copy')
 */
class CopyController extends Controller
{

	/**
	 * @Get('/price/{id:[0-9]+}')
	 *
	 * @param int $id
	 * @throws BadRequest
	 */
	public function priceAction(int $id): float
	{
		$row = Copy::findFirst($id);

		if (!$row) {
			throw new BadRequest('copy.not_found');
		}

		return $row->price;
	}
}
