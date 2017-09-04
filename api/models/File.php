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
