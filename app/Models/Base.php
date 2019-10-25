<?php

namespace App\Models;

use App\Libs\Pagination;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Base extends Eloquent
{
	public function pagination($perPage = 10, $columns = ['*'], $pageName = 'page', $page = null)
	{
		$pag = (new Pagination(static::query(), $perPage, $columns, $pageName, $page));

		return $pag;
	}
}
