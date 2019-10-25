<?php

namespace FL;

class Response
{
	public $content;
	public $code;
	public $headers = [];

	function __construct($content = '', $code = 200)
	{
		$this->content = $content;
		$this->code = $code;
	}

	public function setHeaders(array $headers = [])
	{
		$this->headers = $headers;
		return $this;
	}

	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
		return $this;
	}

	public function sendHeaders()
	{
		if (headers_sent()) {
			return;
		}

		header('X-Powered-By: Favorite Links', true, $this->code);

		foreach($this->headers as $name => $header){
			if ($name === 'Set-Cookie') {
				$name = $name . strstr($name, '=');
			}
			$replace = 0 === strcasecmp($name, 'Content-Type');

			header($name.': '.$header, $replace, $this->code);
		}

		header(sprintf('HTTP/%s %s', 1.0, $this->code), true, $this->statusCode);
	}

	public function setContent($content = '')
	{
		$this->content = $content;
	}

	public function send()
	{
		$this->sendHeaders();
		echo $this->content;

		if (\function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
		return;
	}
}
