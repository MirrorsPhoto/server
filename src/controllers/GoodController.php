<?php

use Core\Exception\BadRequest;
use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
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
	 * @Get('/')
	 *
	 * @return mixed[]
	 */
	public function getAction(): array
	{
		$rows = Good::find('is_delete = false');
		$result = [];

		/** @var Good $row */
		foreach ($rows as $row) {
			$data = $row->toArray([
				'id',
				'name',
				'description',
				'bar_code',
			]);

			$data['price'] = $row->price;
			$data['available'] = $row->getAvaibleCount();

			$result[] = $data;
		}

		return $result;
	}

	/**
	 * @Post('/')
	 *
	 * @throws ServerError
	 *
	 * @return mixed[]
	 */
	public function addAction(): array
	{
		$validator = new Add();
		$validator->validate();

		$user = \Core\UserCenter\Security::getUser();
		$department = $user->getCurrentDepartments()->getLast();

		$name = $this->getPost('name');
		$description = $this->getPost('description') ?: null;
		$bar_code = $this->getPost('bar_code') ?: null;
		$price = $this->getPost('price');

		$newGood = new Good([
			'name' => $name,
			'description' => $description,
			'bar_code' => $bar_code,
		]);

		$newGood->save();

		$newGood->refresh();

		$newGood->updatePrice($price);

		$data = $newGood->toArray([
			'id',
			'name',
			'description',
		]);

		$data['price'] = $newGood->price;
		$data['available'] = $newGood->getAvaibleCount();

		return [$data];
	}

	/**
	 * @Get('/search[a-zA-Z0-9\-\?=?\&\%]+')
	 *
	 * @throws BadRequest
	 */
	public function searchAction(): Simple
	{
		$validator = new Search();
		$validator->validate();

		$query = $this->getQuery('query');

		$goods = Good::find("LOWER(name) LIKE LOWER('%$query%') AND is_delete = false");

		if (!$goods->count()) {
			throw new BadRequest('Ничего не найдено');
		}

		return $goods;
	}

	/**
	 * @Get('/bar-code/{barCode:[0-9]+}')
	 *
	 * @param int $barCode
	 * @throws Unauthorized
	 * @throws BadRequest
	 *
	 * @return mixed[]
	 */
	public function getInfoByBarCodeAction(int $barCode): array
	{
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
	 *
	 * @return mixed[]
	 */
	public function getInfoByIdAction(int $id): array
	{
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
	 * @Post('/{id:[0-9]+}')
	 *
	 * @param int $id
	 * @throws BadRequest
	 *
	 * @return mixed[]
	 */
	public function getEditAction(int $id): array
	{
		/** @var Good $good */
		$good = Good::findFirst($id);

		if (!$good) {
			throw new BadRequest('Такой товар отсутствует');
		}

		$validator = new Add();
		$validator->validate();

		$name = $this->getPost('name');
		$description = $this->getPost('description') ?: null;
		$bar_code = $this->getPost('bar_code') ?: null;
		$price = $this->getPost('price');

		$good->name = $name;
		$good->description = $description;
		$good->bar_code = $bar_code;

		$good->updatePrice($price);

		$good->save();

		$arrGood = $good->toArray();

		$arrGood['price'] = $good->price;
		$arrGood['available'] = $good->getAvaibleCount();

		return [$arrGood];
	}

	/**
	 * @Post('/receipt')
	 *
	 * @throws ServerError
	 * @throws BadRequest
	 */
	public function receiptAction(): string
	{
		$validator = new Receipt();
		$validator->validate();

		$barCode = (int) $this->getPost('bar_code');
		$price = (float) $this->getPost('price');

		$good = Good::findFirstByBarCode($barCode);

		if (!$good) {
			throw new BadRequest('Товар с таким кодом отсутствует');
		}

		$good->receipt($price);

		return 'good.receipt.success';
	}

	/**
	 * @Get('/{id:[0-9]+}/delete')
	 *
	 * @param int $id
	 * @throws BadRequest
	 */
	public function deleteAction(int $id): bool
	{
		/** @var Good $good */
		$good = Good::findFirst($id);

		if (!$good) {
			throw new BadRequest('Такой товар отсутствует');
		}

		$good->is_delete = true;

		$good->save();

		return true;
	}

}
