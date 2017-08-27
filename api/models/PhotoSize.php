<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class PhotoSize extends Model
{

	protected $_tableName = 'photo_size';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $width;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $height;

	/**
	 * Validations and business logic
	 *
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'width',
			new PresenceOf(
				[
					'message' => 'Ширина обязательна'
				]
			)
		);

		$validator->add(
			'height',
			new PresenceOf(
				[
					'message' => 'Высота обязательна'
				]
			)
		);

		$validator->add(
			'width',
			new Numericality(
				[
					'message' => 'Ширина должна быть числовым значением',
				]
			)
		);

		$validator->add(
			'height',
			new Numericality(
				[
					'message' => 'Высота должна быть числовым значением',
				]
			)
		);

		return $this->validate($validator);
	}

}
