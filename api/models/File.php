<?php

class File extends Model
{

	protected $_tableName = 'file';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $path;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_create;

	public static function factory(Phalcon\Http\Request\File $file)
    {
	    $fileName = hash_file('md5', $file->getTempName()) . ".{$file->getExtension()}";

	    $config = ConfigIni::getInstance();

	    $dir = $config->static->dir;

	    $path = "{$fileName[0]}{$fileName[1]}/{$fileName[2]}{$fileName[3]}";

	    //Проверка на наличие файла
	    if (file_exists("$dir/$path/$fileName")) {
		    $obj = self::findFirstByPath("$path/$fileName");

		    if (!$obj) {
			    $obj = new self();

			    $obj->path = "$path/$fileName";

			    $obj->save();
		    }

	    } else {
		    //Если нет такой папки - создать
		    if (!is_dir("$dir/$path")) mkdir("$dir/$path", 0777, true);

		    $isSave = $file->moveTo("$dir/$path/$fileName");

		    if (!$isSave) throw new \Core\Exception\ServerError('Не удалось записать файл');

		    $obj = new self();

		    $obj->path = "$path/$fileName";

		    $obj->save();
	    }

	    return $obj;
    }

	/**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->hasMany('id', 'User', 'avatar_id', ['alias' => 'User']);
    }

	/**
	 * Возвращает полный путь к файлу (http://static.jonkofee.ru/fox.png)
	 *
	 * @return string
	 */
	public function getFullPath()
    {
		$config = ConfigIni::getInstance();

	    $domain = $config->static->url;

		return "$domain/{$this->path}";
    }

}
