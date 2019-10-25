<?php

use FL\Application;
use FL\FileResponse;
use FL\RedirectResponse;

function app(){
	return Application::instance();
}

function view($view = '', $data = [])
{
	return app()->view->make($view, $data);
}

function request(){
	return app()->request;
}

function session()
{
	return app()->session;
}

function auth()
{
	return app()->auth;
}

function redirect($url = '/', $code = 302, array $headers = [])
{
	return new RedirectResponse($url, $code, $headers);
}

function fileResponse($path = '', $code = 200) : FileResponse
{
	return new FileResponse($path, $coe);
}

function fixPhpFilesArray($data)
{
	$fileKeys = ['error', 'name', 'size', 'tmp_name', 'type'];

	if (!\is_array($data)) {
		return $data;
	}

	$keys = array_keys($data);
	sort($keys);

	if ($fileKeys != $keys || !isset($data['name']) || !\is_array($data['name'])) {
		return $data;
	}

	$files = $data;
	foreach ($fileKeys as $k) {
		unset($files[$k]);
	}

	foreach ($data['name'] as $key => $name) {
		$files[$key] = $this->fixPhpFilesArray([
			'error' => $data['error'][$key],
			'name' => $name,
			'type' => $data['type'][$key],
			'tmp_name' => $data['tmp_name'][$key],
			'size' => $data['size'][$key],
		]);
	}

	return $files;
}
