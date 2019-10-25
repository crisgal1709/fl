<?php

namespace App\Controllers;

use App\Models\User as UserModel;
use FL\RedirectResponse;
use Illuminate\Database\Query\Expression;

class User
{
	private $model;

	public function __construct()
	{
		$this->model = new UserModel;
	}

	public function index()
	{
		if (auth()->user()->rol != 'admin') {
			return [
				'status' => 0,
				'message' => 'Unauthorized',
			];
		}

		$model = $this->model;
		$users = $model->pagination(10,
			[
				'users.*',
				new Expression('COUNT(links.id) as total_links')
			]
		);
		$s = isset($_GET['search'])
				? trim($_GET['search'])
				: null;

		if (!is_null($s) && strlen($s) > 0) {
			$model = $model->getQuery()->where('users.name', 'LIKE', '%'.$s.'%');
		}

		$model = $model->whereNotIn('users.id',[auth()->user()->id]);
		$model = $model->leftJoin('links', 'links.user_id', '=', 'users.id');
		$model = $model->groupBy('users.id');

		$users = $users->setModel($model);
		return $users->getData();
	}

	public function store()
	{
		$data = app()->request->post;

		if (
			!isset($data['name'])
			|| !isset($data['email'])
			|| !isset($data['password'])
		) {
			return [
				'status' => 0,
				'message' => 'Some data to fill out'
			];
		}


		$name = trim($data['name']);
		$email = trim($data['email']);
		$password = trim($data['password']);
		$rol = trim($data['rol']);

		//Validate Email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return [
				'status' => 0,
				'message' => 'Please provide a valid email.'
			];
		}

		//Not equals
		$cont = $this->model->where('email', '=', $email)
					->count();

		if ($cont > 0) {
			return [
				'status' => 0,
				'message' => 'A user with this email already exists, provide a different one'
			];
		}

		//Save
		$user = UserModel::create([
			'name' => $name,
			'email' => $email,
			'password' => md5($password),
			'rol' => $rol,
		]);

		return [
			'status' => 1,
			'message' => 'User Stored',
			'user' => $user,
		];
	}

	public function get($id)
	{
		$user = $this->model->find($id);

		return $user->withOutHidden();
	}

	public function update($id)
	{
		$user = $this->model->find($id);
		$data = app()->request->post;

		if (is_null($user)) {
			return [
				'status' => 0,
				'message' => 'User Not Found',
				'user' => $user,
			];
		}

		$name = trim($data['name']);
		$email = trim($data['email']);
		$password = trim($data['password']);
		$rol = trim($data['rol']);
		//dd($email);

		//Validate Email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return [
				'status' => 0,
				'message' => 'Please provide a valid email.'
			];
		}

		//Not equals
		$cont = $this->model->where('email', '=', $email)
					->where('id', '!=', $user->id)
					->count();

		if ($cont > 0) {
			return [
				'status' => 0,
				'message' => 'A user with this email already exists, provide a different one'
			];
		}

		$data = [
			'name' => $name,
			'email' => $email,
			'rol' => $rol,
		];

		if (strlen($password) > 0) {
			$data['password'] = md5($password);
		}

		$user->update($data);

		return [
				'status' => 1,
				'message' => 'User Updated',
			];
	}
}
