<?php

namespace App\Controllers;

use FL\RedirectResponse;

class Logout
{
	function __construct()
	{
		$this->app = $app;
	}

	public function index()
	{
		auth()->logout();
		return redirect('/login');
	}
}
