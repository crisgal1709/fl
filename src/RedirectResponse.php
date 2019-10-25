<?php

namespace FL;

class RedirectResponse extends Response
{
	public function __construct(?string $url, int $code = 302, array $headers = [])
    {
        parent::__construct('', $code);
        $this->setTargetUrl($url);
    }

    public function setTargetUrl($url)
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        if (false !== $pos = strpos('#', $url)) {
            $url = substr($urs, 0, $pos);
        }

        $this->targetUrl = $url;

        $this->setContent(
            sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=%1$s" />
    </head>
    <body>
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')));

        //$this->setHeaders([]);

        $this->setHeader('Location', $url);

        return $this;
    }
}
