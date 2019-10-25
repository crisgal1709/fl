<?php

namespace FL;

class NotFoundResponse extends Response
{
	function __construct($view = '404', $code = 404)
	{
		$content = view($view)->render();
		parent::content($content, 404);
	}

	public static function ajax($content = [], $code = 404)
	{
		//
	}
}
