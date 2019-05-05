<?php

use Phinx\Seed\AbstractSeed;

class Users extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function getDependencies(): array
	{
		return [
			'Roles',
		];
	}

	public function run(): void
	{
		$this
			->table('user')
			->insert([
				[
					'username' => 'admin',
					'first_name' => 'Админ',
					'last_name' => 'Админский',
					'role_id' => 2,
					'password' => '$2y$10$s6fBj2xEDJfwJikk2kRt8elkqfGX9YI/zEzpf9pxE2jiliXznSIWS',
					'email' => 'admin@mirrors-photo.ru',
				],
				[
					'username' => 'dimchenko_alina',
					'first_name' => 'Алина',
					'last_name' => 'Дымченко',
					'role_id' => 2,
					'password' => '$2y$10$hYV7js7YRrJ/56Kfk7LEN.UMbTG.WQb1wxt7gYYdXxiZIg7car3bG',
					'email' => 'dimchenko_alina@icloud.com',
				],
				[
					'username' => 'jonkofee',
					'first_name' => 'Jon',
					'last_name' => 'Kofee',
					'role_id' => 2,
					'password' => '$2y$10$e5A4kmEvLI1g8LQ/cw.BX.pTmQzyAzXoxsDYm8Un.XnIkdUA7HWZK',
					'email' => 'jonkofee@icloud.com',
				],
			])
			->save()
		;
	}

}
