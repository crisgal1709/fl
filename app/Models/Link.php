<?php

namespace App\Models;

class Link extends Base{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'links';

	/**
	 * Fields that can be mass assigned.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'url',
		'description',
		'user_id',
	];
}
