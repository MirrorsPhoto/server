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
		$user = Security::getUser();
		$allowedType = array_column($user->getTypes()->toArray(), 'name');
		$items = $this->getPost('items');

		if (empty($items)) {
			throw new BadRequest('sale.invalid_items');
		}

		foreach ($items as $index => $item) {
			if (!in_array($item->type, $allowedType)) {
				unset($items[$index]);
				continue;
			}

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

			if (empty($id) && $item->type === 'copy') {
				$id = 1;
			}

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

		if (!empty($items)) {
			$newCheck = new Check([
				'data' => json_encode($items),
			]);

			$newCheck->save();

			/** @var Department $department */
			$department = $user->getCurrentDepartments()->getLast();
			$department->notifyPersonnels();
		}

		return true;
	}

	/**
	 * @Get('/today')
	 *
	 * @return mixed[]
	 */
	public function getTodayAction(): array
	{
		/** @var User $user */
		$user = Security::getUser();
		/** @var Department $department */
		$department = $user->getCurrentDepartments()->getLast();
		$data = $department->getSummary($user);

		return $data;
	}

}
