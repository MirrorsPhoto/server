<?php

/**
 * @RoutePrefix('/photo')
 */
class PhotoController extends Controller
{
	/**
	 * @Get('/size')
	 */
	public function getSizeAction()
	{
		return PhotoSize::find();
	}

}