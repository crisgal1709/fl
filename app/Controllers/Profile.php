<?php

namespace App\Controllers;

class Profile
{
	public function get()
	{
		return $user = auth()->user()->withOutHidden();
	}

	public function POSTupdate()
	{
		$user = auth()->user();

		if (DEMO_VERSION == true) {
			return [
				'status' => 0,
				'user' => $user->withOutHidden(),
				'message' => 'Operation not allowed in Demo Version',
			];
		}


		$data = $this->setDataProfile();
		$user->name = $data['name'];
		$user->email = $data['email'];
		if (isset($data['password'])) {
			$user->password = $data['password'];
		}
		$user->save();
		session()->set('user', $user);

		return [
			'status' => 1,
			'user' => $user->withOutHidden(),
		];
	}

	private function setDataProfile()
	{
		$data = app()->request->post;
		$d = [
			'name' => trim($data['name']),
			'email' => trim($data['email']),
		];

		if (isset($data['password'])
				&& !empty($data['password'])
				&& strlen($data['password']) > 0
		) {
			$d['password'] = md5($data['password']);
		}

		return $d;
	}
}
