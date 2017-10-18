<?php

/**
 * @RoutePrefix('/copy')
 */
class CopyController extends Controller
{
	/**
	 * @Get('/price')
	 */
	public function priceAction()
	{
		$row = CopyPriceHistory::findFirst('datetime_to IS NULL');

		if (!$row) throw new \Core\Exception\ServerError('Не установлена цена на ксерокопию');

		return $row->price;
	}


}