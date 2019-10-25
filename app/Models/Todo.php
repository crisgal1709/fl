<?php

namespace App\Models;

use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Base
{
	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'todos';

	/**
	 * @var array
	 */
	protected $fillable = [
		'text',
		'done',
		'user_id',
	];

	public $casts = [
		'done' => 'boolean',
	];

	public function changeStatus()
	{
		$done = $this->done == false ? true : false;

		$this->forceFill([
			'done' => $done,
		])->save();

		return $this;
	}

}
