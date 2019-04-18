<?php

use Core\Exception\BadRequest;
use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;

/**
 * @RoutePrefix('/sale')
 */
class SaleController extends Controller
{

	/**
	 * @Post('/batch')
	 *
	 * @throws BadRequest
	 * @throws ServerError
	 * @throws Unauthorized
	 * @return bool
	 */
	public function batchAction() {
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
					throw new BadRequest('Не известный тип услуги ' . $item->type);
			}

			$manager::batch($item);
		}

		$newCheck = new Check([
			'data' => json_encode($items)
		]);

		$newCheck->save();

		(Security::getUser())->getCurrentDepartments()->getLast()->notifyPersonnels();

		return true;
	}

}