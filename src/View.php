<?php

namespace FL;

use FL\Contracts\Renderable;
use Exception;
use Throwable;

class View implements Renderable
{
	protected $app;
	protected $shared = [];
	public $view;
	public $data = [];

	function __construct($app)
	{
		$this->app = $app;
		$this->path = $app->base_path . DIRECTORY_SEPARATOR . $app->views_path;
	}

	public function make($view, array $data = [])
	{
		$this->view = $view;
		$this->data = $data;
		return $this;
	}

	public function render()
	{
		$contents = $this->get();
		return $contents;
	}

	private function get()
	{
		$path = $this->resolvePath();
		$__data  = array_merge($this->data, $this->shared);

		$obLevel = ob_get_level();
		ob_start();

		extract($__data, EXTR_SKIP);
		try {
			include $path;
		} catch (Exception $e) {
			return $this->handleViewException($e, $obLevel);
		} catch (Throwable $e) {
			return $this->handleViewException($e, $obLevel);
		}

		return ltrim(ob_get_clean());
	}

	public function resolvePath()
	{
		$view = $this->view;

		if (!mb_strpos($view, '.php') !== false) {
			$view .= '.php';
		}

		$v = $this->path . DIRECTORY_SEPARATOR . $view;
		return $v;
	}

	protected function handleViewException($e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            ob_end_clean();
        }

        throw $e;
    }

    public function share(array $data = [])
    {
    	$this->shared = array_merge($this->shared, $data);

    	return $this;

    }

}
