<?php

namespace FL;

use Illuminate\Support\Str;

class Session
{
	protected $app;
	protected $connection;
	protected $config;
	protected $id;

	public $attributes;

	function __construct($app, $connection, $config)
	{

		$this->app = $app;
		$this->connection = $connection;
		$this->config = $config;
		$this->ensureExists();
	}

	public function getId()
	{
		$cookie = $this->getCookie();

		if (is_null($cookie)) {
			$id = Str::random(40);
		} else {
			$id = $cookie;
		}

		return $this->id = $id;
	}

	protected function ensureExists()
	{
		$id = $this->getId();
		$query = $this->getQuery();
		$result = $query->where('uuid', '=', $this->id)->first();

		if (is_null($result)) {
			$query->wheres = null;
			$query->bindings = null;
			$data = [
				'uuid' => $id,
				'payload' => serialize([]),
			];

			$query->insert($data);
		}
	}

	public function set($key, $value)
	{
		$d = $this->getAll();
		$d[$key] = $value;
		$id = $this->getId();
		$query = $this->getQuery();
		$count = $query->where('uuid', '=', $id)
					->count();

		if (!$count) {
			$query->insert([
				'uuid' => $id,
				'payload' => serialize($d),
			]);
		} else {
			$query->where('uuid', '=', $id)
				->update([
					'payload' => serialize($d),
				]);
		}

		return true;
	}

	protected function getAll()
	{
		$id = $this->getId();
		$query = $this->getQuery();

		$result = $query->where('uuid', '=', $id)
					->first();

		if (is_null($result)) {
			$p = [];
		}

		$p = unserialize($result->payload);

		return $this->attributes = $p;
	}

	public function get($key, $default = null)
	{
		$id = $this->getId();
		$query = $this->getQuery();

		$result = $query->where('uuid', '=', $id)
					->first();

		if (is_null($result)) {
			return null;
		}

		$p = unserialize($result->payload);

		return isset($p[$key]) ? $p[$key] : $default;
	}

	private function getCookie()
	{
		$cookies = $this->app->request->cookie;
		$cookie = NULL;
		$name = $this->config['name'];

		if (isset($cookies[$name])) {
			$cookie = $cookies[$name];
		}

		return $cookie;
	}

	private function getQuery()
	{
		return $this->connection
				->table($this->config['table'] ?? 'sessions');
	}

	public function flush()
	{
		$query = $this->getQuery();
		$query->where('uuid', '=', $this->id)
				->delete();

		return true;
	}

	public function renovateId()
	{
		$this->id = Str::random(40);
		return $this;
	}
}
