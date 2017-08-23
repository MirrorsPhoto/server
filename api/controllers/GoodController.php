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

		$barCode = $login = $this->getPost('bar_code');

		$good = Good::findFirstByBarCode($barCode);

		if (!$good) throw new \Core\Exception\BadRequest('Товар с таким кодом отсутствует');

		$good->sale();

		return 'Товар успешно продан';

	}

}