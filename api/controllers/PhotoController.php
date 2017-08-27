<?php

/**
 * @RoutePrefix('/photo')
 */
class PhotoController extends Controller
{
	/**
	 * @Get('/size')
	 */
	public function getSizeAction()
	{
		return PhotoSize::find();
	}

	/**
	 * @Get('/price')
	 */
	public function getPriceAction()
	{
		(new \Validator\Photo\Price())->validate();

		$width = $this->getQuery('width');
		$height = $this->getQuery('height');
		$count = $this->getQuery('count');

		$rowSize = PhotoSize::findFirst([
			"width = $width AND height = $height"
		]);

		if (!$rowSize) throw new \Core\Exception\BadRequest('Фотографии с таким размером нет');

		$rowPrice = $rowSize->getPrice($count);

		if (!$rowPrice) throw new \Core\Exception\BadRequest('Для такого фото не задана цена', 422);

		return $rowPrice;
	}

}