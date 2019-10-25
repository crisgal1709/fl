<?php

namespace App\Models;

use Illuminate\Support\Str;

class User extends Base{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

	protected $hidden = [
        'password', 'remember_token',
    ];

    public function withOutHidden()
    {
    	$atts = $this->attributes;

    	unset($atts['password']);
    	$this->setRawAttributes($atts);
    	return $this;
    }

    public function prepareLinkRecovery()
    {
        $time = time() + 60 * 60 * 24;
        $t = sha1(Str::random());
        $t .= '$'.$time;
        $ret = request()->url;
        $ret .= '/login/recovery?email=' . $this->email . '&t=' . $t;

        return $ret;
    }
}
