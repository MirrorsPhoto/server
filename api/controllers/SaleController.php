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
					$manager = 'Photo';
					break;
				case 'good':
					$manager = 'Good';
					break;
				case 'copy':
					$manager = 'Copy';
					break;
				case 'lamination':
					$manager = 'Lamination';
					break;
        case 'service':
          $manager = 'Service';
          break;
				case 'printing':
					$manager = 'Printing';
					break;
				default:
					throw new \Core\Exception\BadRequest('Не известный тип услуги ' . $item->type);

			}

			$manager::batch($item);
		}

		$newCheck = new Check([
			'data' => json_encode($items)
		]);

		$newCheck->save();

		(\Core\UserCenter\Security::getUser())->getCurrentDepartments()->getLast()->notifyPersonnels();

		return true;
	}

}