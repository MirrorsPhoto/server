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
	 * @return float
	 * @throws BadRequest
	 */
	public function priceAction($id)
	{
		$row = Copy::findFirst($id);

		if (!$row) {
			throw new BadRequest('copy.not_found');
		}

		return $row->price;
	}
}
