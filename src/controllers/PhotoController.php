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
	 *
	 * @return mixed[]
	 */
	public function getSizeAction(): array
	{
		$rowSet = PhotoSize::find();

		$result = [];

		/** @var PhotoSize $row */
		foreach ($rowSet as $row) {
			$array = $row->toArray([
				'width',
				'height',
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
			throw new ServerError('photo.no_sizes');
		}

		return $result;
	}

}
