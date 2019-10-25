<?php

namespace App\Controllers;

use FL\Application;
use FL\FileResponse;
use FL\RedirectResponse;

class Index
{
	function __construct()
	{
	}

	public function index()
	{
		return view('index');
	}

	public function posts()
	{
		$form = '<form enctype="multipart/form-data" method="POST" action="/index/upload">';

		$form .= '<label> Archives (Multiple)</label><br>';
		$form .= '<input type="file" name="archives[]" multiple><br><br>';
		$form .= '<label> Documents (Multiple)</label><br>';
		$form .= '<input type="file" name="documents[]" multiple><br><br>';
		$form .= '<label> Archive (Single)</label><br>';
		$form .= '<input type="file" name="archive"><br><br>';
		$form .= '<button>Send</button><br><br>';

		$form .= '</form>';

		return $form;
	}

	public function POSTupload()
	{
		$archives = request()->getFiles();
		dd($archives);

		return fileResponse($archives['archive'][0]['tmp_name']);
	}
}
