<?php

namespace FL;

use App\Libs\Pagination;
use FL\Contracts\Renderable;
use Illuminate\Database\Capsule\Manager as DataBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Application
{
	public static $instance;

	public $bindings = [];
	public $base_path;
	public $config;

	public $views_path;
	public $controllers_path;
	public $models_path;
	protected $terminating = [];

	public static function instance()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	function __construct($base_path = '', $config = [])
	{
		$this->base_path = $base_path;
		$this->setConfig($config);
		$this->bindRequest();
		$this->binding('view', new View($this));
		$this->setDatabase();
		$this->setSession();
		$this->setMailer();

		static::$instance = $this;
	}

	public function setConfig(array $config = [])
	{
		$this->config = $config;
		$this->parseConfig();
	}

	private function parseConfig()
	{
		$this->views_path = $this->config['views_path'];
		$this->controllers_path = $this->config['controllers_path'];
		$this->models_path = $this->config['models_path'];
	}

	public function binding($name, $instance)
	{
		$this->bindings[$name] = $instance;
	}

	public function run()
	{

		if (auth()->check()) {
			view()->share([
				'user' => auth()->user(),
			]);
		}

		$response = $this->request->run();

		if ($response instanceof Response) {

		} else if ($response instanceof Model || $response instanceof Pagination) {
			$response = $response->toArray();
			$response = new JsonResponse($response);
		}
		else if (is_array($response)) {
			$response = new JsonResponse($response);
		} else if($response instanceof Collection){
			$response = new JsonResponse($response->all());
		} else {

			if ($response instanceof Renderable) {
				$response = $response->render();
			}

			$response = new Response($response);
		}

		$response = $this->prepareResponse($response);

		return $response->send();
	}

	private function prepareResponse($response)
	{
		$response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate', false);
		$response->setHeader('Cache-Control', 'no-cache, private', false);

		$response = $this->addCookie($response);

		return $response;
	}

	private function addCookie($response)
	{
		$cookie = [
			'name' => $this->config['session']['name'],
			'path' => '/',
			'domain' => null,
			'secure' => false,
			'http_only' => true,
			'expires' => time() + (3600 * 4),
		];

		$str = $cookie['name'] . '=' . $this->session->getId();

		$str .= '; expires='.gmdate('D, d-M-Y H:i:s T', $cookie['expires']).'; Max-Age=' . (3600 * 4);

		$str .= '; path='.$cookie['path'];

		$str .= '; httpOnly';

		$response->setHeader('Set-Cookie', trim($str));
		return $response;
	}

	public function __get($value)
	{
		if (isset($this->bindings[$value])) {
			return $this->call($value);
		}

		return $this->{$value};
	}

	public function call($value, ...$params)
	{
		if (isset($this->bindings[$value])) {
			$instance = $this->bindings[$value];

			if (is_callable($instance)) {
				return call_user_func_array($instance, ...$params);
			}

			return $instance;
		}
	}

	private function setDatabase()
	{
		$db = new DataBase;
		$config = $this->config['database'];
		$db->addConnection($config);
		$db->setAsGlobal();
		$db->bootEloquent();
		$this->binding('db', $db);
		return $this;
	}

	private function setSession()
	{
		$session = new Session($this, $this->db, $this->config['session']);

		$this->binding('session', $session);
		$this->binding('auth', new Auth($session));
	}

	private function setMailer()
	{
		$config = $this->config['mail'];

		$mailer = new \FL\Libs\Mail\Mailer($config, $this);
		$this->binding('mailer', $mailer);
	}

	public function terminate()
	{
		foreach ($this->terminating as $key => $value) {
			call_user_func($value);
		}

		$this->bindings = [];
		$this->base_path = null;
		$this->config = null;
		$this->views_path = null;
		$this->controllers_path = null;
		$this->models_path = null;
		$this->terminating = [];
	}

	public function bindRequest()
	{
		$request = new Request($this);

		$config = $this->config;
		if (isset($config['excludeAuth']) && is_array($config['excludeAuth'])) {
			foreach($config['excludeAuth'] as $exc){
				$request->excludeAuth($exc);
			}
		}

		$this->binding('request', $request);
	}

	public function terminating(callable $cb)
	{
		$this->terminating[] = $cb;
		return $this;
	}
}
