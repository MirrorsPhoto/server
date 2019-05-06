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
	 */
	public function batchAction(): bool
	{
		$items = $this->getPost('items');

		if (empty($items)) {
			throw new BadRequest('sale.invalid_items');
		}

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
					throw new BadRequest('sale.unknown_type');
			}

			$id = $item->id;
			$copies = $item->copies;

			if ($copies <= 0) {
				throw new BadRequest('sale.invalid_copies');
			}

			if ($id <= 0) {
				throw new BadRequest('sale.invalid_id');
			}

			$row = $manager::findFirst($id);

			if (!$row) {
				throw new BadRequest('sale.wrong_id');
			}

			for ($i = 1; $i <= $copies; $i++) {
				$row->sale();
			}
		}

		$newCheck = new Check([
			'data' => json_encode($items),
		]);

		$newCheck->save();

		(Security::getUser())->getCurrentDepartments()->getLast()->notifyPersonnels();

		return true;
	}

}
