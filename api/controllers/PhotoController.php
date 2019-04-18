<?php

use Core\Exception\ServerError;

/**
 * @RoutePrefix('/photo')
 */
class PhotoController extends Controller
{

	/**
	 * @Get('/size')
	 *
	 * @throws ServerError
	 * @return array
	 */
	public function getSizeAction()
	{
		$rowSet = PhotoSize::find();

		$result = [];

		/** @var PhotoSize $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'width',
				'height'
			]);

			$array['width'] = (float) $array['width'];
			$array['height'] = (float) $array['height'];

			$variations = $row->getVariations();
			if (!$variations) {
				continue;
			}

			$array['variations'] = $variations;

			$result[] = $array;
		}

		if (!$result) {
			throw new ServerError('Нет цен для фото');
		}

		return $result;
	}

}