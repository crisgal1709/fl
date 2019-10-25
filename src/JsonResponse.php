<?php

namespace FL;

class JsonResponse extends Response
{

	function __construct($content = '', $code = 200)
	{
		$content = $this->parseContent($content);
		parent::__construct($content, $code);
		$this->setHeader('Content-type', 'text/json');
	}

	private function parseContent($content)
	{
		return json_encode($content, JSON_UNESCAPED_UNICODE);
	}
}
