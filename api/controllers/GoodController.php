<?php

/**
 * @RoutePrefix('/good')
 */
class GoodController extends Controller
{
	/**
	 * @Post('/add')
	 */
	public function addAction()
	{
		(new \Validator\Good\Add())->validate();

		$name = $this->getPost('name');
		$description = $this->getPost('description');
		$bar_code = $this->getPost('bar_code');

		$newGood = new Good([
			'name' => $name,
			'description' => $description,
			'bar_code' => $bar_code
		]);

		$newGood->save();

		return 'Товар успешно добавлен';
	}


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