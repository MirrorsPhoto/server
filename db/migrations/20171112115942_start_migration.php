<?php


use Phinx\Migration\AbstractMigration;

class StartMigration extends AbstractMigration
{

	public function change()
	{
		$this->user();

		$this->city();

		$this->department();

		$this->check();

		$this->good();

		$this->copy();

		$this->lamination();

		$this->photo();
	}

	private function city()
	{
		$table = $this->table('city');

		$table
			->addColumn('name', 'text', ['comment' => 'Название города'])
		;

		$table
			->addIndex(['name'])
		;

		$table->create();
	}

	private function department()
	{
		$table = $this->table('department');

		$table
			->addColumn('city_id', 'integer', ['comment' => 'Id города к которому относится фотосалон'])
			->addColumn('name', 'text', ['comment' => 'Название салона'])
			->addColumn('address', 'text', ['comment' => 'Адрес салона (улица, дом)'])
		;

		$table
			->addIndex([
				'name',
				'address',
				'city_id'
			])
		;

		$table
			->addForeignKey('city_id', 'city', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
		$table = $this->table('department_personnel_history');

		$table
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором работает данный сотрудник'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'Дата устройства',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addColumn('datetime_to', 'timestamp', [
				'comment' => 'Дата увольнения',
				'null' => true
			])
		;

		$table
			->addIndex([
				'department_id',
				'user_id',
				'datetime_from',
				'datetime_to'
			])
		;

		$table
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}

	private function user()
	{
		$table = $this->table('role');

		$table
			->addColumn('name', 'text', ['comment' => 'Название роли'])
		;

		$table
			->addIndex(['name'], ['unique' => true])
		;

		$table->create();

		$table = $this->table('file');

		$table
			->addColumn('path', 'text', ['comment' => 'Путь к файлу (fox.png)'])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата добавления файла',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex(['path'], ['unique' => true])
			->addIndex(['datetime_create'])
		;

		$table->create();

		$table = $this->table('user');

		$table
			->addColumn('avatar_id', 'integer', [
				'comment' => 'Id файла с изображение',
				'null' => true
			])
			->addColumn('role_id', 'integer', [
				'comment' => 'Id роли пользователя',
				'default' => Role::USER
			])
			->addColumn('username', 'text', ['comment' => 'Логин пользователя'])
			->addColumn('first_name', 'text', ['comment' => 'Имя пользователя'])
			->addColumn('middle_name', 'text', [
				'comment' => 'Отчество пользователя',
				'null' => true
			])
			->addColumn('last_name', 'text', ['comment' => 'Фамилия пользователя'])
			->addColumn('email', 'text', ['comment' => 'Email пользователя'])
			->addColumn('password', 'text', ['comment' => 'Пароль пользователя'])
			->addColumn('token', 'text', [
				'comment' => 'Ключ доступа',
				'null' => true
			])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата создания пользователя',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex(
				[
					'username',
					'email',
					'token'
				],
				['unique' => true]
			)
			->addIndex([
				'avatar_id',
				'role_id',
				'password',
				'datetime_create'
			])
		;

		$table
			->addForeignKey('avatar_id', 'file', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('role_id', 'role', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}

	private function good()
	{
		$table = $this->table('good');

		$table
			->addColumn('name', 'text', ['comment' => 'Название товара'])
			->addColumn('description', 'text', [
				'comment' => 'Описание товара',
				'null' => true
			])
			->addColumn('bar_code', 'text', ['comment' => 'Штрих-код товара'])
			->addColumn('datetime_сreate', 'timestamp', [
				'comment' => 'Дата создания',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex(
				[
					'bar_code',
					'name'
				],
				['unique' => true]
			)
		;

		$table->create();

		$table = $this->table('good_receipt');

		$table
			->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в который привезли данный товар'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который принимал товар'])
			->addColumn('price', 'float', ['comment' => 'Цена по который получали этот товар'])
			->addColumn('datetime', 'timestamp', [
				'comment' => 'Дата получения',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex([
				'good_id',
				'department_id',
				'user_id',
				'price',
				'datetime'
			])
		;

		$table
			->addForeignKey('good_id', 'good', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();

		$table = $this->table('good_sale');

		$table
			->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором продали данный товар'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который продавал товар'])
			->addColumn('datetime', 'timestamp', [
				'comment' => 'Дата продажи',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex([
				'good_id',
				'department_id',
				'user_id',
				'datetime'
			])
		;

		$table
			->addForeignKey('good_id', 'good', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();

		$table = $this->table('good_price_history');

		$table
			->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
			->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'С какой даты действительна цена',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
		;

		$table
			->addIndex(['good_id', 'department_id', 'user_id', 'price', 'datetime_to'])
		;

		$table
			->addForeignKey('good_id', 'good', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}

	private function check()
	{
		$table = $this->table('check');

		$table
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором проходил этот чек'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который пробивал этот чек'])
			->addColumn('data', 'text', ['comment' => 'JSON'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата устройства', 'default' => 'CURRENT_TIMESTAMP'])
		;

		$table
			->addIndex(['department_id', 'user_id', 'datetime'])
		;

		$table
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}

	private function copy()
	{
		$table = $this->table('copy_price_history');

		$table
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
			->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'С какой даты действительна цена',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
		;

		$table
			->addIndex(['department_id', 'user_id', 'price', 'datetime_to'])
		;

		$table
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();

		$table = $this->table('copy_sale');

		$table
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором сделали ксерокопию'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который сделал ксерокопию'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
		;

		$table
			->addIndex(['department_id', 'user_id', 'datetime'])
		;

		$table
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}

	private function lamination()
	{
		$table = $this->table('lamination_size');

		$table
			->addColumn('format', 'text', ['comment' => 'Название формата (А4)'])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата создания',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex(['format', 'datetime_create'])
		;

		$table->create();

		$table = $this->table('lamination_price_history');

		$table
			->addColumn('lamination_size_id', 'integer', ['comment' => 'Id формата ламинации'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
			->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'С какой даты действительна цена',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addColumn('datetime_to', 'timestamp', [
				'comment' => 'По какую дату действительна цена',
				'null' => true
			])
		;

		$table
			->addIndex(['lamination_size_id', 'department_id', 'user_id', 'price', 'datetime_to'])
		;

		$table
			->addForeignKey('lamination_size_id', 'lamination_size', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();

		$table = $this->table('lamination_sale');

		$table
			->addColumn('lamination_size_id', 'integer', ['comment' => 'Id формата ламинации'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором делали эту ламинацию'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который делал ламинацию'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
		;

		$table
			->addIndex(['lamination_size_id', 'department_id', 'user_id', 'datetime'])
		;

		$table
			->addForeignKey('lamination_size_id', 'lamination_size', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}

	private function photo()
	{
		$table = $this->table('photo_size');

		$table
			->addColumn('width', 'float', ['comment' => 'Ширина фотографии'])
			->addColumn('height', 'float', ['comment' => 'Высота фотографии'])
			->addColumn('datetime_create', 'timestamp', [
				'comment' => 'Дата создания',
				'default' => 'CURRENT_TIMESTAMP'
			])
		;

		$table
			->addIndex(['width', 'height', 'datetime_create'])
		;

		$table->create();

		$table = $this->table('photo_price_history');

		$table
			->addColumn('photo_size_id', 'integer', ['comment' => 'Id размера фотографии'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук для такого размера'])
			->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать такую фотографию'])
			->addColumn('datetime_from', 'timestamp', [
				'comment' => 'С какой даты действительна цена',
				'default' => 'CURRENT_TIMESTAMP'
			])
			->addColumn('datetime_to', 'timestamp', [
				'comment' => 'По какую дату действительна цена',
				'null' => true
			])
		;

		$table
			->addIndex(['photo_size_id', 'department_id', 'user_id', 'count', 'price', 'datetime_to'])
		;

		$table
			->addForeignKey('photo_size_id', 'photo_size', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();

		$table = $this->table('photo_sale');

		$table
			->addColumn('photo_size_id', 'integer', ['comment' => 'Id размера фотографии'])
			->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором делали эту фотографию'])
			->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который делал фотографию'])
			->addColumn('count', 'integer', ['comment' => 'Количество штук'])
			->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
		;

		$table
			->addIndex(['photo_size_id', 'department_id', 'user_id', 'count', 'datetime'])
		;

		$table
			->addForeignKey('photo_size_id', 'photo_size', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('department_id', 'department', 'id', ['delete' => 'RESTRICT'])
			->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT'])
		;

		$table->create();
	}
}
