<?php

namespace FL\Libs\Mail;

use FL\Contracts\Renderable;
use Swift_Mailer;

class Message {

	public $from;
	public $to = [];
	public $cc = [];
	public $bcc = [];
	public $replyTo = [];
	public $subject;
	protected $content;
	public $view;

	protected $swift;
	protected $message;

	public $failedRecipients = [];

	public function __construct($swift)
    {
        $this->swift = $swift;
        $mailer = new Swift_Mailer($this->swift);
        $message = $mailer->createMessage('message');

        $this->message = $message;
    }

    public function from($address, $name = null)
	{
		if (is_array($address)) {
			$this->message->setFrom($address['address'], $address['name']);
		}

		$this->message->setFrom($address, $name);
		return $this;
	}

	public function to($address, $name = null)
	{
		if (is_array($address)) {
			$this->message->setTo($address['email'], $address['name']);
		}

		$this->message->setTo($address, $name);
		return $this;
	}

	public function subject(string $subject)
	{
		$this->message->setSubject($subject);
		return $this;
	}

	public function priority($level)
    {
        $this->message->setPriority($level);

        return $this;
    }

    public function content($content)
    {
    	if ($content instanceof Renderable) {
    		$content = $content->render();
    	}

    	$this->content = $content;
    	$this->message->setBody($content, 'text/html');
    	return $this;
    }

    public function send()
    {
    	$m = $this->getMessage();
    	$s = $this->swift->send($m, $this->failedRecipients);

    	return $this->failedRecipients;
    }

    public function getMessage()
    {
    	return $this->message;
    }
}
