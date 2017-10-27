<?php

/**
 * @RoutePrefix('/sale')
 */
class SaleController extends Controller
{

	/**
	 * @Post('/batch')
	 */
	public function batchAction()
	{
		$items = $this->getPost('items');

		foreach ($items as $item) {
			switch ($item->type) {
				case 'photo':
					$manager = 'PhotoSize';
					break;
				case 'good':
					$manager = 'Good';
					break;
				case 'copy':
					$manager = 'CopySale';
					break;
				case 'lamination':
					$manager = 'LaminationSize';
					break;
				default:
					throw new \Core\Exception\BadRequest('Не известный тип услуги ' . $item->type);

			}

			$manager::batch($item);
		}

		return true;
	}

}