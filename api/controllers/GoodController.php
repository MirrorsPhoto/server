<?php

use Core\Exception\BadRequest;
use Core\Exception\ServerError;
use Phalcon\Mvc\Model\Resultset\Simple;
use Validator\Good\Add;
use Validator\Good\Receipt;
use Validator\Good\Search;

/**
 * @RoutePrefix('/good')
 */
class GoodController extends Controller
{

	/**
	 * @Post('/add')
	 *
	 * @throws ServerError
	 * @return void
	 */
	public function addAction() {
		$validator = new Add();
		$validator->validate();

		$name = $this->getPost('name');
		$description = $this->getPost('description');
		$bar_code = $this->getPost('bar_code');

		$newGood = new Good([
			'name' => $name,
			'description' => $description,
			'bar_code' => $bar_code
		]);

		$newGood->save();

		return $newGood->refresh();
	}

	/**
	 * @Get('/search')
	 *
	 * @throws BadRequest
	 * @return Simple
	 */
	public function searchAction() {
		$validator = new Search();
		$validator->validate();

		$query = $this->getQuery('query');

		$goods = Good::find("LOWER(name) LIKE LOWER('%$query%')");

		if (!$goods->count()) {
			throw new BadRequest('Ничего не найдено');
		}

		return $goods;
	}

	/**
	 * @Get('/bar-code/{barCode:[0-9]+}')
	 *
	 * @param int $barCode
	 * @throws BadRequest
	 * @return array
	 */
	public function getInfoByBarCodeAction($barCode) {
		$good = Good::findFirstByBarCode($barCode);
		if (!$good) {
			throw new BadRequest('Такой товар отсутствует');
		}

		$arrGood = $good->toArray();

		$arrGood['price'] = $good->price;
		$arrGood['available'] = $good->getAvaibleCount();

		return $arrGood;
	}

	/**
	 * @Get('/{id:[0-9]+}')
	 *
	 * @param int $id
	 * @throws BadRequest
	 * @return array
	 */
	public function getInfoByIdAction($id) {
		$good = Good::findFirst($id);

		if (!$good) {
			throw new BadRequest('Такой товар отсутствует');
		}

		$arrGood = $good->toArray();

		$arrGood['price'] = $good->price;
		$arrGood['available'] = $good->getAvaibleCount();

		return $arrGood;
	}

	/**
	 * @Post('/receipt')
	 *
	 * @throws BadRequest
	 * @return string
	 */
	public function receiptAction() {
		$validator = new Receipt();
		$validator->validate();

		$barCode = (int)$this->getPost('bar_code');
		$price = (float)$this->getPost('price');

		$good = Good::findFirstByBarCode($barCode);

		if (!$good) {
			throw new BadRequest('Товар с таким кодом отсутствует');
		}

		$good->receipt($price);

		return 'good.receipt.success';
	}

}