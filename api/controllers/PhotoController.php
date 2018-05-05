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
		$rowSet = PhotoSize::find();

		$result = [];

		foreach ($rowSet as $row) {
			$array = $row->toArray([
			  'width',
        'height'
      ]);

			$array['width'] = (float)$array['width'];
			$array['height'] = (float)$array['height'];

			if (!$variations = $row->getVariations()) continue;

			$array['variations'] = $variations;

			$result[] = $array;
		}

		if (!$result) throw new \Core\Exception\ServerError('Нет цен для фото');
		
		return $result;
	}

}