<?php


use Phinx\Migration\AbstractMigration;

class StartMigration extends AbstractMigration
{

    public function change()
    {
    		$this->_user();

        $this->_city();

        $this->_department();

        $this->_check();

        $this->_good();

        $this->_copy();

        $this->_lamination();

        $this->_photo();
    }

    private function _city()
    {
        $table = $this->table('city');

        $table
            ->addColumn('name', 'text', ['comment' => 'Название города'])
        ;

        $table
            ->addIndex(['name'])
        ;

        $table->create();

        $this->table('city')->insert([
            [
                'name' => 'Амвросиевка'
            ]
        ])->save();
    }

    private function _department()
    {
        $table = $this->table('department');

        $table
            ->addColumn('city_id', 'integer', ['comment' => 'Id города к которому относится фотосалон'])
            ->addColumn('name', 'text', ['comment' => 'Название салона'])
            ->addColumn('address', 'text', ['comment' => 'Адрес салона (улица, дом)'])
        ;

        $table
            ->addIndex(['name', 'address', 'city_id'])
        ;

        $table
            ->addForeignKey('city_id', 'city', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('department')->insert([
            [
                'city_id'   => 1,
                'name'      => 'Амвросиевка',
                'address'   => 'Фрунзе 16'
            ]
        ])->save();



        $table = $this->table('department_personnel_history');

        $table
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором работает данный сотрудник'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя'])
            ->addColumn('datetime_from', 'timestamp', ['comment' => 'Дата устройства', 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('datetime_to', 'timestamp', ['comment' => 'Дата увольнения', 'null' => true])
        ;

        $table
            ->addIndex(['department_id', 'user_id', 'datetime_from', 'datetime_to'])
        ;

        $table
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('department_personnel_history')->insert([
            [
                'department_id' => 1,
                'user_id'       => 1
            ],
            [
                'department_id' => 1,
                'user_id'       => 2
            ],
            [
                'department_id' => 1,
                'user_id'       => 3
            ]
        ])->save();
    }

    private function _user()
    {
        $table = $this->table('role');

        $table
            ->addColumn('name', 'text', ['comment' => 'Название роли'])
        ;

        $table
            ->addIndex(['name'], ['unique' => true])
        ;

        $table->create();

        $this->table('role')->insert([
            [
                'name' => 'Гость'
            ],
            [
                'name' => 'Администратор'
            ],
            [
                'name' => 'Оператор'
            ],
            [
                'name' => 'Пользователь'
            ]
        ])->save();



        $table = $this->table('file');

        $table
            ->addColumn('path', 'text', ['comment' => 'Путь к файлу (fox.png)'])
            ->addColumn('datetime_create', 'timestamp', ['comment' => 'Дата добавления файла', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['path'], ['unique' => true])
            ->addIndex(['datetime_create'])
        ;

        $table->create();



        $table = $this->table('user');

        $table
            ->addColumn('avatar_id', 'integer', ['comment' => 'Id файла с изображение', 'null' => true])
            ->addColumn('role_id', 'integer', ['comment' => 'Id роли пользователя', 'default' => Role::USER])
            ->addColumn('username', 'text', ['comment' => 'Логин пользователя'])
            ->addColumn('first_name', 'text', ['comment' => 'Имя пользователя'])
            ->addColumn('middle_name', 'text', ['comment' => 'Отчество пользователя', 'null' => true])
            ->addColumn('last_name', 'text', ['comment' => 'Фамилия пользователя'])
            ->addColumn('email', 'text', ['comment' => 'Email пользователя'])
            ->addColumn('password', 'text', ['comment' => 'Пароль пользователя'])
            ->addColumn('token', 'text', ['comment' => 'Ключ доступа', 'null' => true])
            ->addColumn('datetime_create', 'timestamp', ['comment' => 'Дата создания пользователя', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['username', 'email', 'token'], ['unique' => true])
            ->addIndex(['avatar_id', 'role_id', 'password', 'datetime_create'])
        ;

        $table
            ->addForeignKey('avatar_id', 'file', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('role_id', 'role', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('user')->insert([
            [
                'username'      => 'admin',
                'first_name'    => 'Админ',
                'last_name'     => 'Админский',
                'role_id'       => '2',
                'password'      => '$2y$10$s6fBj2xEDJfwJikk2kRt8elkqfGX9YI/zEzpf9pxE2jiliXznSIWS',
                'email'         => 'admin@mirrors-photo.ru'
            ],
            [
                'username'      => 'dimchenko_alina',
                'first_name'    => 'Алина',
                'last_name'     => 'Дымченко',
                'role_id'       => '2',
                'password'      => '$2y$10$hYV7js7YRrJ/56Kfk7LEN.UMbTG.WQb1wxt7gYYdXxiZIg7car3bG',
                'email'         => 'dimchenko_alina@icloud.com'
            ],
            [
                'username'      => 'jonkofee',
                'first_name'    => 'Jon',
                'last_name'     => 'Kofee',
                'role_id'       => '2',
                'password'      => '$2y$10$e5A4kmEvLI1g8LQ/cw.BX.pTmQzyAzXoxsDYm8Un.XnIkdUA7HWZK',
                'email'         => 'jonkofee@icloud.com'
            ]
        ])->save();
    }

    private function _good()
    {
        $table = $this->table('good');

        $table
            ->addColumn('name', 'text', ['comment' => 'Название товара'])
            ->addColumn('description', 'text', ['comment' => 'Описание товара', 'null' => true])
            ->addColumn('bar_code', 'text', ['comment' => 'Штрих-код товара'])
            ->addColumn('datetime_сreate', 'timestamp', ['comment' => 'Дата создания', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['bar_code', 'name'], ['unique' => true])
        ;

        $table->create();

        $this->table('good')->insert([
            [
                'name'          => 'Тестовый товар',
                'description'   => 'Тестовое описание',
                'bar_code'      => '1234567890123'
            ]
        ])->save();




        $table = $this->table('good_receipt');

        $table
            ->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в который привезли данный товар'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который принимал товар'])
            ->addColumn('price', 'float', ['comment' => 'Цена по который получали этот товар'])
            ->addColumn('datetime', 'timestamp', ['comment' => 'Дата получения', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['good_id', 'department_id', 'user_id', 'price', 'datetime'])
        ;

        $table
            ->addForeignKey('good_id', 'good', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('good_receipt')->insert([
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ],
            [
                'good_id'       => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'price'         => 420
            ]
        ])->save();




        $table = $this->table('good_sale');

        $table
            ->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором продали данный товар'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который продавал товар'])
            ->addColumn('datetime', 'timestamp', ['comment' => 'Дата продажи', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['good_id', 'department_id', 'user_id', 'datetime'])
        ;

        $table
            ->addForeignKey('good_id', 'good', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();




        $table = $this->table('good_price_history');

        $table
            ->addColumn('good_id', 'integer', ['comment' => 'Id товара'])
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
            ->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать'])
            ->addColumn('datetime_from', 'timestamp', ['comment' => 'С какой даты действительна цена', 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
        ;

        $table
            ->addIndex(['good_id', 'department_id', 'user_id', 'price', 'datetime_to'])
        ;

        $table
            ->addForeignKey('good_id', 'good', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('good_price_history')->insert([
            [
                'good_id' => 1,
                'department_id' => 1,
                'user_id' => 1,
                'price' => 500
            ]
        ])->save();
    }

    private function _check()
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
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();
    }

    private function _copy()
    {
        $table = $this->table('copy_price_history');

        $table
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
            ->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать'])
            ->addColumn('datetime_from', 'timestamp', ['comment' => 'С какой даты действительна цена', 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
        ;

        $table
            ->addIndex(['department_id', 'user_id', 'price', 'datetime_to'])
        ;

        $table
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('copy_price_history')->insert([
            [
                'department_id'         => 1,
                'user_id'               => 1,
                'price'                 => 3
            ]
        ])->save();




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
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();
    }

    private function _lamination()
    {
        $table = $this->table('lamination_size');

        $table
            ->addColumn('format', 'text', ['comment' => 'Название формата (А4)'])
            ->addColumn('datetime_create', 'timestamp', ['comment' => 'Дата создания', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['format', 'datetime_create'])
        ;

        $table->create();

        $this->table('lamination_size')->insert([
            [
                'format' => 'A4'
            ],
            [
                'format' => 'A5'
            ]
        ])->save();




        $table = $this->table('lamination_price_history');

        $table
            ->addColumn('lamination_size_id', 'integer', ['comment' => 'Id формата ламинации'])
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
            ->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать'])
            ->addColumn('datetime_from', 'timestamp', ['comment' => 'С какой даты действительна цена', 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
        ;

        $table
            ->addIndex(['lamination_size_id', 'department_id', 'user_id', 'price', 'datetime_to'])
        ;

        $table
            ->addForeignKey('lamination_size_id', 'lamination_size', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('lamination_price_history')->insert([
            [
                'lamination_size_id'    => 1,
                'department_id'         => 1,
                'user_id'               => 1,
                'price'                 => 30
            ],
            [
                'lamination_size_id'    => 2,
                'department_id'         => 1,
                'user_id'               => 1,
                'price'                 => 20
            ]
        ])->save();




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
            ->addForeignKey('lamination_size_id', 'lamination_size', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();
    }

    private function _photo()
    {
        $table = $this->table('photo_size');

        $table
            ->addColumn('width', 'float', ['comment' => 'Ширина фотографии'])
            ->addColumn('height', 'float', ['comment' => 'Высота фотографии'])
            ->addColumn('datetime_create', 'timestamp', ['comment' => 'Дата создания', 'default' => 'CURRENT_TIMESTAMP'])
        ;

        $table
            ->addIndex(['width', 'height', 'datetime_create'])
        ;

        $table->create();

        $this->table('photo_size')->insert([
            [
                'width'     => 2.5,
                'height'    => 3
            ],
            [
                'width'     => 3,
                'height'    => 4
            ],
            [
                'width'     => 3.6,
                'height'    => 4.6
            ],
            [
                'width'     => 4,
                'height'    => 6
            ],
            [
                'width'     => 5,
                'height'    => 5
            ],
            [
                'width'     => 9,
                'height'    => 12
            ],
            [
                'width'     => 10,
                'height'    => 15
            ]
        ])->save();




        $table = $this->table('photo_price_history');

        $table
            ->addColumn('photo_size_id', 'integer', ['comment' => 'Id размера фотографии'])
            ->addColumn('department_id', 'integer', ['comment' => 'Id салона в котором установлена эта цена'])
            ->addColumn('user_id', 'integer', ['comment' => 'Id пользователя который устанавливал цену'])
            ->addColumn('count', 'integer', ['comment' => 'Количество штук для такого размера'])
            ->addColumn('price', 'float', ['comment' => 'Цена по который будем продавать такую фотографию'])
            ->addColumn('datetime_from', 'timestamp', ['comment' => 'С какой даты действительна цена', 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('datetime_to', 'timestamp', ['comment' => 'По какую дату действительна цена', 'null' => true])
        ;

        $table
            ->addIndex(['photo_size_id', 'department_id', 'user_id', 'count', 'price', 'datetime_to'])
        ;

        $table
            ->addForeignKey('photo_size_id', 'photo_size', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();

        $this->table('photo_price_history')->insert([
            [
                'photo_size_id' => 1,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 4,
                'price'         => 100
            ],
            [
                'photo_size_id' => 2,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 4,
                'price'         => 100
            ],
            [
                'photo_size_id' => 2,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 6,
                'price'         => 140
            ],
            [
                'photo_size_id' => 3,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 2,
                'price'         => 55
            ],
            [
                'photo_size_id' => 3,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 4,
                'price'         => 110
            ],
            [
                'photo_size_id' => 4,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 2,
                'price'         => 100
            ],
            [
                'photo_size_id' => 5,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 2,
                'price'         => 110
            ],
            [
                'photo_size_id' => 6,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 1,
                'price'         => 110
            ],
            [
                'photo_size_id' => 7,
                'department_id' => 1,
                'user_id'       => 1,
                'count'         => 1,
                'price'         => 110
            ]
        ])->save();




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
            ->addForeignKey('photo_size_id', 'photo_size', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('department_id', 'department', 'id', ['delete'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'RESTRICT'])
        ;

        $table->create();
    }
}
