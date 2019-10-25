<?php

namespace FL\Libs\Mail;

use FL\Application;
use Swift_SmtpTransport;

class Mailer {

	private $config;
	protected $app;

	public function __construct($config, Application $app)
	{
		$this->config = $config;
		$this->app = $app;
	}

	public function message()
	{
		return (new Message($this->setSwift()))
				->from(
					$this->config['from']['address'],
					$this->config['from']['name']
				);
	}

	public function setSwift()
	{
		$config = $this->config;

		$transport = (new Swift_SmtpTransport($config['host'], $config['port']))
		->setUsername($config['username'])
		->setPassword($config['password']);
		//dd($transport);

		return $transport;
	}
}
