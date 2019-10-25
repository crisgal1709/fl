<?php

namespace App\Libs;

use Illuminate\Database\Eloquent\Model;

class Pagination {

	protected $model;
	protected $perPage;
	protected $columns;
	protected $pageName;
	protected $page;

	protected $firstPage = 1;
	protected $lastPage;
	protected $items;
	protected $totalItems = 0;


	function __construct($model, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
	{
		$this->model = $model;
		$this->perPage = $perPage ?: $model->getPerPage();
		$this->columns = $columns;
		$this->pageName = $pageName ?: 'name';
		$this->page = $page ?: $this->resolvePage();
	}

	private function resolvePage()
	{
		$query = $_GET;

		if (in_array($this->pageName, array_keys($query))) {
			$page = (int) $query['page'];
		} else {
			$page = 1;
		}

		return $page;
	}

	private function calculate()
	{
		$totalItems = $this->model->count();

		$start = ($this->page-1) * $this->perPage;

		$totalPages = (int) ceil($totalItems / $this->perPage);

		$this->firstPage = $start;
		$this->currentPage = $start;
		$this->totalPages = $totalPages;
		$this->lastPage = ($totalPages - 1);

		return $this;
	}

	public function setModel($model)
	{
		$this->model = $model;
		return $this;
	}

	public function getData()
	{
		$this->calculate();

		$model = $this->model;
		$items = $model->limit($this->perPage)
					->offset((int) $this->currentPage)
					->get($this->columns);

		$this->items = $items;
		return $this;
	}

	public function toArray()
	{
		return [
			'firstPage' => $this->firstPage,
			'currentPage' => $this->currentPage,
			'lastPage' => $this->lastPage,
			'items' => $this->items,
			'totalItems' => $this->totalItems,
			'pageName' => $this->pageName,
			'page' => $this->page,
		];
	}
}
