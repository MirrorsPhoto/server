<?php

/**
 * @RoutePrefix('/good')
 */
class GoodController extends Controller
{

	/**
	 * @Post('/sale')
	 */
	public function saleAction()
	{
		(new \Validator\Good\Sale())->validate();

		$barCode = $this->getPost('bar_code');

		$good = Good::findFirstByBarCode($barCode);

		if (!$good) throw new \Core\Exception\BadRequest('Товар с таким кодом отсутствует');

		if (!$good->isAvailable()) throw new \Core\Exception\BadRequest('Такого товара нет в наличии');

		$good->sale();

		return 'Товар успешно продан';

	}

}