<?php

use Core\Exception\ServerError;

/**
 * @property string fullPath
 * @method static self findFirstByPath(string $string)
 */
class File extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'file';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $path;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	/**
	 * @param \Phalcon\Http\Request\File $file
	 * @throws ServerError
	 */
	public static function factory(Phalcon\Http\Request\File $file): self
	{
		$fileName = hash_file('md5', $file->getTempName()) . ".{$file->getExtension()}";

		$path = "static/{$fileName[0]}{$fileName[1]}/{$fileName[2]}{$fileName[3]}";

		//Проверка на наличие файла
		if (file_exists("$path/$fileName")) {
			$obj = self::findFirstByPath("$path/$fileName");

			if (!$obj) {
				$obj = new self();

				$obj->path = "$path/$fileName";

				$obj->save();
			}
		} else {
			//Если нет такой папки - создать
			if (!is_dir("$path")) {
				mkdir("$path", 0777, true);
			}

			$isSave = $file->moveTo("$path/$fileName");

			if (!$isSave) {
				throw new ServerError('Не удалось записать файл');
			}

			$obj = new self();

			$obj->path = "$path/$fileName";

			$obj->save();
		}

		return $obj;
	}

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'User', 'avatar_id', ['alias' => 'Users']);
	}

	/**
	 * Возвращает полный путь к файлу (http://static.jonkofee.ru/fox.png)
	 */
	public function getFullPath(): string
	{
		$domain = 'http://static.' . $_ENV['DOMAIN'];

		return "$domain/{$this->path}";
	}

}
