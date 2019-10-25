<?php

namespace App\Controllers;

use App\Models\User;
use FL\RedirectResponse;

class Login {
	public function index()
	{
		if (auth()->check()) {
			return redirect();
		} else {
			return view('login');
		}
	}

	public function post()
	{
		$post = app()->request->post;
		if (auth()->attemp($post)) {
			if (!request()->ajax()) {
				return redirect('/', 200);
			}

			return [
				'status' => 1,
			];
		}

		if (request()->ajax()) {
			return [
				'status' => 0,
			];
		}

		return redirect('/login', 200);
	}

	public function forgot()
	{
		if (DEMO_VERSION) {
			echo 'DEMO VERSION';
			return redirect('/login');
		}

		return view('forgot');
	}

	public function forgotPost()
	{
		$email = request()->post['email'];
		$user = User::where('email', '=', $email)
					->first();

		if (!is_null($user)) {
			$view = view('mails/recovery', [
				'user' => $user->withOutHidden(),
				'link' => $user->prepareLinkRecovery(),
			]);

			$message = app()->mailer->message();
			$message->to('cristian.galeano1913@gmail.com', 'Cristian')
			->subject('Recovery Password')
			->content($view)
			->send();

			return view('forgot', [
				'messages' => [
					'success' => [
						'An email was sent with instructions to recover your password, This link expires in 24 hours'
					],
				],
			]);
		}

		return view('forgot', [
			'messages' => [
				'danger' => [
					'User Not found...',
				],
			],
		]);
	}

	public function recovery()
	{
		$q = request()->get;
		$email = $q['email'] ?? '';
		$t = $q['t'] ?? '';

		$user = User::where('email', '=', $email)
					->first();

		if (is_null($user)) {
			echo 'User Not Found';
		}

		return view('recovery', [
			'user' => $user->withOutHidden(),
		]);
	}

	public function recoveryPost()
	{
		$data = request()->post;

		$email = $data['email'] ?? '';
		$password = $data['password'] ?? '';
		$confirm_password = $data['confirm_password'] ?? '';

		if (empty($email) || empty($password) || empty($confirm_password)) {
		}

		$user = User::where('email', '=', $email)
					->first();

		if (!is_null($user)) {
			$user->password = md5($password);
			$user->save();
		}

		return view('login', [
				'noForm' => true,
				'messages' => [
					'success' => [
						'Password Changed',
					],
				],
			]);
	}
}
