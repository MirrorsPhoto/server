<?php

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
	 */
	public function priceAction($id)
	{
		return Copy::findFirst($id)->price;
	}
}
