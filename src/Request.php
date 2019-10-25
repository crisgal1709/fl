<?php

namespace FL;

use Exception;

class Request
{
	protected $app;
	protected $uri;

	public $server;
	public $content;
	public $get;
	public $post;
	public $files;
	public $cookie;
	public $Httpmethod;
    public $userResolver;

    public $url;

    protected $excludeAuth = [
        '/login',
        '/login/post',
    ];

	function __construct($app)
	{
		$this->app = $app;
		$this->server = $_SERVER;
		$this->get = $_GET;
		$this->post = $_POST;
		$this->files;
		$this->cookie = $_COOKIE;
        $this->getMethod();
        $this->getContent(false);

		$this->setFiles();
        $this->prepare();
	}

    private function setFiles()
    {
        if (is_array($_FILES) && count($_FILES) > 0) {
            $files = fixPhpFilesArray($_FILES);
            $_return = [];

            foreach($files as $key => $file){
                $_data = [];
                if (!is_array($file['name'])) {
                    $_d = $file;
                    $_data[] = $_d;
                } else {

                    for ($i=0; $i < count($file['name']) ; $i++) {

                        $_d = [
                            'error' => isset($file['error'][$i])
                            ? $file['error'][$i]
                            : NULL,

                            'name' => isset($file['name'][$i])
                            ? $file['name'][$i]
                            : NULL,

                            'size' => isset($file['size'][$i])
                            ? $file['size'][$i]
                            : NULL,

                            'tmp_name' => isset($file['tmp_name'][$i])
                            ? $file['tmp_name'][$i]
                            : NULL,

                            'type' => isset($file['type'][$i])
                            ? $file['type'][$i]
                            : NULL,

                        ];

                        $_data[] = $_d;
                    }
                }

                $files[$key] = $_data;
            }

        } else {
            $files = [];
        }

        $this->files = $files;
        return $this->files;
    }

    public function getFiles($key = null, $default = [])
    {
        return !is_null($key) ?
                (isset($this->files[$key]) ? $this->files[$key] : $default)
                : $this->files;
    }

	public function run()
	{
		$this->parseUrl();
		return $this->performRequest();
	}

	private function parseUrl()
	{
		$uri = $this->uri();
		return $this;
	}

	private function uri()
	{
		$uri = '';
		$server = $this->server;
		$uri = $this->prepareRequestUri();
		$uri = $this->preparePathInfo();

		$uri = array_filter(explode('/', $uri));

		$url = [];

		foreach($uri as $r){
			$url[] = $r;
		}

		$this->uri = $url;
		$this->getContent(false);
		//dd($this);
		return $url;
	}

	private function performRequest()
	{
		$uri = $this->uri;
        $real = $this->preparePathInfo();

        if (!in_array($real, $this->excludeAuth))
        {
            if(!$this->ensureLogged()){

                return $this->responseNotAuth();
            }
        }

		$controller = 'Index';
		$method = 'index';
		$params = [];

		if(count($uri) == 1){
			$controller = ucfirst($uri[0]);
		} else if(count($uri) == 2){
			$controller = ucfirst($uri[0]);
			$method = $uri[1];
		} else if(count($uri) > 2){
			$controller = ucfirst($uri[0]);
			$method = $uri[1];
			$uril = $uri;
			unset($uril[0]);
			unset($uril[1]);
			$params = $uril;
		}

		return $this->runController($controller, $method, $params);
	}

	private function runController($controller, $method, $params)
	{
		$this->app->binding('request', $this);
		$controllers_path = $this->app->controllers_path;

		$c = $controllers_path . '\\' . $controller;

		if (class_exists($c)) {
			$instance = new $c;
			if (method_exists($instance, $method)) {
				return $instance->{$method}(...$params);
			} else {
                //Try to look for methods before the HTTP method
                //Method not allowed implementation
                $m = $this->getMethod();
                if (method_exists($instance, $m . $method)) {
                    return $instance->{$m.$method}(...$params);
                }
            }
		}

		return $this->e404();
	}

	private function e404()
	{
		if ($this->ajax()) {
            return new JsonResponse([
                'status' => 404,
                'message' => 'Not Found',
            ], 404);
        }

        return view('errors/404');
	}

	protected function prepareRequestUri()
    {
        $requestUri = '';

        if ('1' == $this->server['IIS_WasUrlRewritten'] && '' != $this->server['UNENCODED_URL']) {
            // IIS7 with URL Rewrite: make sure we get the unencoded URL (double slash problem)
            $requestUri = $this->server['UNENCODED_URL'];
            unset($this->server['UNENCODED_URL']);
            unset($this->server['IIS_WasUrlRewritten']);
        } elseif ($this->server['REQUEST_URI']) {
            $requestUri = $this->server['REQUEST_URI'];

            if ('' !== $requestUri && '/' === $requestUri[0]) {
                // To only use path and query remove the fragment.
                if (false !== $pos = strpos($requestUri, '#')) {
                    $requestUri = substr($requestUri, 0, $pos);
                }
            } else {
                // HTTP proxy reqs setup request URI with scheme and host [and port] + the URL path,
                // only use URL path.
                $uriComponents = parse_url($requestUri);

                if (isset($uriComponents['path'])) {
                    $requestUri = $uriComponents['path'];
                }

                if (isset($uriComponents['query'])) {
                    $requestUri .= '?'.$uriComponents['query'];
                }
            }
        } elseif ($this->server['ORIG_PATH_INFO']) {
            // IIS 5.0, PHP as CGI
            $requestUri = $this->server['ORIG_PATH_INFO'];
            if ('' != $this->server['QUERY_STRING']) {
                $requestUri .= '?'.$this->server['QUERY_STRING'];
            }
            unset($this->server['ORIG_PATH_INFO']);
        }

        // normalize the request URI to ease creating sub-requests from this request

        $this->server['REQUEST_URI'] = $requestUri;

        return $requestUri;
    }

    protected function preparePathInfo()
    {
        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        // Remove the query string from REQUEST_URI
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        if ('' !== $requestUri && '/' !== $requestUri[0]) {
            $requestUri = '/'.$requestUri;
        }

        // if (null === ($baseUrl = $this->getBaseUrl())) {
        //     return $requestUri;
        // }

        $pathInfo = substr($requestUri, \strlen($baseUrl));
        if (false === $pathInfo || '' === $pathInfo) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        }

        return (string) $pathInfo;
    }

    public function getRequestUri()
    {
        if (null === $this->requestUri) {
            $this->requestUri = $this->prepareRequestUri();
        }

        return $this->requestUri;
    }

    public function getContent($asResource = true)
    {
        $currentContentIsResource = \is_resource($this->content);

        if (true === $asResource) {
            if ($currentContentIsResource) {
                rewind($this->content);

                return $this->content;
            }

            // Content passed in parameter (test)
            if (\is_string($this->content)) {
                $resource = fopen('php://temp', 'r+');
                fwrite($resource, $this->content);
                rewind($resource);

                return $resource;
            }

            $this->content = false;

            return fopen('php://input', 'rb');
        }

        if ($currentContentIsResource) {
            rewind($this->content);

            $content = stream_get_contents($this->content);
        }

        if (null === $content || false === $content) {
            $content = file_get_contents('php://input');
        }

        $this->content = $content;

        if ($this->Httpmethod == 'POST' && count($this->post) == 0) {
            try {
                $this->post = json_decode($content, TRUE);
            } catch (Exception $e) {
                //
            }
        }

        return $this->content;
    }

    public function getMethod()
    {
        if (null !== $this->Httpmethod) {
            return $this->Httpmethod;
        }

        $this->Httpmethod = strtoupper($this->server['REQUEST_METHOD']) ?: 'GET';

        if ('POST' !== $this->Httpmethod) {
            return $this->Httpmethod;
        }

        return 'GET';

        $method = $this->headers->get('X-HTTP-METHOD-OVERRIDE');

        if (!$method && self::$parameterOverride) {
            $method = $this->request->get('_method', $this->query->get('_method', 'POST'));
        }

        if (!\is_string($method)) {
            return $this->Httpmethod;
        }

        $method = strtoupper($method);

        if (\in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'PATCH', 'PURGE', 'TRACE'], true)) {
            return $this->Httpmethod = $method;
        }

        if (!preg_match('/^[A-Z]++$/D', $method)) {
            throw new \FL\Exceptions\SuspiciousOperationException(sprintf('Invalid method override "%s".', $method));
        }

        return $this->Httpmethod = $method;
    }

    private function ensureLogged()
    {
        return $this->app->auth->check();
    }

    public function responseNotAuth()
    {
        if ($this->ajax()) {
            return new JsonResponse([
                'status' => 404,
                'message' => 'Not Authorized',
            ], 401);
        }

        return new RedirectResponse('/login');
    }

    public function ajax()
    {
        $p = 'HTTP_ACCEPT';
        return 'application/json, text/plain, */*' == $this->server['HTTP_ACCEPT'];
    }


    public function setUserResolve(callable $fn)
    {
        $this->userResolver = $fn;
    }

    public function excludeAuth($url)
    {
        $this->excludeAuth[] = $url;
        return $this;
    }

    private function prepare()
    {
        $scheme = $this->server['REQUEST_SCHEME'];
        $host = $this->server['HTTP_HOST'];

        $url = $scheme . '://' . $host;
        $this->url = $url;
    }
}
