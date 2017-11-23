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

		return $newGood;
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

	/**
	 * @Get('/search')
	 */
	public function searchAction()
	{
        (new \Validator\Good\Search())->validate();

        $query = $this->getQuery('query');

		$goods = Good::find("LOWER(name) LIKE LOWER('%$query%')");

		if (!$goods->count()) throw new \Core\Exception\BadRequest('Ничего не найдено');

		return $goods;
	}

    /**
     * @Get('/bar-code/{barCode:[0-9]+}')
     */
    public function getInfoByBarCodeAction($barCode)
    {
        $good = Good::findFirstByBarCode($barCode);
        if (!$good) throw new \Core\Exception\BadRequest('Такой товар отсутствует');

        $arrGood = $good->toArray();

        $arrGood['price'] = $good->price;
        $arrGood['available'] = $good->getAvaibleCount();

        return $arrGood;
    }

    /**
     * @Get('/{id:[0-9]+}')
     */
    public function getInfoByIdAction($id)
    {
        $good = Good::findFirst($id);

        if (!$good) throw new \Core\Exception\BadRequest('Такой товар отсутствует');

        $arrGood = $good->toArray();

        $arrGood['price'] = $good->price;
        $arrGood['available'] = $good->getAvaibleCount();

        return $arrGood;
    }

	/**
	 * @Post('/receipt')
	 */
	public function receiptAction()
	{
		(new \Validator\Good\Receipt())->validate();

		$barCode = $this->getPost('bar_code');
		$price = $this->getPost('price');

		$good = Good::findFirstByBarCode($barCode);

		if (!$good) throw new \Core\Exception\BadRequest('Товар с таким кодом отсутствует');

		$good->receipt($price);

		return 'Товар успешно добавлен в наличие';
	}

}