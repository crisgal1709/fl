<?php

namespace FL;

use App\Models\User;

class Auth
{
	protected $session;
	protected $app;
	public $userResolver;

	function __construct(Session $session)
	{
		$this->session = $session;
	}

	public function check()
	{
		$user = session()->get('user');
		if (is_null($user)) {
			return false;
		}

		$fn = function() use($user){
			return $user;
		};

		app()->request->setUserResolve($fn);
		return true;
	}

	public function attemp($credentials)
	{
		if (!isset($credentials['email'])
				|| !isset($credentials['password'])
		) {
			return false;
		}

		$user = $this->getUserByCredentials($credentials);

		if (is_null($user)) {
			return false;
		}

		$fn = function() use($user){
			return $user;
		};

		app()->request->setUserResolve($fn);

		$this->setUserResolve($fn);
		$this->login($user);

		return true;
	}

	private function login($user = null)
	{
		$user = $user ?: $this->user();
		if (!is_null($user)) {
			session()->set('loggued_in', TRUE);
			session()->set('user', $user);
		}
	}

	public function getUserByCredentials($credentials)
	{
		if (!isset($credentials['email'])
				|| !isset($credentials['password'])
		) {
			return null;
		}

		$user = User::where('email', $credentials['email'])->first();

		if (is_null($user)) {
			return null;
		}

		$pass = $user->password;
		$md5 = md5($credentials['password']);
		//dd($pass, $md5);

		if ($pass !== $md5) {
			return null;
		}

		return $user;
	}

	public function setUserResolve(callable $fn)
	{
		$this->userResolver = $fn;
	}

	public function user()
	{
		return call_user_func($this->userResolver ?: function(){
			return $this->getBySession();
		});
	}

	public function getBySession()
	{
		return session()->get('user');
	}

	public function logout()
	{
		$this->user = null;
		session()->flush();
		session()->renovateId();
		return true;
	}
}
