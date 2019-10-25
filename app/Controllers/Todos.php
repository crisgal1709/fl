<?php

namespace App\Controllers;

use App\Models\Todo;

class Todos
{
	public function index()
	{
		$todos = Todo::where('user_id', '=', auth()->user()->id)
					->get();

		return $todos;

		return [
			[
				'id' => 1,
				'text' => 'Test 1',
				'done' => false,
			],

			[
				'id' => 2,
				'text' => 'Test 2',
				'done' => false,
			],

			[
				'id' => 3,
				'text' => 'Test 3',
				'done' => false,
			],

			[
				'id' => 4,
				'text' => 'Test 4',
				'done' => false,
			],

			[
				'id' => 5,
				'text' => 'Test 5',
				'done' => false,
			],
			[
				'id' => 6,
				'text' => 'Test 6',
				'done' => false,
			],
		];
	}

	public function POSTstore()
	{
		$data = app()->request->post;
		$data['done'] = 0;
		$todo = Todo::create($data);

		return $todo;
	}

	public function POSTtoogle($id)
	{
		$todo = Todo::find($id);
		$t = $todo->changeStatus();

		return $t;
	}

	public function POSTdestroy($id)
	{
		$todo = Todo::find($id);
		$t = $todo->delete();

		return $t;
	}

	public function POSTremoveCompleted()
	{
		$todos = Todo::where('done', '=', true)
					->delete();

		return $todos;
	}

	public function GEThistory()
	{
		$todos = Todo::onlyTrashed()
					->where('user_id', '=', auth()->user()->id)
					->get();

		return $todos;

	}

	public function POSTclearCompleted()
	{
		$res = Todo::where('user_id', '=', auth()->user()->id)
				->onlyTrashed()
				->forceDelete();

		return $res;
	}
}
