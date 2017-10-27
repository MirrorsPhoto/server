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
		return CopyPriceHistory::getPrice();
	}


}